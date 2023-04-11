<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\TDAmeritrade\MarketHours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $link=Tdameritrade::redirectOAuth();
        $codeHasExpired = false;
        // Message Default, gets changed if permission isn't granted yet
        $msg = 'Your Account Has Granted Trading Permission Please Set Your Trading Options';
        $linkaddress = '/account';
        $linktext = 'Account';

        // check if the logged in user has a TD Ameritrade Authentication Code
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
            $marketHoursResponse = MarketHours::isMarketOpen("EQUITY");
            dd($marketHoursResponse);
            $msg = 'Please update your config options to begin trading';
            $linkaddress = '/preferences';
            $linktext = 'Preferences';
        }

        return View::make('dashboard', [
            'msg' => $msg,
            'linkaddress' => $linkaddress,
            'linktext' => $linktext,
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
