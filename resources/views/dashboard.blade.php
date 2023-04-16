<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in! <br /> {{ $msg }}: <br />
                    <a href="{{ $linkaddress }}">{{ $linktext }}</a><br />
                    {{ $marketMsg }}


                    <div class="table-responsive">
                        <table class="table" id="urls-table">
                            <thead>
                            <tr>
                                <th>symbol</th>
                                <th>Bid</th>
                                <th>Ask</th>
                                <th>Price</th>
                                <th>Change</th>
                                <th>Open</th>
                                <th>High</th>
                                <th>Low</th>
                                <th>Volume</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($quotes as $quote)
                                <tr>
                                    <td>{{ $quote->symbol }}</td>
                                    <td>{{ $quote->bidPrice }}</td>
                                    <td>{{ $quote->lastPrice }}</td>
                                    <td>{{ ((int)$quote->openPrice - (int)$quote->lastPrice) }}</td>
                                    <td>{{ $quote->openPrice }}</td>
                                    <td>{{ $quote->highPrice }}</td>
                                    <td>{{ $quote->lowPrice }}</td>
                                    <td>{{ $quote->totalVolume }}</td>
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
