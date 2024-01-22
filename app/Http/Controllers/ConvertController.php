<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ConvertController extends Controller
{
    protected $currencies = [];
    protected $defaultFrom = 'USD';
    protected $defaultTo = 'RUB';

    public function __construct()
    {
        $this->currencies = DB::table('currency_rates')
            ->select('char_code', 'name_currency', 'created_at', 'updated_at')
            ->orderBy('name_currency')
            ->get()
            ->toArray();
    }

    private function getCurrRates($fromCurrency, $toCurrency): array
    {
        return DB::table('currency_rates')
            ->select('char_code', 'nominal', 'value_currency')
            ->whereIn('char_code', [$fromCurrency, $toCurrency])
            ->get()
            ->keyBy('char_code')
            ->toArray();
    }

    public function getIndex()
    {
        $amount = request('amount', 1);
        $rate = request('rate', '');
        $defaultFrom = request('from_currency', $this->defaultFrom);
        $defaultTo = request('to_currency', $this->defaultTo);

        $last_date_rate = $last_update_rate = '-- --';
        if ($this->currencies) {
            $last_date_rate = Carbon::createFromFormat('Y-m-d H:i:s', $this->currencies[0]->created_at)->format('d.m.Y');
            $last_update_rate = Carbon::createFromFormat('Y-m-d H:i:s', $this->currencies[0]->updated_at)->format('d.m.Y H:i:s');
        }

        return view('dashboard', [
            'amount' => $amount,
            'rate' => $rate,
            'currencies' => $this->currencies,
            'defaultFrom' => $defaultFrom,
            'defaultTo' => $defaultTo,
            'last_date_rate' => $last_date_rate,
            'last_update_rate' => $last_update_rate,
        ]);
    }

    public function getUpdateRate()
    {
        Artisan::call('get-rates');

        return redirect()->action(
            [ConvertController::class, 'getIndex']
        );
    }

    public function postConvert()
    {
        $amount = request('amount', 1);
        $from_currency = request('from_currency', null);
        $to_currency = request('to_currency', null);

        if (!is_numeric($amount)) {
            return redirect()->action(
                [ConvertController::class, 'getIndex'], [
                    'amount' => $amount,
                    'from_currency' => $from_currency,
                    'to_currency' => $to_currency,
                ]
            )->with('error', 'Пожалуйста, введите корректное число!');
        }

        $result = $this->getCurrRates($from_currency, $to_currency);

        $rate = '';
        if ($result) {
            $total_currency = $result[$from_currency]->value_currency / $result[$to_currency]->value_currency;
            $total_nominal = $result[$from_currency]->nominal / $result[$to_currency]->nominal;
            $rate = abs($total_currency * $amount / $total_nominal);
        }


        return redirect()->action(
            [ConvertController::class, 'getIndex'], [
                'amount' => $amount,
                'rate' => $rate,
                'from_currency' => $from_currency,
                'to_currency' => $to_currency
            ]
        );
    }
}
