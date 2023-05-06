<?php

namespace App\Http\Controllers;

use App\Models\Symbol;
use App\TDAmeritrade\TDAmeritrade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \JsonException
     */
    public function show(Request $request)
    {
        $validator = $request->validate([
            'symbol' => 'required|alpha:ascii|max:5',
        ]);

        if ($validator->fails()) {
            return redirect('/dashboard')
                ->withErrors($validator)
                ->withInput();
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        $Symbol = Symbol::where([
            ['symbol', '=', $validated['symbol']],
            ['updated_at', '<', Carbon::now()->subHours(5)]
        ])->get();

        if (count($Symbol) < 1) {
            Log::info('Performing API Call To Retrieve Fundamentals For: '
                .$validated['symbol']. ' '.Carbon::now());
            TDAmeritrade::getSymbol(strtoupper($validated['symbol']));

            $Symbol = Symbol::where([
                ['symbol', '=', $validated['symbol']],
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
