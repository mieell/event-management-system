<?php

namespace Tests\Feature;

use CodeIgniter\Config\Services;
use Tests\Support\DatabaseTestCase;

class ValidationTest extends DatabaseTestCase
{
    public function testEventValidationRejectsEmptyTitle(): void
    {
        $validation = Services::validation();
        $validation->setRules([
            'title' => 'required|min_length[4]',
            'capacity' => 'required|integer|greater_than[0]',
        ]);

        $passed = $validation->run([
            'title' => '',
            'capacity' => 0,
        ]);

        $this->assertEquals(false, $passed);
        $this->assertNotNull($validation->getError('title'));
    }
}
