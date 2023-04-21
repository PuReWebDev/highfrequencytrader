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


                    @isset($quotes)
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
                    @endisset

                    @if (count($orders) >= 1)
                        <div class="table-responsive">
                            <table class="table" id="open-orders-table">
                                <thead>
                                <tr>
                                    <th>Filled Orders</th>
                                    <th>Working Orders</th>
                                    <th>Rejected Orders</th>
                                    <th>Cancelled Orders</th>
                                    <th>Expired Orders</th>
                                    <th>Total Orders</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>@isset($filledCount['FILLED'])
                                            {{ $filledCount['FILLED']}}
                                        @endisset
                                        @empty($filledCount['FILLED'])
                                            0
                                        @endempty
                                    </td>
                                    <td>@isset($workingCount['WORKING'])
                                            {{ $workingCount['WORKING']}}
                                        @endisset
                                        @empty($workingCount['WORKING'])
                                            0
                                        @endempty</td>
                                    <td>@isset($rejectedCount['REJECTED'])
                                            {{ $rejectedCount['REJECTED'] }}
                                        @endisset
                                        @empty($rejectedCount['REJECTED'])
                                            0
                                        @endempty</td>
                                    <td>@isset($cancelledCount['CANCELED'])
                                            {{ $cancelledCount['CANCELED'] }}
                                        @endisset
                                        @empty($cancelledCount['CANCELED'])
                                            0
                                        @endempty</td>
                                    <td>@isset($expiredCount['EXPIRED'])
                                            {{ $expiredCount['EXPIRED'] }}
                                        @endisset
                                        @empty($expiredCount['EXPIRED'])
                                            0
                                        @endempty</td>
                                    <td>{{ $orders->count() }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>



</x-app-layout>
