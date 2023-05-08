<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Services\MathService;

class MathServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testReturnsCorrectDirection()
    {
        $ticks = [
            [
                "open" => 180.12,
                "high" => 180.46,
                "low" => 180.03,
                "close" => 180.2,
                "volume" => 21279,
                "datetime" => 1681902000000,
            ],
            [
                "open" => 180.19,
                "high" => 180.3,
                "low" => 180,
                "close" => 180.10,
                "volume" => 31079,
                "datetime" => 1681902060000,
            ]
        ];
        $up = MathService::getOptimalTradingRange([$ticks[0], $ticks[0]])['direction'];
        $down = MathService::getOptimalTradingRange([$ticks[1], $ticks[1]])['direction'];
        $noMovement = MathService::getOptimalTradingRange([$ticks[0], $ticks[1]])['direction'];
        $this->assertTrue($up === 1);
        $this->assertTrue($down === -1);
        $this->assertTrue($noMovement === 0);
    }
}
