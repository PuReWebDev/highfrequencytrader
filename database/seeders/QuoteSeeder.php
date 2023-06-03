<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quote;

class QuoteSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        
        $symbols = [
            "TSLA",
            "GE",
            "APPL"
        ];

        $ranges = [
            [ 110, 145 ],
            [ 170, 210 ],
            [ 95,  100 ]
        ];
        $j = 0;
        foreach ($symbols as $symbol)
        {
            $j++;
            for($i = 0;$i < 250;$i++)
            {
                $range = $ranges[$i % 3];
                $id = $j * ($i + 1);
                Quote::create([
                    'symbol' => $symbol,
                    'description' => $faker->text,
                    'bidPrice' => $faker->randomFloat($range[0], $range[1]),
                    'bidSize' => $faker->randomNumber(),
                    'bidId' => $id,
                    'askPrice' => $faker->randomFloat($range[0], $range[1]),
                    'askSize' => $faker->randomNumber(),
                    'askId' => $id,
                    'lastPrice' => $faker->randomFloat($range[0], $range[1]),
                    'lastSize' => $faker->randomNumber(),
                    'lastId' => $id,
                    'openPrice' => $faker->randomFloat($range[0], $range[1]),
                    'highPrice' => $range[1],
                    'lowPrice' => $range[0],
                    'closePrice' => $faker->randomFloat($range[0], $range[1]),
                    'netChange' => $faker->randomNumber(), // Needs to be calculated
                    'totalVolume' => 0,
                    'quoteTimeInLong' => $faker->randomNumber(),
                    'tradeTimeInLong' => $faker->randomNumber(),
                    'mark' => 0,
                    'exchange' => "NYSE",
                    'exchangeName' => $faker->name,
                    'marginable' => 0,
                    'shortable' => 0,
                    'volatility' => $faker->randomNumber(),
                    'digits' => $faker->randomNumber(),
                    '52WkHigh' => $range[1] + 30,
                    '52WkLow' => $range[0] - 20,
                    'peRatio' => $faker->randomNumber(),
                    'divAmount' => $faker->randomNumber(),
                    'divYield' => $faker->randomNumber(),
                    'securityStatus' => '',
                    'regularMarketLastPrice' => $faker->randomFloat($range[0], $range[1]),
                    'regularMarketLastSize' => $faker->randomNumber(),
                    'regularMarketNetChange'=> $faker->randomNumber(),
                    'regularMarketTradeTimeInLong'=> $faker->randomNumber(),
                ]);
            }
        }
    }
}
