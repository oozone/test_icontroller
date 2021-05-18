<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class SalaryBonusFeatureTest extends TestCase
{
    /**
     * Salary & Bonus Tests
     *
     * @return void
     */
    public function test_good_year()
    {
        Session::start();
        $response = $this->call('POST', 'api/csv', [
            '_token' => csrf_token(),
            'year' => 2021
        ]);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_bad_year()
    {
        Session::start();
        $response = $this->call('POST', 'api/csv', [
            '_token' => csrf_token(),
            'year' => 2020
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('/');
    }

}
