<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFundRequest;
use App\Http\Resources\FundResource;
use App\Models\Fund;
use App\Services\FundService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FundController extends Controller
{
    use AuthorizesRequests;

    protected FundService $fundService;

    public function __construct(FundService $fundService)
    {
        $this->fundService = $fundService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $funds = $this->fundService->getFundsForUser(auth()->user());

        return Inertia::render('Funds/Index', [
            'funds' => FundResource::collection($funds)->resolve(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Funds/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFundRequest $request)
    {
        $symbol = strtoupper($request->validated('symbol'));

        // Check if the symbol is valid
        $fundData = $this->fundService->validateSymbol($symbol);

        if (! $fundData) {
            return back()->withErrors([
                'symbol' => 'The symbol is not found or is invalid.',
            ]);
        }

        // Create the fund
        $fund = $this->fundService->createFund(auth()->user(), $fundData);

        return redirect()->route('funds.index')
            ->with('success', "Fund {$fund->symbol} added successfully.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fund $fund)
    {
        // Authorize the user owns this fund
        $this->authorize('delete', $fund);

        $fund->delete();

        return redirect()->route('funds.index')
            ->with('success', "Fund {$fund->symbol} removed successfully.");
    }

    /**
     * Refresh the price of a specific fund.
     */
    public function refreshPrice(Fund $fund)
    {
        // Authorize the user owns this fund
        $this->authorize('update', $fund);

        $updated = $this->fundService->updateFundPrice($fund);

        if ($updated) {
            return back()->with('success', "Price for {$fund->symbol} updated successfully.");
        }

        return back()->withErrors([
            'symbol' => "Could not update the price for {$fund->symbol}.",
        ]);
    }
}
