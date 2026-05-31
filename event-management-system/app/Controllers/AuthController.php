<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Models\RememberTokenModel;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login(): string
    {
        return view('auth/login', [
            'title' => 'Login',
            'rememberedEmail' => $this->request->getCookie('Evenira_email') ?? '',
        ]);
    }

    public function register(): string
    {
        return view('auth/register', ['title' => 'Create account']);
    }

    public function attemptLogin()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = mb_strtolower(trim((string) $this->request->getPost('email')));
        $user = (new UserModel())->findByEmail($email);

        if (! $user || ! password_verify((string) $this->request->getPost('password'), $user['password'])) {
            log_message('warning', 'Failed login attempt for email: {email}', ['email' => $email]);
            return redirect()->back()->withInput()->with('error', 'Invalid email or password.');
        }

        session()->regenerate(true);
        session()->set([
            'user_id' => (int) $user['id'],
            'fullname' => $user['fullname'],
            'email' => $user['email'],
            'role' => $user['role'],
            'logged_in' => true,
        ]);

        if ($this->request->getPost('remember')) {
            $this->issueRememberToken((int) $user['id']);
            $this->response->setCookie([
                'name' => 'Evenira_email',
                'value' => $user['email'],
                'expire' => 60 * 60 * 24 * 30,
                'httponly' => true,
                'secure' => $this->request->isSecure(),
                'samesite' => 'Lax',
            ]);
        }

        (new ActivityLogModel())->write((int) $user['id'], 'Signed in to the system.');

        return redirect()->to('/dashboard')->with('success', 'Welcome back, ' . $user['fullname'] . '.');
    }

    public function storeRegistration()
    {
        $rules = [
            'fullname' => 'required|min_length[3]|max_length[120]',
            'email' => 'required|valid_email|max_length[190]|is_unique[users.email]',
            'password' => 'required|min_length[8]|regex_match[/^(?=.*[A-Z])(?=.*\d).+$/]',
            'password_confirm' => 'required|matches[password]',
            'role' => 'required|in_list[attendee,organizer]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $role = $this->request->getPost('role');
        $model = new UserModel();
        
        $emailAddress = mb_strtolower(trim((string) $this->request->getPost('email')));
        $fullname = trim((string) $this->request->getPost('fullname'));
        
        $userId = $model->insert([
            'fullname' => $fullname,
            'email' => $emailAddress,
            'password' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => $role === 'organizer' ? 'organizer' : 'attendee',
        ], true);

        (new ActivityLogModel())->write((int) $userId, 'Created an ' . $role . ' account.');

        // Send registration email
        $email = \Config\Services::email();
        $email->setTo($emailAddress);
        $email->setSubject('Welcome to Evenira EMS!');
        
        // HTML Message
        $htmlMessage = "<h1>Welcome, " . esc($fullname) . "!</h1><p>Your account has been successfully created. We are excited to have you on board.</p>";
        $email->setMessage($htmlMessage);
        
        // Plain-text Fallback
        $email->setAltMessage("Welcome, {$fullname}! Your account has been successfully created. We are excited to have you on board.");
        
        if ($email->send()) {
            log_message('info', "Registration email sent successfully to {$emailAddress}.");
        } else {
            log_message('error', "Failed to send registration email to {$emailAddress}. Error: " . $email->printDebugger(['headers']));
        }

        return redirect()->to('/login')->with('success', 'Account created. You can sign in now.');
    }

    public function logout()
    {
        $cookie = $this->request->getCookie('Evenira_remember');
        if ($cookie && str_contains($cookie, ':')) {
            [$selector] = explode(':', $cookie, 2);
            (new RememberTokenModel())->where('selector', $selector)->delete();
        }

        $userId = session()->get('user_id');
        if ($userId) {
            (new ActivityLogModel())->write((int) $userId, 'Signed out of the system.');
        }

        session()->destroy();
        $this->response->deleteCookie('Evenira_remember');

        return redirect()->to('/login')->with('success', 'You have been signed out safely.');
    }

    private function issueRememberToken(int $userId): void
    {
        $selector = bin2hex(random_bytes(12));
        $validator = bin2hex(random_bytes(32));

        (new RememberTokenModel())->insert([
            'user_id' => $userId,
            'selector' => $selector,
            'token_hash' => hash('sha256', $validator),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $this->response->setCookie([
            'name' => 'Evenira_remember',
            'value' => $selector . ':' . $validator,
            'expire' => 60 * 60 * 24 * 30,
            'httponly' => true,
            'secure' => $this->request->isSecure(),
            'samesite' => 'Lax',
        ]);
    }
}
