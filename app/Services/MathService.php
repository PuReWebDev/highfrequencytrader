<?php

namespace App\Services;

class MathService 
{
    /**
     * Takes in an array of ticks and returns three components
     * the high and low of the ticks range based on the most avarage prices
     * and a direction based on the general direction of the array
     * 
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
        $frequencyHigh = max(array_values($frequencies));
        $frequencyLow = min(array_values($frequencies));
        $frequencyRange = range($frequencyHigh, $frequencyLow);
        $frequencyFilter = array_splice($frequencyRange, floor(count($frequencyRange) / 2))[0];
        $frequencies = array_filter($frequencies, function($value) use($frequencyFilter) {
            return $value >= $frequencyFilter;
        });
        krsort($frequencies);
        $high = number_format(array_keys($frequencies)[0], 2, '.', ',');
        $low = number_format(array_keys($frequencies)[count($frequencies) - 1], 2, '.', ',');
        return compact('high', 'low', 'direction');
    }
}