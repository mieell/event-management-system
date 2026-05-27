<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\DatabaseTestCase;

class LoginTest extends DatabaseTestCase
{
    use FeatureTestTrait;

    public function testLoginPageLoads(): void
    {
        $result = $this->get('/login');

        $result->assertStatus(200);
        $this->assertStringContainsString('Welcome back', $result->getBody());
    }

    public function testInvalidLoginValidationFails(): void
    {
        $result = $this->withHeaders(['Referer' => site_url('login')])->post('/login', [
            csrf_token() => csrf_hash(),
            'email' => 'not-an-email',
            'password' => 'short',
        ]);

        $result->assertRedirect();
        $this->assertNotNull(session()->getFlashdata('errors'));
    }
}
