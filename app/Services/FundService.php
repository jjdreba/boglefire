<?php

namespace App\Services;

use App\Models\Fund;
use App\Models\User;
use Illuminate\Support\Carbon;

class FundService
{
    protected AlphaVantageService $alphaVantageService;

    public function __construct(AlphaVantageService $alphaVantageService)
    {
        $this->alphaVantageService = $alphaVantageService;
    }

    /**
     * Validate a ticker symbol exists and return its data
     */
    public function validateSymbol(string $symbol): ?array
    {
        // Get quote data
        $quoteData = $this->alphaVantageService->getQuote($symbol);
        if (! $quoteData) {
            return null;
        }

        // Get additional symbol info
        $symbolInfo = $this->alphaVantageService->findExactSymbol($symbol);

        // Return combined data
        return [
            'symbol' => $quoteData['symbol'],
            'price' => $quoteData['price'],
            'name' => $symbolInfo['name'] ?? $symbol,
            'type' => $symbolInfo['type'] ?? 'stock',
            'exchange' => $symbolInfo['region'] ?? null,
            'currency' => $symbolInfo['currency'] ?? 'USD',
        ];
    }

    /**
     * Create a new fund for a user
     */
    public function createFund(User $user, array $fundData): Fund
    {
        return $user->funds()->create([
            'symbol' => $fundData['symbol'],
            'name' => $fundData['name'],
            'type' => $fundData['type'],
            'exchange' => $fundData['exchange'] ?? null,
            'currency' => $fundData['currency'] ?? 'USD',
            'last_price' => $fundData['price'] ?? null,
            'last_price_updated_at' => now(),
        ]);
    }

    /**
     * Update the price of a single fund
     */
    public function updateFundPrice(Fund $fund): bool
    {
        $quoteData = $this->alphaVantageService->getQuote($fund->symbol);

        if (! $quoteData) {
            return false;
        }

        $fund->update([
            'last_price' => $quoteData['price'],
            'last_price_updated_at' => now(),
        ]);

        return true;
    }

    /**
     * Get funds for a user
     */
    public function getFundsForUser(User $user)
    {
        return $user->funds()->latest()->get();
    }

    /**
     * Check if a fund needs price update (older than 24 hours)
     */
    public function needsPriceUpdate(Fund $fund): bool
    {
        if (! $fund->last_price_updated_at) {
            return true;
        }

        return $fund->last_price_updated_at->diffInHours(now()) >= 24;
    }

    /**
     * Update prices for all funds that need updates
     */
    public function updateStaleprices(): int
    {
        $count = 0;
        $staleFunds = Fund::where('last_price_updated_at', '<', Carbon::now()->subHours(24))
            ->orWhereNull('last_price_updated_at')
            ->get();

        foreach ($staleFunds as $fund) {
            if ($this->updateFundPrice($fund)) {
                $count++;
            }
        }

        return $count;
    }
}
