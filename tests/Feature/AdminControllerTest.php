<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminControllerTest extends TestCase
{
    public function test_can_get_user_logged_profile()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson(route('admin.user-profile'));

        $response->assertSuccessful()
            ->assertExactJson($user->toArray());
    }

    public function test_can_not_see_user_profile_without_being_logged()
    {
        $response = $this->getJson(route('admin.user-profile'));

        $response->assertUnauthorized();
    }

    public function test_can_see_non_admin_user_listing()
    {
        $user = User::factory()->admin()->create();

        $users = User::factory()->count(10)->create();

        $response = $this->actingAs($user)->getJson(route('admin.user-listing'));

        $response->assertSuccessful()
            ->assertDontSee($user->uuid);
    }

    public function test_can_not_see_user_listing_without_being_logged()
    {
        $response = $this->getJson(route('admin.user-listing'));

        $response->assertUnauthorized();
    }

    public function test_can_not_edit_user_without_valid_data()
    {
        $user = User::factory()->admin()->create();

        $userToEdit = User::factory()->create();

        $response = $this->actingAs($user)
            ->putJson(route('admin.user-editing', $userToEdit));

        $response->assertJsonValidationErrors([
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required'],
        ]);
    }

    public function test_can_not_edit_user_nonexistent()
    {
        $user = User::factory()->admin()->create();

        $userData = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
        ];

        $response = $this->actingAs($user)
            ->putJson(route('admin.user-editing', $this->faker->uuid()), $userData);

        $response->assertNotFound();
    }

    public function test_can_not_edit_admin_user()
    {
        $user = User::factory()->admin()->create();

        $adminUser = User::factory()->admin()->create();

        $userData = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
        ];

        $response = $this->actingAs($user)
            ->putJson(route('admin.user-editing', $adminUser->uuid), $userData);

        $response->assertUnauthorized();
    }

    public function test_can_not_edit_user()
    {
        $user = User::factory()->admin()->create();

        $userToEdit = User::factory()->create();

        $userData = [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->email(),
        ];

        $response = $this->actingAs($user)
            ->putJson(route('admin.user-editing', $userToEdit->uuid), $userData);

        $response->assertSuccessful()
            ->assertJsonFragment($userData);
        $this->assertDatabaseHas(User::class, $userData);
    }

    public function test_can_not_edit_user_without_being_logged()
    {
        $response = $this->putJson(route('admin.user-editing', $this->faker->uuid()));

        $response->assertUnauthorized();
    }

    public function test_can_not_delete_admin_user()
    {
        $user = User::factory()->admin()->create();

        $adminUser = User::factory()->admin()->create();

        $response = $this->actingAs($user)
            ->deleteJson(route('admin.user-delete', $adminUser->uuid));

        $response->assertUnauthorized();
    }

    public function test_can_not_delete_master_user()
    {
        $user = User::factory()->admin()->create();

        $masterUser = User::factory(['email' => 'admin@buckhill.co.uk'])->create();

        $response = $this->actingAs($user)
            ->deleteJson(route('admin.user-delete', $masterUser->uuid));

        $response->assertUnauthorized();
    }

    public function test_can_not_delete_user_nonexistent()
    {
        $user = User::factory()->admin()->create();

        $response = $this->actingAs($user)
            ->deleteJson(route('admin.user-delete', $this->faker->uuid()));

        $response->assertNotFound();
    }

    public function test_can_delete_user()
    {
        $user = User::factory()->admin()->create();

        $userToBeDeleted = User::factory()->create();

        $response = $this->actingAs($user)
            ->deleteJson(route('admin.user-delete', $userToBeDeleted->uuid));

        $response->assertSuccessful();
        $this->assertDatabaseMissing(User::class, [
            'id' => $userToBeDeleted->id
        ]);
    }

    public function test_can_not_delete_user_without_being_logged()
    {
        $response = $this->deleteJson(route('admin.user-delete', $this->faker->uuid()));

        $response->assertUnauthorized();
    }
}
