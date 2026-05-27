<?php

namespace App\Filters;

use App\Models\RememberTokenModel;
use App\Models\UserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('logged_in')) {
            return null;
        }

        $cookie = $request->getCookie('Evenira_remember');

        if ($cookie && str_contains($cookie, ':')) {
            [$selector, $validator] = explode(':', $cookie, 2);
            $tokenModel = new RememberTokenModel();
            $tokenModel->purgeExpired();

            $record = $tokenModel->where('selector', $selector)
                ->where('expires_at >=', date('Y-m-d H:i:s'))
                ->first();

            if ($record && hash_equals($record['token_hash'], hash('sha256', $validator))) {
                $user = (new UserModel())->find((int) $record['user_id']);
                if ($user) {
                    session()->regenerate(true);
                    session()->set([
                        'user_id' => (int) $user['id'],
                        'fullname' => $user['fullname'],
                        'email' => $user['email'],
                        'role' => $user['role'],
                        'logged_in' => true,
                    ]);

                    return null;
                }
            }
        }

        return redirect()->to('/login')->with('error', 'Please sign in to continue.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
