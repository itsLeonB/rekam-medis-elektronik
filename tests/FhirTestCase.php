<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class FhirTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Set your application's organization ID configuration
        Config::set('app.organization_id', env('organization_id'));
    }
}
