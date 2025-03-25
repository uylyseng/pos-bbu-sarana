<?php

namespace App\Http\Controllers;

use App\Models\ExchangeRate;
use App\Models\Currency;
use Illuminate\Http\Request;

class ExchangeRateController extends Controller
{
    /**
     * Display a listing of exchange rates.
     */
    public function index()
    {
        $exchangeRates = ExchangeRate::with(['fromCurrency', 'toCurrency'])->paginate(10);
        $currencies = Currency::all();
        return view('exchange-rates.index', compact('exchangeRates', 'currencies'));
    }

    /**
     * Store a newly created exchange rate.
     */
    public function store(Request $request)
    {
        $request->validate([
            'currency_from' => 'required|exists:currencies,id',
            'currency_to' => 'required|exists:currencies,id',
            'rate' => 'required|numeric|min:0',
        ]);

        ExchangeRate::create($request->all());

        return redirect()->route('exchange-rates.index')->with('success', 'Exchange Rate Added Successfully.');
    }

    /**
     * Update the specified exchange rate.
     */
    public function update(Request $request, ExchangeRate $exchangeRate)
    {
        $request->validate([
            'currency_from' => 'required|exists:currencies,id',
            'currency_to' => 'required|exists:currencies,id',
            'rate' => 'required|numeric|min:0',
        ]);

        $exchangeRate->update($request->all());

        return redirect()->route('exchange-rates.index')->with('success', 'Exchange Rate Updated Successfully.');
    }

    /**
     * Remove the specified exchange rate.
     */
    public function destroy(ExchangeRate $exchangeRate)
    {
        $exchangeRate->delete();

        return redirect()->route('exchange-rates.index')->with('success', 'Exchange Rate Deleted Successfully.');
    }
}
