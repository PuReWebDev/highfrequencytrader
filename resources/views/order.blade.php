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
                            <table class="table" id="open-orders-table">
                                <caption style="text-align: center;">Total Counts</caption>
                                <thead>
                                <tr>
                                    <th>Filled Orders</th>
                                    <th>Working Orders</th>
                                    <th>Rejected Orders</th>
                                    <th>Cancelled Orders</th>
                                    <th>Expired Orders</th>
                                    <th>Total Orders</th>
                                    <th>Stopped Orders (Last 5 Minutes)</th>
                                    <th>Stopped Orders Total</th>
                                    <th>Account Value</th>
                                    <th>Today's Profits</th>
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
                                            <td style="text-align:center">{{ $balance['0']['liquidationValue'] }}</td>
                                            <td style="text-align:center">{{ $profitsTotal }}</td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>

                        @isset($displayWorking)
                            @if ($displayWorking === true)
                                <div class="table-responsive">
                                    <table class="table" id="open-orders-table">
                                        <caption style="text-align: center;">Working Orders</caption>
                                        <thead>
                                        <tr>
                                            <th>Symbol</th>
                                            <th>Order ID</th>
                                            <th>Parent Order ID</th>
                                            <th>Instruction</th>
                                            <th>Position Effect</th>
                                            <th>Order Strategy Type</th>
                                            <th>Duration</th>
                                            <th>Price</th>
                                            <th>Expected Profit</th>
                                            <th>Status</th>
                                            <th>Quantity</th>
                                            <th>Entered Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $order)
                                            @if ($order->status === 'WORKING')
                                                <tr>
                                                    <td>{{ $order->symbol }}</td>
                                                    <td>{{ $order->orderId }}</td>
                                                    <td>{{ $order->parentOrderId}}</td>
                                                    <td>{{ $order->instruction }}</td>
                                                    <td>{{ $order->positionEffect }}</td>
                                                    <td>{{ $order->orderStrategyType }}</td>
                                                    <td>{{ $order->duration }}</td>
                                                    <td>{{ $order->price }}</td>
                                                    <td style="text-align:center">${{ $order->tradeProfit }}</td>
                                                    <td>{{ $order->status }}</td>
                                                    <td style="text-align:center">{{ $order->quantity }}</td>
                                                    <td>{{ $order->enteredTime }}</td>
                                                </tr>
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
                                    <table class="table" id="filled-orders-table">
                                        <caption style="text-align: center;">Filled Orders</caption>
                                        <thead>
                                        <tr>
                                            <th>Symbol</th>
                                            <th>Order ID</th>
                                            <th>Parent Order ID</th>
                                            <th>Instruction</th>
                                            <th>Position Effect</th>
                                            <th>Order Strategy Type</th>
                                            <th>Duration</th>
                                            <th>Price</th>
                                            <th>Trade Profit</th>
                                            <th>Quantity</th>
                                            <th>Entered Time</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $order)
                                            @if ($order->status === 'FILLED')
                                                <tr>
                                                    <td>{{ $order->symbol }}</td>
                                                    <td>{{ $order->orderId }}</td>
                                                    <td>{{ $order->parentOrderId }}</td>
                                                    <td>{{ $order->instruction }}</td>
                                                    <td>{{ $order->positionEffect }}</td>
                                                    <td>{{ $order->orderStrategyType }}</td>
                                                    <td>{{ $order->duration }}</td>
                                                    <td>{{ $order->price }}</td>
                                                    <td style="text-align:center">${{ $order->tradeProfit }}</td>
                                                    <td style="text-align:center">{{ $order->quantity }}</td>
                                                    <td>{{ $order->enteredTime }}</td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endisset

                    @if (count($orders) >= 1)
                    <div class="table-responsive">
                        <table class="table" id="orders-table">
                            <caption style="text-align: center; ">All
                                Orders</caption>
                            <thead>
                            <tr>
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
                                    <td>{{ $order->symbol }}</td>
                                    <td>{{ $order->orderId }}</td>
                                    <td>{{ $order->parentOrderId }}</td>
                                    <td>{{ $order->instruction }}</td>
                                    <td>{{ $order->positionEffect }}</td>
                                    <td>{{ $order->orderStrategyType }}</td>
                                    <td>{{ $order->duration }}</td>
                                    <td>{{ $order->price }}</td>
                                    <td style="text-align:center">{{ $order->tradeProfit }}</td>
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
