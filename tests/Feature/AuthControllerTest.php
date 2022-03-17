<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_can_not_login_without_valid_information()
    {
        $response = $this->postJson(route('admin.login'));

        $response->assertJsonValidationErrors([
            'email' => ['required'],
            'password' => ['required']
        ]);
    }

    public function test_can_login()
    {
        $user = User::factory()->create();

        $response = $this->postJson(route('admin.login'), [
            'email' => $user->email,
            'password' => 'userpassword'
        ]);

        $response->assertSuccessful();
    }

    public function test_last_login_at_is_updated_when_login_is_made()
    {
        $user = User::factory()->create();

        $date = now();

        Carbon::setTestNow($date);

        $response = $this->postJson(route('admin.login'), [
            'email' => $user->email,
            'password' => 'userpassword'
        ]);

        $newUser = $user->refresh();

        $response->assertSuccessful();
        $this->assertEquals($date->toDateTimeString(), $newUser->last_login_at);

        $newDate = $date->addMinutes(15);

        Carbon::setTestNow($newDate);

        $response = $this->postJson(route('admin.login'), [
            'email' => $user->email,
            'password' => 'userpassword'
        ]);

        $newUser = $user->refresh();

        $response->assertSuccessful();
        $this->assertEquals($newDate->toDateTimeString(), $newUser->last_login_at);
    }

    public function test_can_not_register_without_valid_information()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)->postJson(route('admin.register'));

        $response->assertJsonValidationErrors([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required'],
            'phone_number' => ['required'],
            'password' => ['required'],
        ]);
    }

    public function test_can_not_register_without_being_logged()
    {
        $userData = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phone_number' => $this->faker->phoneNumber(),
        ];

        $response = $this->postJson(
            route('admin.register'),
            array_merge($userData, [
                'password' => 'userpassword',
                'password_confirmation' => 'userpassword'
            ])
        );

        $response->assertUnauthorized();
        $this->assertDatabaseMissing(User::class, $userData);
    }

    public function test_can_register_with_valid_information()
    {
        $user = User::factory()->admin()->create();

        $userData = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
            'phone_number' => $this->faker->phoneNumber(),
        ];

        $response = $this->actingAs($user)->postJson(
            route('admin.register'),
            array_merge($userData, [
                'password' => 'userpassword',
                'password_confirmation' => 'userpassword'
            ])
        );

        $response->assertSuccessful();
        $this->assertDatabaseHas(User::class, $userData);
    }

    public function test_can_logout()
    {
        $user = User::factory()->admin()->create();

        auth()->login($user);

        $response = $this->actingAs($user)->postJson(route('admin.logout'));

        $response->assertSuccessful();
    }
}
