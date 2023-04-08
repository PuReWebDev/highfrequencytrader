<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Token;
use App\Services\AdminService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        if (empty($token['0']['access_token'])) {
            $authentication = collect([
                'grant_type' => config("tdameritrade.grant_type"),
                'access_type' => config('tdameritrade.access_type'),
                'code' => $token['0']['code'],
                'client_id' => config('tdameritrade.client_id'),
                'redirect_uri' => config('tdameritrade.redirect_uri')
            ]);

            $authResponse = AdminService::login($authentication->toArray());

            Log::info($authResponse);

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
