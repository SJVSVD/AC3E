<?php

namespace App\Http\Controllers;
use AmrShawky\LaravelCurrency\Facade\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(){
        $rates = Currency::rates()
            ->latest()
            ->symbols(['USD', 'EUR', 'GBP', 'PEN', 'ARS', 'JPY', 'CNY'])
            ->base('CLP')
            ->amount(1000)
            ->round(2)
            ->get();
        $crypto_rates = Currency::rates()
            ->latest()
            ->symbols(['BTC', 'ETH', 'IMPT', 'CCHG', 'XLM'])
            ->base('CLP')
            ->amount(1000)
            ->round(4)
            ->source('crypto')
            ->get();

        $output = [];
        array_push($output, $rates, $crypto_rates);
        return response()->json($output);
    }
}
