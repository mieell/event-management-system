<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Models\EventModel;
use App\Models\NotificationModel;
use App\Models\RegistrationModel;
use App\Models\UserModel;

class DashboardController extends BaseController
{
    public function index()
    {
        return match (session()->get('role')) {
            'admin' => redirect()->to('/admin/dashboard'),
            'organizer' => redirect()->to('/organizer/dashboard'),
            default => $this->user(),
        };
    }

    public function admin(): string
    {
        $stats = cache('admin_dashboard_stats');
        if (! $stats) {
            $registrationModel = new RegistrationModel();
            $approved = $registrationModel->where('status', 'approved')->countAllResults();
            $stats = [
                'users' => (new UserModel())->countAllResults(),
                'events' => (new EventModel())->countAllResults(),
                'registrations' => (new RegistrationModel())->countAllResults(),
                'revenue' => $approved * 499,
            ];
            cache()->save('admin_dashboard_stats', $stats, 60);
        }

        return view('dashboard/admin', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'recentActivities' => (new ActivityLogModel())->recent(8),
            'notifications' => (new NotificationModel())->forUser((int) session()->get('user_id')),
            'monthlyRegistrations' => (new RegistrationModel())->monthlyTotals(6),
            'categoryCounts' => (new EventModel())->categoryCounts(),
        ]);
    }

    public function organizer(): string
    {
        return view('dashboard/organizer', [
            'title' => 'Organizer Dashboard',
            'stats' => [
                // Query optimization: select('id') avoids fetching full rows for COUNT queries (instruction: Line 129)
                'events' => (new EventModel())->select('id')->countAllResults(),
                'registrations' => (new RegistrationModel())->select('id')->countAllResults(),
                'pending' => (new RegistrationModel())->select('id')->where('status', 'pending')->countAllResults(),
                'featured' => (new EventModel())->select('id')->where('status', 'featured')->countAllResults(),
            ],
            'recentActivities' => (new ActivityLogModel())->recent(8),
            'upcomingEvents' => (new EventModel())->upcoming(6),
        ]);
    }

    public function user(): string
    {
        $userId = (int) session()->get('user_id');

        return view('dashboard/user', [
            'title' => 'User Dashboard',
            'upcomingEvents' => (new EventModel())->upcoming(6),
            'registrations' => (new RegistrationModel())->withDetails($userId)->findAll(5),
            'notifications' => (new NotificationModel())->forUser($userId),
        ]);
    }

    public function analytics(): string
    {
        $this->cachePage(60);

        return view('dashboard/analytics', [
            'title' => 'Analytics',
            'monthlyRegistrations' => (new RegistrationModel())->monthlyTotals(12),
            'categoryCounts' => (new EventModel())->categoryCounts(),
            'roleCounts' => (new UserModel())->roleCounts(),
        ]);
    }
}
