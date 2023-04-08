<?php

namespace App\Http\Controllers;

use App\Models\Token;
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
        // Message Default, gets changed if permission isn't granted yet
        $msg = 'Your Account Has Granted Trading Permission Please Set Your Trading Options <a href="/account">Account</a>';

        // check if the logged in user has a TD Ameritrade Authentication Code
        $code = Token::where('user_id', Auth::id())->get();

        if (empty($code['0']) || count($code) < 1) {
            $msg = 'Please click here to <a href="https://auth.tdameritrade.com/auth?response_type=code&redirect_uri=https%3A%2F%2Fhighfrequencytradingservices.com%2Fcallback%2F&client_id=PP8HGBTPVG2IXJ9FY9NRPZFJ7M82UIPR%40AMER.OAUTHAP">Grant Needed Trading Permission</a>';
        }

        return View::make('dashboard', ['msg' => $msg]);
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
