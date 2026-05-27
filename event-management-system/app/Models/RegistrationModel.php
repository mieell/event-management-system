<?php

namespace App\Models;

use CodeIgniter\Model;

class RegistrationModel extends Model
{
    protected $table = 'registrations';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'event_id', 'status', 'payment_proof'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'user_id' => 'required|integer',
        'event_id' => 'required|integer',
        'status' => 'permit_empty|in_list[pending,approved,rejected,cancelled]',
    ];

    public function withDetails(?int $userId = null): self
    {
        $this->select('registrations.*, users.fullname, users.email, events.title AS event_title, events.event_date, events.event_time, events.capacity')
            ->join('users', 'users.id = registrations.user_id')
            ->join('events', 'events.id = registrations.event_id');

        if ($userId !== null) {
            $this->where('registrations.user_id', $userId);
        }

        return $this->orderBy('registrations.created_at', 'DESC');
    }

    public function alreadyRegistered(int $userId, int $eventId): bool
    {
        return (bool) $this->where('user_id', $userId)
            ->where('event_id', $eventId)
            ->first();
    }

    public function approvedCountForEvent(int $eventId): int
    {
        return $this->where('event_id', $eventId)
            ->whereIn('status', ['pending', 'approved'])
            ->countAllResults();
    }

    public function monthlyTotals(int $months = 6): array
    {
        return $this->select('DATE_FORMAT(created_at, "%Y-%m") AS month, COUNT(*) AS total')
            ->where('created_at >=', date('Y-m-01 00:00:00', strtotime("-{$months} months")))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->findAll();
    }
}
