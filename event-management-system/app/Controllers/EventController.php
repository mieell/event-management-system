<?php

namespace App\Controllers;

use App\Libraries\SecureUploader;
use App\Models\ActivityLogModel;
use App\Models\EventModel;
use App\Models\RegistrationModel;
use RuntimeException;

class EventController extends BaseController
{
    public function index(): string
    {
        $filters = [
            'search' => trim((string) $this->request->getGet('search')),
            'category' => trim((string) $this->request->getGet('category')),
            'date' => trim((string) $this->request->getGet('date')),
        ];

        if (in_array(session()->get('role'), ['admin', 'organizer'], true)) {
            $filters['status'] = trim((string) $this->request->getGet('status'));
        }

        $eventModel = new EventModel();
        $events = $eventModel->filtered($filters)->paginate(9, 'events');

        return view('events/index', [
            'title' => 'Events',
            'events' => $events,
            'pager' => $eventModel->pager,
            'filters' => $filters,
            'categories' => (new EventModel())->select('category')->distinct()->orderBy('category')->findAll(),
        ]);
    }

    public function show(int $id): string
    {
        $eventModel = new EventModel();
        $event = $eventModel->select('events.*, COUNT(registrations.id) AS registrations_count')
            ->join('registrations', 'registrations.event_id = events.id AND registrations.status != "cancelled"', 'left')
            ->where('events.id', $id)
            ->groupBy('events.id')
            ->first();

        if (! $event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Event not found.');
        }

        $alreadyRegistered = false;
        if (session()->get('logged_in')) {
            $alreadyRegistered = (new RegistrationModel())->alreadyRegistered((int) session()->get('user_id'), $id);
        }

        return view('events/show', [
            'title' => $event['title'],
            'event' => $event,
            'alreadyRegistered' => $alreadyRegistered,
        ]);
    }

    public function create(): string
    {
        return view('events/form', [
            'title' => 'Create Event',
            'event' => null,
            'action' => site_url('events'),
            'methodLabel' => 'Create Event',
        ]);
    }

    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $image = (new SecureUploader())->image($this->request->getFile('image'), 'events', 4096);
        } catch (RuntimeException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        $eventId = (new EventModel())->insert($this->payload($image), true);
        (new ActivityLogModel())->write((int) session()->get('user_id'), 'Created event #' . $eventId . '.');
        cache()->delete('admin_dashboard_stats');

        return redirect()->to('/events/' . $eventId)->with('success', 'Event created successfully.');
    }

    public function edit(int $id): string
    {
        $event = (new EventModel())->find($id);
        if (! $event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Event not found.');
        }

        return view('events/form', [
            'title' => 'Edit Event',
            'event' => $event,
            'action' => site_url('events/' . $id),
            'methodLabel' => 'Save Changes',
        ]);
    }

    public function update(int $id)
    {
        $eventModel = new EventModel();
        $event = $eventModel->find($id);
        if (! $event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Event not found.');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $image = (new SecureUploader())->image($this->request->getFile('image'), 'events', 4096) ?? $event['image'];
        } catch (RuntimeException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        $eventModel->update($id, $this->payload($image));
        (new ActivityLogModel())->write((int) session()->get('user_id'), 'Updated event #' . $id . '.');
        cache()->delete('admin_dashboard_stats');

        return redirect()->to('/events/' . $id)->with('success', 'Event updated successfully.');
    }

    public function delete(int $id)
    {
        $event = (new EventModel())->find($id);
        if (! $event) {
            return redirect()->back()->with('error', 'Event not found.');
        }

        (new EventModel())->delete($id);
        (new ActivityLogModel())->write((int) session()->get('user_id'), 'Deleted event #' . $id . '.');
        cache()->delete('admin_dashboard_stats');

        return redirect()->to('/events')->with('success', 'Event deleted successfully.');
    }

    private function rules(): array
    {
        return [
            'title' => 'required|min_length[4]|max_length[180]',
            'description' => 'required|min_length[20]',
            'venue' => 'required|min_length[3]|max_length[180]',
            'category' => 'required|max_length[80]',
            'event_date' => 'required|valid_date[Y-m-d]',
            'event_time' => 'required',
            'capacity' => 'required|integer|greater_than[0]',
            'status' => 'required|in_list[draft,published,featured,cancelled,completed]',
        ];
    }

    private function payload(?string $image): array
    {
        return [
            'title' => trim((string) $this->request->getPost('title')),
            'description' => trim((string) $this->request->getPost('description')),
            'venue' => trim((string) $this->request->getPost('venue')),
            'category' => trim((string) $this->request->getPost('category')),
            'event_date' => (string) $this->request->getPost('event_date'),
            'event_time' => (string) $this->request->getPost('event_time'),
            'capacity' => (int) $this->request->getPost('capacity'),
            'status' => (string) $this->request->getPost('status'),
            'image' => $image,
        ];
    }
}
