<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['fullname', 'email', 'password', 'role', 'profile_image'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $validationRules = [
        'fullname' => 'required|min_length[3]|max_length[120]',
        'email' => 'required|valid_email|max_length[190]',
        'role' => 'required|in_list[admin,organizer,attendee]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function findByEmail(string $email): ?array
    {
        return $this->where('email', mb_strtolower(trim($email)))->first();
    }

    public function searchable(?string $keyword = null): self
    {
        // Query optimization: select only needed columns, exclude password hash (instruction: Line 129)
        $this->select('id, fullname, email, role, profile_image, created_at');

        if ($keyword !== null && trim($keyword) !== '') {
            $keyword = trim($keyword);
            $this->groupStart()
                ->like('fullname', $keyword)
                ->orLike('email', $keyword)
                ->orLike('role', $keyword)
                ->groupEnd();
        }

        return $this->orderBy('created_at', 'DESC');
    }

    public function roleCounts(): array
    {
        $rows = $this->select('role, COUNT(*) AS total')
            ->groupBy('role')
            ->findAll();

        return array_column($rows, 'total', 'role') + [
            'admin' => 0,
            'organizer' => 0,
            'attendee' => 0,
        ];
    }
}
