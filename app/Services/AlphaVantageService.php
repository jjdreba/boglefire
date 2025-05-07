<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlphaVantageService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://www.alphavantage.co/query';

    public function __construct()
    {
        $this->apiKey = config('services.alpha_vantage.key');
    }

    /**
     * Get real-time quote data for a symbol
     */
    public function getQuote(string $symbol): ?array
    {
        try {
            $response = Http::get($this->baseUrl, [
                'function' => 'GLOBAL_QUOTE',
                'symbol' => $symbol,
                'apikey' => $this->apiKey,
            ]);

            $data = $response->json();

            if (isset($data['Global Quote']) && ! empty($data['Global Quote']['01. symbol'])) {
                return [
                    'symbol' => $data['Global Quote']['01. symbol'],
                    'price' => $data['Global Quote']['05. price'],
                    'change' => $data['Global Quote']['09. change'],
                    'change_percent' => $data['Global Quote']['10. change percent'],
                    'volume' => $data['Global Quote']['06. volume'],
                    'latest_trading_day' => $data['Global Quote']['07. latest trading day'],
                ];
            }

            return null;
        } catch (RequestException $e) {
            Log::error('Alpha Vantage API error in getQuote: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Search for symbols by keywords
     */
    public function searchSymbol(string $keywords): ?array
    {
        try {
            $response = Http::get($this->baseUrl, [
                'function' => 'SYMBOL_SEARCH',
                'keywords' => $keywords,
                'apikey' => $this->apiKey,
            ]);

            $data = $response->json();

            if (isset($data['bestMatches']) && ! empty($data['bestMatches'])) {
                $matches = [];
                foreach ($data['bestMatches'] as $match) {
                    $matches[] = [
                        'symbol' => $match['1. symbol'],
                        'name' => $match['2. name'],
                        'type' => $match['3. type'],
                        'region' => $match['4. region'],
                        'currency' => $match['8. currency'],
                    ];
                }

                return $matches;
            }

            return null;
        } catch (RequestException $e) {
            Log::error('Alpha Vantage API error in searchSymbol: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Find an exact match for a symbol
     */
    public function findExactSymbol(string $symbol): ?array
    {
        $matches = $this->searchSymbol($symbol);
        if (! $matches) {
            return null;
        }

        // Find exact match
        foreach ($matches as $match) {
            if (strtoupper($match['symbol']) === strtoupper($symbol)) {
                return $match;
            }
        }

        // If no exact match, return the first match
        return $matches[0];
    }
}
