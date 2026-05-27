<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run(): void
    {
        $password = password_hash('Admin123!', PASSWORD_DEFAULT);

        $users = [
            ['fullname' => 'Tournament Administrator', 'email' => 'admin@Evenira.test', 'password' => $password, 'role' => 'admin'],
            ['fullname' => 'MLBB Event Organizer', 'email' => 'organizer@Evenira.test', 'password' => $password, 'role' => 'organizer'],
            ['fullname' => 'Sample Player', 'email' => 'attendee@Evenira.test', 'password' => $password, 'role' => 'attendee'],
        ];

        foreach ($users as $user) {
            if (! $this->db->table('users')->where('email', $user['email'])->countAllResults()) {
                $user['created_at'] = date('Y-m-d H:i:s');
                $this->db->table('users')->insert($user);
            }
        }

        $events = [
            [
                'title' => 'Mythic Cup Mobile Legends Tournament',
                'description' => 'A 5v5 Mobile Legends tournament for campus squads, featuring bracket play, finals livestreaming, MVP awards, and championship prizes.',
                'venue' => 'Celestial Esports Arena',
                'category' => 'Tournament',
                'event_date' => date('Y-m-d', strtotime('+18 days')),
                'event_time' => '09:00:00',
                'image' => 'assets/img/events/mythic-cup.svg',
                'capacity' => 160,
                'status' => 'featured',
            ],
            [
                'title' => 'Legend Squad Scrim Night',
                'description' => 'A managed scrim event for Mobile Legends teams that want ranked practice, room codes, referee support, and post-match result tracking.',
                'venue' => 'Aurora Gaming Hub',
                'category' => 'Scrim',
                'event_date' => date('Y-m-d', strtotime('+32 days')),
                'event_time' => '18:30:00',
                'image' => 'assets/img/events/scrim-night.svg',
                'capacity' => 80,
                'status' => 'published',
            ],
            [
                'title' => 'MPL Finals Watch Party',
                'description' => 'A community watch party for Mobile Legends fans with live match analysis, cosplay corners, prediction games, and team meetups.',
                'venue' => 'Skyline Esports Lounge',
                'category' => 'Watch Party',
                'event_date' => date('Y-m-d', strtotime('+45 days')),
                'event_time' => '15:00:00',
                'image' => 'assets/img/events/watch-party.svg',
                'capacity' => 120,
                'status' => 'published',
            ],
        ];

        foreach ($events as $event) {
            if (! $this->db->table('events')->where('title', $event['title'])->countAllResults()) {
                $event['created_at'] = date('Y-m-d H:i:s');
                $this->db->table('events')->insert($event);
            }
        }
    }
}
