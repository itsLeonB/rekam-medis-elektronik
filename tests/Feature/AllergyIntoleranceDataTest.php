<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\Traits\ExamplePayload;

class AllergyIntoleranceDataTest extends TestCase
{
    use DatabaseTransactions;
    use ExamplePayload;
}
