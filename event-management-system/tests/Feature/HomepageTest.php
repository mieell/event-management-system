<?php

namespace Tests\Feature;

use CodeIgniter\Test\FeatureTestTrait;
use Tests\Support\DatabaseTestCase;

class HomepageTest extends DatabaseTestCase
{
    use FeatureTestTrait;

    public function testHomepageLoads(): void
    {
        $result = $this->get('/');

        $result->assertStatus(200);
        $this->assertNotNull($result->getBody());
    }
}
