<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Please sign in to continue.');
        }

        $allowed = $arguments ?? [];
        $role = (string) session()->get('role');

        if ($allowed !== [] && ! in_array($role, $allowed, true)) {
            return service('response')
                ->setStatusCode(403)
                ->setBody(view('errors/html/error_403', [
                    'message' => 'Your account does not have permission to open this area.',
                ]));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
