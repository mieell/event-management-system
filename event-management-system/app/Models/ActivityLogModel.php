<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'activity', 'created_at'];
    protected $useTimestamps = false;

    public function write(?int $userId, string $activity): bool
    {
        return (bool) $this->insert([
            'user_id' => $userId,
            'activity' => $activity,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    public function recent(int $limit = 10): array
    {
        return $this->select('activity_logs.*, users.fullname')
            ->join('users', 'users.id = activity_logs.user_id', 'left')
            ->orderBy('activity_logs.created_at', 'DESC')
            ->limit($limit)
            ->find();
    }
}
