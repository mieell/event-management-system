<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'title',
        'description',
        'venue',
        'category',
        'event_date',
        'event_time',
        'image',
        'capacity',
        'status',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'title' => 'required|min_length[4]|max_length[180]',
        'description' => 'required|min_length[20]',
        'venue' => 'required|min_length[3]|max_length[180]',
        'category' => 'required|max_length[80]',
        'event_date' => 'required|valid_date[Y-m-d]',
        'event_time' => 'required',
        'capacity' => 'required|integer|greater_than[0]|less_than_equal_to[100000]',
        'status' => 'required|in_list[draft,published,featured,cancelled,completed]',
    ];

    public function filtered(array $filters = []): self
    {
        $this->select('events.*, COUNT(registrations.id) AS registrations_count')
            ->join('registrations', 'registrations.event_id = events.id AND registrations.status != "cancelled"', 'left')
            ->groupBy('events.id');

        if (! empty($filters['search'])) {
            $term = trim($filters['search']);
            $this->groupStart()
                ->like('events.title', $term)
                ->orLike('events.description', $term)
                ->orLike('events.venue', $term)
                ->orLike('events.category', $term)
                ->groupEnd();
        }

        if (! empty($filters['category'])) {
            $this->where('events.category', $filters['category']);
        }

        if (! empty($filters['date'])) {
            $this->where('events.event_date', $filters['date']);
        }

        if (! empty($filters['status'])) {
            $this->where('events.status', $filters['status']);
        } else {
            $this->whereIn('events.status', ['published', 'featured']);
        }

        return $this->orderBy('events.event_date', 'ASC');
    }

    public function upcoming(int $limit = 5): array
    {
        return $this->whereIn('status', ['published', 'featured'])
            ->where('event_date >=', date('Y-m-d'))
            ->orderBy('event_date', 'ASC')
            ->limit($limit)
            ->find();
    }

    public function categoryCounts(): array
    {
        return $this->select('category, COUNT(*) AS total')
            ->groupBy('category')
            ->orderBy('total', 'DESC')
            ->findAll();
    }
}
