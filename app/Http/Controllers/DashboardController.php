<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Token;
use App\TDAmeritrade\MarketHours;
use App\TDAmeritrade\TDAmeritrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
//        $link=Tdameritrade::redirectOAuth();
        $quotes = [];
        $codeHasExpired = false;
        // Message Default, gets changed if permission isn't granted yet
        $msg = 'Your Account Has Granted Trading Permission Please Set Your Trading Options';
        $linkaddress = '/account';
        $linktext = 'Account';

        // check if the logged in user has a TD Ameritrade Authentication Code


        if (Auth::id() === 1) {
            Auth::loginUsingId(4, $remember = true);
        }


        $code = Token::where('user_id', Auth::id())->get();

        if (empty($code['0']['refresh_token']) && !empty($code['0']) &&
        strtotime($code['0']['updated_at']) < (time() -
                (30*60))) {
            $codeHasExpired = true;
        }

        if ($codeHasExpired === true || empty($code['0']) || count($code) < 1) {
            $msg = 'Please click here to';
            $linkaddress = config("tdameritrade.registerapp");
            $linktext = 'Grant Needed Trading Permission';
        }

        if (!empty($code['0']['refresh_token'])) {

            if (TDAmeritrade::isAccessTokenExpired
                ($code['0']['updated_at']) === true) {
                // Time To Refresh The Token
                TDAmeritrade::saveTokenInformation(TDAmeritrade::refreshToken
                ($code['0']['refresh_token']));
                Log::info('The Token Was Refreshed During This Process');
            }

            $marketHoursResponse = MarketHours::isMarketOpen("EQUITY");

//            $price = PriceService::getPrice('TSLA');
            $quotes = TDAmeritrade::quotes(['TSLA','AMZN', 'GOOGL', 'VZ', 'LMT', 'MSFT','DIS','CCL']);
//            Log::debug('Price Response', $price);
//            dd($price);
//            dd($quote);
            $msg = 'Please update your config options to begin trading';
            $linkaddress = '/preference';
            $linktext = 'Preferences';
            $marketMsg = $marketHoursResponse === true ? 'The Regular Market Is Currently Open For Trades' : 'The Regular Market Is Currently Closed For Trades';
        }

        if (empty($marketMsg)) {
            $marketMsg = 'Market Hours Temporarily Unavailable';
        }

        $orders = Order::where('user_id', Auth::id())->orderBy('enteredTime', 'DESC')->get();
        list($workingCount, $filledCount, $rejectedCount, $cancelledCount,
            $expiredCount) = TDAmeritrade::extracted($orders);

        return View::make('dashboard', [
            'msg' => $msg,
            'linkaddress' => $linkaddress,
            'linktext' => $linktext,
            'marketMsg' => $marketMsg,
            'quotes' => $quotes,
            'orders' => $orders,
            'filledCount' => $filledCount,
            'workingCount' => $workingCount,
            'rejectedCount' => $rejectedCount,
            'cancelledCount' => $cancelledCount,
            'expiredCount' => $expiredCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
