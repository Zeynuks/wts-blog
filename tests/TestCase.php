<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    protected function signIn($user = null)
    {
        $user = $user ?: User::factory()->create();
        Sanctum::actingAs($user);
        return $user;
    }
}
