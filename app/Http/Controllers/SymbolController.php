<?php

namespace App\Http\Controllers;

use App\Models\Symbol;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SymbolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param string $symbol
     * @return \Illuminate\Http\Response
     */
    public function show(string $symbol)
    {
        $Symbol = Symbol::where([
            ['symbol', '=', $symbol],
            ['updated_at', '<', Carbon::now()->subHours(5)]
        ])->get();

        if (empty($Symbol)) {
            TDAmeritrade::getSymbol($symbol);
            dd('We ain\'t got nada, look at this empty thang: '. $Symbol);
            $Symbol = Symbol::where([
                ['symbol', '=', $symbol],
                ['updated_at', '<', Carbon::now()->subHours(5)]
            ])->get();
        }
        dd($Symbol);
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
