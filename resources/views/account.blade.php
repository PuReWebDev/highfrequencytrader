<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Account') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="table-responsive">
                        <table class="table" id="account-table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">{{ $name }} Value</th>
                                <th scope="col">Total Cash</th>
                                <th scope="col">Account Type</th>
                                <th scope="col">Round Trips</th>
                                <th scope="col">Is Day Trader</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <th scope="row">{{ $count }}</th>
                                <td>{{ $balance['0']['accountValue'] }}</td>
                                <td>{{  $balance['0']['totalCash'] }}</td>
                                <td>{{  $balance['0']['type'] }}</td>
                                <td>{{  $balance['0']['roundTrips'] }}</td>
                                <td>{{  $balance['0']['isDayTrader'] }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">symbol</th>
                                <th scope="col">Qty</th>
                                <th scope="col">Price</th>
                                <th scope="col">Market Value</th>
                                <th scope="col">Gain ($) P/L</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($positions as $position)
                                <tr>
                                    <th scope="row">{{ $count++ }}</th>
                                    <td>{{ $position['symbol'] }}</td>
                                    <td>{{ $position['longQuantity'] }}</td>
                                    <td>{{ $position['averagePrice'] }}</td>
                                    <td>{{ $position['marketValue'] }}</td>
                                    <td>{{ $position['currentDayProfitLoss'] }}</td>
                                    <td>{{ $position['marketValue'] }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
