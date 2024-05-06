<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
//        return [
//            'name' => $this->faker->name,
//            'email' => $this->faker->unique()->safeEmail,
//            'email_verified_at' => now(),
//            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
//            'remember_token' => Str::random(10),
//        ];

        return [
            'id'            => $this->faker->unique()->numberBetween(168093503986860063, 368093503986860062),
            'username'      => $this->faker->name,
            'discriminator' => $this->faker->numberBetween(1000, 9999),
            'email'         => $this->faker->unique()->safeEmail,
            'avatar'        => 'a25465f6835eb5b3a1a526d1224ffa6f',
            'verified'      => '1',
            'locale'        => 'en-US',
            'mfa_enabled'   => '1',
            'refresh_token' => 'kbUgiT8twqlod46MhIzXLJW7mm9Gmv',
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
}
