<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fund>
 */
class FundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fundTypes = ['stock', 'etf', 'mutual_fund', 'bond', 'index'];
        $exchanges = ['NYSE', 'NASDAQ', 'AMEX', 'LSE', 'TSX', null];

        return [
            'user_id' => User::factory(),
            'symbol' => strtoupper(fake()->lexify('???')),
            'name' => fake()->company().' '.fake()->randomElement(['Fund', 'ETF', 'Index', 'Trust', 'Group']),
            'type' => fake()->randomElement($fundTypes),
            'exchange' => fake()->randomElement($exchanges),
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP', 'CAD']),
            'last_price' => fake()->randomFloat(2, 10, 1000),
            'last_price_updated_at' => fake()->dateTimeBetween('-1 week', 'now'),
        ];
    }
}
