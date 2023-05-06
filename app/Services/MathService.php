<?php

namespace App\Services;

class MathService 
{
    /**
     * @param Array collection of ticks
     * @return Array range of high frequency values
     */
    public static function getOptimalTradingRange(array $ticks) : array
    {
        $prices = [];
        $direction = 0;
        
        foreach($ticks as $tick) {
            array_push($prices, (string)$tick['close']);
            $direction += ($tick['close'] - $tick['open']) <=> 0;
        }

        $direction = $direction <=> 0;
        $frequencies = array_count_values($prices);
        $frequencies = array_filter($frequencies, function($value) {
            return $value >= 3;
        });
        krsort($frequencies);
        $high = number_format(array_keys($frequencies)[0], 2, '.', ',');
        $low = number_format(array_keys($frequencies)[count($frequencies) - 1], 2, '.', ',');
        return compact('high', 'low', 'direction');
    }
}