<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\TDAmeritrade\MarketHours;
use App\Services\PriceService;
use App\Models\Price;


class MarketTest extends TestCase
{
    /**
     * Can get the open/close state of the market
     *
     * @return void
     */
    public function testCanCheckIfMarketIsOpen()
    {
        $setting = MarketHours::isMarketOpen("EQUITY");
        $isInMarketHours = date('H') < 17;
        if ($isInMarketHours) {
            $this->assertTrue($setting);
        } else {
            $this->assertTrue(!$setting);

        }
    }

    /**
     * Can check the current price of a symbol
     *
     * @return void
     */
    public function testCanFetchCurrentSymbolPrice()
    {
        $price = PriceService::getPrice("TSLA");
        $this->assertTrue(!is_null($price));
        // TODO assert things about this $price object
        //$this->assert()
    }
}
