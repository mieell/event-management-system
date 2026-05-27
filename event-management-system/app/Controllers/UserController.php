<?php

namespace App\Controllers;

use App\Libraries\SecureUploader;
use App\Models\ActivityLogModel;
use App\Models\RegistrationModel;
use App\Models\UserModel;
use RuntimeException;

class UserController extends BaseController
{
    public function index(): string
    {
        $keyword = trim((string) $this->request->getGet('search'));
        $userModel = new UserModel();

        return view('users/index', [
            'title' => 'Users',
            'users' => $userModel->searchable($keyword)->paginate(12, 'users'),
            'pager' => $userModel->pager,
            'search' => $keyword,
            'roleCounts' => (new UserModel())->roleCounts(),
        ]);
    }

    public function profile(): string
    {
        $userId = (int) session()->get('user_id');

        return view('users/profile', [
            'title' => 'Profile',
            'user' => (new UserModel())->find($userId),
            'registrations' => (new RegistrationModel())->withDetails($userId)->findAll(5),
        ]);
    }

    public function updateProfile()
    {
        $userId = (int) session()->get('user_id');
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[120]',
            'email' => 'required|valid_email|max_length[190]|is_unique[users.email,id,' . $userId . ']',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        try {
            $profileImage = (new SecureUploader())->image($this->request->getFile('profile_image'), 'profiles', 2048) ?? $user['profile_image'];
        } catch (RuntimeException $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        $payload = [
            'fullname' => trim((string) $this->request->getPost('fullname')),
            'email' => mb_strtolower(trim((string) $this->request->getPost('email'))),
            'profile_image' => $profileImage,
        ];

        $userModel->update($userId, $payload);
        session()->set([
            'fullname' => $payload['fullname'],
            'email' => $payload['email'],
        ]);

        (new ActivityLogModel())->write($userId, 'Updated profile information.');

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
