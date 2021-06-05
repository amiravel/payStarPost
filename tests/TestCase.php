<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');
    }

    public function headers()
    {
        return [
            'client_id' => 123456789,
            'token' => 123456789
        ];
    }
}
