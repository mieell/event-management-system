<?php

namespace App\Models;

use CodeIgniter\Model;

class RememberTokenModel extends Model
{
    protected $table = 'remember_tokens';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = ['user_id', 'selector', 'token_hash', 'expires_at', 'created_at'];
    protected $useTimestamps = false;

    public function purgeExpired(): void
    {
        $this->where('expires_at <', date('Y-m-d H:i:s'))->delete();
    }
}
