<?php

namespace Tests\App;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use App\Models\UserModel;

class ProjectTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testHomePage()
    {
        // Test 1: Cover testHomePage() -> assertStatus(200)
        $result = $this->get('/');
        $result->assertStatus(200);
    }

    public function testUserModelTableProperty()
    {
        // Test 2: Test a model method with assertEquals()
        $model = new UserModel();
        $this->assertEquals('users', $model->table);
    }

    public function testEmailValidationRule()
    {
        // Test 3: Test a validation rule passes/fails
        $validation = \Config\Services::validation();

        // Failing case
        $fails = $validation->check('not-an-email', 'required|valid_email');
        $this->assertFalse($fails);

        // Passing case
        $passes = $validation->check('test@Evenira.com', 'required|valid_email');
        $this->assertTrue($passes);
    }
}
