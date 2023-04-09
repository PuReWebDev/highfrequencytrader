<?php

namespace App\Http\Controllers;

use App\Models\Token;
use App\TDAmeritrade\TDAmeritrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $token = Token::where('user_id', Auth::id())->get();

        if (empty($token['0']['refresh_token']) && !empty($token['0']) &&
            strtotime($token['0']['updated_at']) < (time() -
                (30*60))) {
            return redirect('/dashboard');
        }

        if (empty($token['0']['refresh_token'])) {
//            $authentication = collect([
//                'grant_type' =>config("tdameritrade.grant_type"),
//                'access_type' => config('tdameritrade.access_type'),
//                'code' => urldecode($token['0']['code']),
//                'client_id' => config('tdameritrade.client_id'),
//                'redirect_uri' => urlencode(config('tdameritrade.redirect_url')),
//            ]);
//
//            Log::info('client_id: ' .urlencode(config('tdameritrade.client_id')));
//            Log::info('grant_type: ' .urlencode(config("tdameritrade.grant_type")));
//            Log::info('access_type: ' .urlencode(config('tdameritrade.access_type')));
//            Log::info('redirect_uri: ' .urlencode(config('tdameritrade.redirect_url')));
//            Log::info(urldecode($token['0']['code']));
//
//            $authResponse = AdminService::login($authentication->toArray());
//
//
//            Log::info($authResponse);

            $authResponse = TDAmeritrade::createAccessToken(urldecode($token['0']['code']));

            dd($authResponse);

            Token::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'token' => $authResponse->token,
                    'refresh_token' => $authResponse->refresh_token
                ]
            );
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
