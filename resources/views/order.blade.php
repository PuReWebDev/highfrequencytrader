<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (count($orders) >= 1)

                                @foreach($orders as $order)
                                    @if ($order->status === 'WORKING')
                                        @php($displayWorking = true)
                                    @endif

                                    @if ($order->status === 'FILLED')
                                        @php($displayFilled = true)
                                    @endif
                                @endforeach
                    @endif

                        <div class="table-responsive">
                            <table class="table table-striped caption-top"
                                   id="open-orders-table">
                                <caption style="text-align: center;">Total Counts</caption>
                                <thead>
                                <tr>
                                    <th style="white-space: nowrap;">Filled</th>
                                    <th>Working</th>
                                    <th>Rejected</th>
                                    <th>Cancelled</th>
                                    <th>Expired</th>
                                    <th>Total</th>
                                    <th style="white-space: nowrap;">Stopped (5 Mins)</th>
                                    <th style="white-space: nowrap;">Stopped Total</th>
                                    <th style="white-space: nowrap;">Account Value</th>
                                    <th style="white-space: nowrap;">Today's Gains</th>
                                    <th style="white-space: nowrap;">Total Loss</th>
                                    <th style="white-space: nowrap;">Total P/L</th>
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
                                            <td style="text-align:center">@isset($stoppedCount['FILLED'])
                                                    {{ $stoppedCount['FILLED'] }}
                                                @endisset
                                                @empty($stoppedCount['FILLED'])
                                                    0
                                                @endempty</td>
                                            <td style="text-align:center">@isset($stoppedTotalCount['FILLED'])
                                                    {{ $stoppedTotalCount['FILLED'] }}
                                                @endisset
                                                @empty($stoppedTotalCount['FILLED'])
                                                    0
                                                @endempty</td>
                                            <td style="text-align:center">{{$balance['1']['liquidationValue'] }}</td>
                                            <td style="text-align:center">${{$profitsTotal }}</td>
                                            <td
                                                style="text-align:center">-${{$lossTotal }}</td>
                                            <td
                                                style="text-align:center">${{number_format($pl,2,'.',',')}}</td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>

                        @isset($displayWorking)
                            @if ($displayWorking === true)
                                <div class="table-responsive">
                                    <table class="table table-striped caption-top"
                                           id="open-orders-table">
                                        <caption style="text-align: center;">Working Orders</caption>
                                        <thead>
                                        <tr>
                                            <th scope="col"style="white-space: nowrap;"><strong>#</strong></th>
                                            <th style="white-space: nowrap;">Symbol</th>
                                            <th style="white-space: nowrap;">Order ID</th>
                                            <th style="white-space: nowrap;">Parent ID</th>
                                            <th style="white-space: nowrap;">Instruction</th>
{{--                                            <th style="white-space: nowrap;">Position Effect</th>--}}
{{--                                            <th style="white-space: nowrap;">Order Strategy Type</th>--}}
{{--                                            <th style="white-space: nowrap;">Duration</th>--}}
                                            <th style="white-space: nowrap;">Price</th>
                                            <th style="white-space: nowrap;">Expected Profit</th>
                                            <th style="white-space: nowrap;">Status</th>
                                            <th style="white-space: nowrap;">Quantity</th>
                                            <th style="white-space: nowrap;">Entered Time</th>
                                            <th style="white-space: nowrap;">Elapsed Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $order)
                                            @if ($order->status === 'WORKING')
                                                @if ($order->price)
                                                <tr>
                                                    <td scope="row"
                                                        style="white-space: nowrap;">{{
                                                    $loop->index }}</td>
                                                    <td style="white-space: nowrap;">{{ $order->symbol
                                                    }}</td>
                                                    <td style="white-space: nowrap;">{{ $order->orderId }}</td>
                                                    <td style="white-space: nowrap;">{{ $order->parentOrderId}}</td>
                                                    <td style="white-space: nowrap;">{{ $order->instruction }}</td>
{{--                                                    <td>{{ $order->positionEffect }}</td>--}}
{{--                                                    <td>{{ $order->orderStrategyType }}</td>--}}
{{--                                                    <td>{{ $order->duration }}</td>--}}
                                                    <td style="white-space: nowrap;">@if ($order->price)$@endif{{ $order->price }}</td>
                                                    <td
                                                        style="text-align:center;">$@if ($order->quantity === 10){{ number_format($order->quantity * .10,2,'.',',') }}@else{{ number_format($order->quantity * .05,2,'.',',') }}@endif</td>
                                                    <td style="white-space: nowrap;">{{ $order->status }}</td>
                                                    <td
                                                        style="text-align:center;">{{ $order->quantity }}</td>
                                                    <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($order->enteredTime)->toDateTimeString() }}</td>
                                                    <td>{{ gmdate('H:i:s',
                                                    \Carbon\Carbon::parse
                                                    ($order->enteredTime)
                                                    ->diffInSeconds(\Carbon\Carbon::now())) }}</td>
                                                </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endisset

                        @isset($displayFilled)
                            @if ($displayFilled === true)
                                <div class="table-responsive">
                                    <table class="table table-striped caption-top"
                                           id="filled-orders-table">
                                        <caption style="text-align: center;">Filled Orders</caption>
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th style="white-space:nowrap;">Symbol</th>
                                            <th style="white-space:nowrap;">Order ID</th>
                                            <th style="white-space:nowrap;">Parent Order ID</th>
                                            <th style="white-space:nowrap;">Instruction</th>
{{--                                            <th style="white-space:nowrap;">Position Effect</th>--}}
{{--                                            <th style="white-space:nowrap;">Order Strategy Type</th>--}}
{{--                                            <th style="white-space:nowrap;">Duration</th>--}}
                                            <th style="white-space:nowrap;">Price</th>
                                            <th style="white-space:nowrap;">Trade Profit</th>
                                            <th style="white-space:nowrap;">Quantity</th>
                                            <th style="white-space:nowrap;">Entered Time</th>
                                            <th style="white-space:nowrap;">Close Time</th>
                                            <th style="white-space:nowrap;
                                            text-align:right;">Elapsed Time</th>
                                        </tr>
                                        </thead>
                                        <tbody style=".table-striped">
                                        @foreach($orders as $order)
                                            @if ($order->status === 'FILLED')
                                                <tr>
                                                    <th scope="row">{{ $loop->index }}</th>
                                                    <td>{{ $order->symbol }}</td>
                                                    <td>{{ $order->orderId }}</td>
                                                    <td>{{ $order->parentOrderId }}</td>
                                                    <td>{{ $order->instruction }}</td>
{{--                                                    <td>{{ $order->positionEffect }}</td>--}}
{{--                                                    <td>{{ $order->orderStrategyType }}</td>--}}
{{--                                                    <td>{{ $order->duration }}</td>--}}
                                                    <td>@if ($order->price)$@endif{{ $order->price }}</td>
                                                    <td
                                                        style="text-align:center;@if($order->stopPrice)color:red;@endif">@isset($order->actualProfit)
                                                            @if($order->stopPrice)-@endif${{$order->actualProfit }}
                                                        @endisset
                                                    </td>
                                                    <td style="text-align:center">{{ $order->quantity }}</td>
                                                    <td>{{
                                                    \Carbon\Carbon::parse
                                                    ($order->enteredTime)
                                                    ->setTimezone('America/New_York')->format('Y-m-d g:i A') }}</td>
{{--                                                    <td>{{ \Carbon\Carbon::parse($order->closeTime)->format('g:i:s a') }}</td>--}}
                                                    <td nowrap>{{
                                                    \Carbon\Carbon::parse($order->closeTime)->setTimezone('America/New_York')->format('Y-m-d g:i A') }}</td>
{{--                                                    <td>{{ \Carbon\Carbon::parse($order->closeTime)->toDateTimeString() }}</td>--}}

                                                    <td nowrap>{{ gmdate('H:i:s', \Carbon\Carbon::parse($order->closeTime)->diffInSeconds($order->enteredTime)) }}</td>
{{--                                                    <td>{{ \Carbon\Carbon::parse($order->closeTime)->diffInSeconds($order->enteredTime)->format('g:i:s a') }}</td>--}}

                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endisset

{{--                    @if (count($orders) >= 1)--}}
                    @if (count($orders) >= 1000000)
                    <div class="table-responsive">
                        <table class="table" id="orders-table">
                            <caption style="text-align: center; ">All
                                Orders</caption>
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th>Symbol</th>
                                <th>Order ID</th>
                                <th>Parent Order ID</th>
                                <th>Instruction</th>
                                <th>Position Effect</th>
                                <th>Order Strategy Type</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Trade Profit</th>
                                <th>Status</th>
                                <th>Quantity</th>
                                <th>Entered Time</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <th scope="row">{{ $loop->index }}</th>
                                    <td>{{ $order->symbol }}</td>
                                    <td>{{ $order->orderId }}</td>
                                    <td>{{ $order->parentOrderId }}</td>
                                    <td>{{ $order->instruction }}</td>
                                    <td>{{ $order->positionEffect }}</td>
                                    <td>{{ $order->orderStrategyType }}</td>
                                    <td>{{ $order->duration }}</td>
                                    <td>{{ $order->price }}</td>
                                    <td style="text-align:center">{{ $order->actualProfit }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td style="text-align:center">{{ $order->quantity }}</td>
                                    <td>{{ $order->enteredTime }}</td>
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
