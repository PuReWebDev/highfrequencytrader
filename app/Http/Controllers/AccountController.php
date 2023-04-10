<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Position;
use App\Models\Token;
use App\TDAmeritrade\Accounts;
use App\TDAmeritrade\TDAmeritrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    /**
     * @param mixed $position_value
     * @param $account_id
     */
    private static function savePositionInformation(mixed $position_value, $account_id): void
    {
        Position::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'symbol' => $position_value['instrument']['symbol'],
                'account_id' => $account_id
            ],
            [
                'shortQuantity' => $position_value['shortQuantity'],
                'averagePrice' => $position_value['averagePrice'],
                'currentDayCost' => $position_value['currentDayCost'],
                'currentDayProfitLoss' => $position_value['currentDayProfitLoss'],
                'currentDayProfitLossPercentage' => $position_value['currentDayProfitLossPercentage'],
                'longQuantity' => $position_value['longQuantity'],
                'settledLongQuantity' => $position_value['settledLongQuantity'],
                'settledShortQuantity' => $position_value['settledShortQuantity'],
                'assetType' => $position_value['instrument']['assetType'],
                'cusip' => $position_value['instrument']['cusip'],
                'symbol' => $position_value['instrument']['symbol'],
                'marketValue' => $position_value['marketValue'],
                'maintenanceRequirement' => $position_value['maintenanceRequirement'],
                'previousSessionLongQuantity' => $position_value['previousSessionLongQuantity'],
            ]
        );
    }

    /**
     * @param mixed $authResponse
     */
    public static function saveTokenInformation(mixed $authResponse): void
    {
        Token::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'access_token' => $authResponse['access_token'] ?: null,
                'refresh_token' => $authResponse['refresh_token'] ?: null,
                'scope' => $authResponse['scope'] ?: null,
                'expires_in' => $authResponse['expires_in'] ?: null,
                'refresh_token_expires_in' => $authResponse['refresh_token_expires_in'] ?: null,
                'token_type' => $authResponse['token_type'] ?: null,
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = Token::where('user_id', Auth::id())->get();

        if (empty($token['0']['code'])) {
            return redirect('/dashboard');
        }

        if (empty($token['0']['refresh_token']) && !empty($token['0']) &&
            strtotime($token['0']['updated_at']) < (time() -
                (30*60))) {
            return redirect('/dashboard');
        }

        if (empty($token['0']['refresh_token'])) {

            if (!empty($token['0']['code'])) {
                $authResponse = TDAmeritrade::createAccessToken($token['0']['code']);
            }

            if (!empty($authResponse)) {
                self::saveTokenInformation($authResponse);
            }
        }

        if (!empty($token['0']['access_token'])) {
            if (TDAmeritrade::isAccessTokenExpired
            ($token['0']['updated_at']) === true) {
                // Time To Refresh The Token
                Log::info('The Token Was Determined To Be Expired');
                self::saveTokenInformation(TDAmeritrade::refreshToken($token['0']['refresh_token']));
                Log::info('We have refreshed the token automagically');
            }

            Log::info('Retrieving Account Information');
            // Retrieve The Account Information
            $accountResponse = Accounts::getAccounts();

            if (!empty($accountResponse['error'])) {
                self::saveTokenInformation(TDAmeritrade::refreshToken($token['0']['refresh_token']));
                return redirect('/account');
            }

            Log::info('Account Information Retrieved');

            Log::info($accountResponse);
            self::saveAccountInformation($accountResponse);
            dd($accountResponse);
        }


    }

    /**
     * @param mixed $authResponse
     */
    public static function saveAccountInformation(mixed $accountResponse): void
    {
        foreach ($accountResponse as $key => $value) {
            Log::info('The Key: '.$key);
            Log::debug('The Value',$value);
            $account = Account::updateOrCreate(
                ['user_id' => Auth::id(),'account_id' => $value['securitiesAccount']['accountId']],
                [
                    'user_id' => Auth::id() ?: null,
                    'account_id' => $value['securitiesAccount']['accountId'] ?: null,
                    'type' => $value['securitiesAccount']['type'] ?: null,
                    'roundTrips' => $value['securitiesAccount']['roundTrips'],
                    'isDayTrader' => $value['securitiesAccount']['isDayTrader'],
                    'isClosingOnlyRestricted' => $value['securitiesAccount']['isClosingOnlyRestricted'],
                ]
            );

            foreach ($value['securitiesAccount']['positions'] as
                     $position_key => $position_value) {
                Log::info('The Position Key: '.$position_key);
                Log::debug('The Position Value',$position_value);
                self::savePositionInformation($position_value, $account['0']['account_id']);
            }

        }
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
