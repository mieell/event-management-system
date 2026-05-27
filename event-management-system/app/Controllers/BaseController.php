<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['form', 'url', 'text', 'filesystem'];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger): void
    {
        parent::initController($request, $response, $logger);
    }

    protected function currentUser(): array
    {
        return [
            'id' => (int) session()->get('user_id'),
            'fullname' => (string) session()->get('fullname'),
            'email' => (string) session()->get('email'),
            'role' => (string) session()->get('role'),
        ];
    }

    protected function isAdmin(): bool
    {
        return session()->get('role') === 'admin';
    }

    protected function isOrganizer(): bool
    {
        return session()->get('role') === 'organizer';
    }
}
