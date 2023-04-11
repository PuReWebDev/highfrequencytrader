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

                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ $name }} Value</th>
                            <th scope="col">Total Cash</th>
                            <th scope="col">Margin Balance</th>
                            <th scope="col">Available Funds For Trading</th>
                            <th scope="col">Stock Buying Power</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>{{ $balance['0']['accountValue'] }}</td>
                            <td>{{  $balance['0']['totalCash'] }}</td>
                            <td>{{  $balance['0']['marginBalance'] }}</td>
                            <td>{{  $balance['0']['cashAvailableForTrading'] }}</td>
                            <td>{{  $balance['0']['stockBuyingPower'] }}</td>
                        </tr>
                        </tbody>
                    </table>

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
                                <td>{{ $position['cashAvailableForTrading'] }}</td>
                                <td>{{ $position['averagePrice'] - $position['marketValue'] }}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
