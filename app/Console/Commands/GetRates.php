<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GetRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get rates';

    protected $clientHttp;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->clientHttp = new Client();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $response = $this->clientHttp->request('GET', env('RATES_HOST'));
            $result = simplexml_load_string($response->getBody(), 'SimpleXMLElement', 16384);
        } catch (GuzzleException $e) {
            $this->warn('Command get-rates error: ' . $e->getMessage());
            exit;
        }

        $json = json_encode($result);
        $arrRates = json_decode($json, true);

        $createdDate = Carbon::createFromFormat('!d.m.Y', $arrRates['@attributes']['Date'], 'UTC');
        $updatedDate = Carbon::now('UTC');

        $arrDB[] = [
            'num_code' => '643',
            'char_code' => 'RUB',
            'nominal' => 1,
            'name_currency' => 'Российский рубль',
            'value_currency' => 1,
            'value_unit_currency' => 1,
            'created_at' => $createdDate,
            'updated_at' => $updatedDate,
        ];
        foreach ($arrRates['Valute'] as $item => $value) {
            $arrDB[] = [
                'num_code' => $value['NumCode'],
                'char_code' => $value['CharCode'],
                'nominal' => $value['Nominal'],
                'name_currency' => $value['Name'],
                'value_currency' => str_replace(',', '.', $value['Value']),
                'value_unit_currency' => str_replace(',', '.', $value['VunitRate']),
                'created_at' => $createdDate,
                'updated_at' => $updatedDate,
            ];
        }

        DB::table('currency_rates')->upsert($arrDB, ['num_code'], ['value_currency', 'value_unit_currency', 'created_at', 'updated_at']);

        return 0;
    }
}
