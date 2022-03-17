<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => $this->faker->uuid(),
            'first_name' => $this->faker->name(),
            'last_name' => $this->faker->name(),
            'is_admin' => false,
            'avatar' => $this->faker->uuid(),
            'is_marketing' => $this->faker->boolean(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2a$12$H67YfGvV84BwWP9GEEPiR.ktHQSU2i9/7u98TKDgSCCB7GONiVy1y', // userpassword
            'phone_number' => $this->faker->phoneNumber(),
            'last_login_at' => $this->faker->dateTime(),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => true,
            ];
        });
    }
}
