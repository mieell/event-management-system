<?php

namespace Tests\Feature;

use App\Models\EventModel;
use Tests\Support\DatabaseTestCase;

class EventCreationTest extends DatabaseTestCase
{
    public function testEventCanBeCreatedThroughModel(): void
    {
        $model = new EventModel();
        $eventId = $model->insert([
            'title' => 'PHP 8 Cloud Deployment Lab',
            'description' => 'A complete technical session about deploying CodeIgniter applications to cloud platforms.',
            'venue' => 'Cloud Studio A',
            'category' => 'Deployment',
            'event_date' => date('Y-m-d', strtotime('+10 days')),
            'event_time' => '14:00:00',
            'capacity' => 60,
            'status' => 'published',
        ], true);

        $created = $model->find($eventId);

        $this->assertNotNull($created);
        $this->assertEquals('PHP 8 Cloud Deployment Lab', $created['title']);
    }
}
