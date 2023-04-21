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


                    @if (count($quotes) >= 1)
                    <div class="table-responsive">
                        <table class="table" id="quote-table">
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
                                    <td>{{ $quote->askPrice }}</td>
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
                    @endif

                    @if (count($orders) >= 1)
                    <div class="table-responsive">
                        <table class="table" id="orders-table">
                            <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Order ID</th>
                                <th>Instruction</th>
                                <th>Position Effect</th>
                                <th>Order Strategy Type</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Quantity</th>
                                <th>Session</th>
                                <th>Created At</th>
                                <th>Last At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->symbol }}</td>
                                    <td>{{ $order->orderId }}</td>
                                    <td>{{ $order->instruction }}</td>
                                    <td>{{ $order->positionEffect }}</td>
                                    <td>{{ $order->orderStrategyType }}</td>
                                    <td>{{ $order->duration }}</td>
                                    <td>{{ $order->price }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->session }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->updated_at }}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif

                </div>

            </div>
        </div>
    </div>



</x-app-layout>
