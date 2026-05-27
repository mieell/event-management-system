<?php

namespace App\Controllers;

use App\Libraries\EventMailer;
use App\Libraries\SecureUploader;
use App\Models\ActivityLogModel;
use App\Models\EventModel;
use App\Models\NotificationModel;
use App\Models\RegistrationModel;
use App\Models\UserModel;
use RuntimeException;

class RegistrationController extends BaseController
{
    public function index(): string
    {
        $registrationModel = new RegistrationModel();

        return view('registrations/index', [
            'title' => 'Registrations',
            'registrations' => $registrationModel->withDetails()->paginate(12, 'registrations'),
            'pager' => $registrationModel->pager,
        ]);
    }

    public function mine(): string
    {
        $registrationModel = new RegistrationModel();
        $userId = (int) session()->get('user_id');

        return view('registrations/mine', [
            'title' => 'My Registrations',
            'registrations' => $registrationModel->withDetails($userId)->paginate(10, 'registrations'),
            'pager' => $registrationModel->pager,
        ]);
    }

    public function store(int $eventId)
    {
        $userId = (int) session()->get('user_id');
        $event = (new EventModel())->find($eventId);

        if (! $event || ! in_array($event['status'], ['published', 'featured'], true)) {
            return redirect()->back()->with('error', 'This event is not available for registration.');
        }

        $registrationModel = new RegistrationModel();
        if ($registrationModel->alreadyRegistered($userId, $eventId)) {
            return redirect()->back()->with('error', 'You are already registered for this event.');
        }

        if ($registrationModel->approvedCountForEvent($eventId) >= (int) $event['capacity']) {
            return redirect()->back()->with('error', 'This event has reached its registration capacity.');
        }

        $registrationId = $registrationModel->insert([
            'user_id' => $userId,
            'event_id' => $eventId,
            'status' => 'pending',
        ], true);

        $user = (new UserModel())->find($userId);
        (new NotificationModel())->push($userId, 'Your registration for ' . $event['title'] . ' was received.');
        (new ActivityLogModel())->write($userId, 'Registered for event #' . $eventId . '.');
        (new EventMailer())->registrationConfirmation($user, $event);
        cache()->delete('admin_dashboard_stats');

        return redirect()->to('/my-registrations')->with('success', 'Registration submitted. Upload payment proof when ready.');
    }

    public function uploadPayment(int $registrationId)
    {
        $registrationModel = new RegistrationModel();
        $registration = $registrationModel->find($registrationId);
        $userId = (int) session()->get('user_id');

        if (! $registration || ((int) $registration['user_id'] !== $userId && ! $this->isAdmin() && ! $this->isOrganizer())) {
            return redirect()->back()->with('error', 'Payment upload is not available for this registration.');
        }

        try {
            $proof = (new SecureUploader())->image($this->request->getFile('payment_proof'), 'payments', 4096);
        } catch (RuntimeException $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        if (! $proof) {
            return redirect()->back()->with('error', 'Please choose an image to upload.');
        }

        $registrationModel->update($registrationId, [
            'payment_proof' => $proof,
            'status' => 'pending',
        ]);

        (new ActivityLogModel())->write($userId, 'Uploaded payment proof for registration #' . $registrationId . '.');

        return redirect()->back()->with('success', 'Payment proof uploaded securely.');
    }

    public function updateStatus(int $registrationId)
    {
        $rules = ['status' => 'required|in_list[pending,approved,rejected,cancelled]'];
        if (! $this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $registrationModel = new RegistrationModel();
        $registration = $registrationModel->withDetails()->where('registrations.id', $registrationId)->first();

        if (! $registration) {
            return redirect()->back()->with('error', 'Registration not found.');
        }

        $status = (string) $this->request->getPost('status');
        $registrationModel->update($registrationId, ['status' => $status]);

        (new NotificationModel())->push((int) $registration['user_id'], 'Your registration for ' . $registration['event_title'] . ' is now ' . $status . '.');

        $user = [
            'fullname' => $registration['fullname'],
            'email' => $registration['email'],
        ];
        $event = [
            'title' => $registration['event_title'],
            'event_date' => $registration['event_date'],
            'event_time' => $registration['event_time'],
        ];
        (new EventMailer())->registrationStatus($user, $event, $status);
        (new ActivityLogModel())->write((int) session()->get('user_id'), 'Marked registration #' . $registrationId . ' as ' . $status . '.');
        cache()->delete('admin_dashboard_stats');

        return redirect()->back()->with('success', 'Registration status updated.');
    }
}
