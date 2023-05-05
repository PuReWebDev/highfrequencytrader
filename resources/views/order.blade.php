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



                    <div class="container text-center">

                        <form method="post" action="/orders"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}
                        <div class="row align-items-start">
                            <div class="col">

                                <h2 class="mb-4">Select A Start Date To View
                                    Orders From</h2>

                                <div class="form-group">
                                    <div class='input-group date'
                                         id='fromdatetimepicker'>
                                        <input name="from_date" type='text'
                                               class="form-control" autocomplete="off" />
                                        <div class="input-group-addon">
                                            <svg xmlns="http://www.w3
                                            .org/2000/svg" width="33"
                                                 height="33"
                                                 fill="currentColor" class="bi bi-calendar-date" viewBox="0 0 16 16">
                                                <path d="M6.445 11.688V6.354h-.633A12.6 12.6 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61h.675zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82h-.684zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col">

                                <h2 class="mb-4">Select A To Date To View
                                    Orders From</h2>
                                <div class="form-group">
                                    <div class='input-group date'
                                         id='todatetimepicker'>
                                        <input name="to_date" type='text'
                                               class="form-control" autocomplete="off" />
                                        <div class="input-group-addon">
                                            <svg xmlns="http://www.w3
                                            .org/2000/svg" width="33"
                                                 height="33"
                                                 fill="currentColor" class="bi bi-calendar-date" viewBox="0 0 16 16">
                                                <path d="M6.445 11.688V6.354h-.633A12.6 12.6 0 0 0 4.5 7.16v.695c.375-.257.969-.62 1.258-.777h.012v4.61h.675zm1.188-1.305c.047.64.594 1.406 1.703 1.406 1.258 0 2-1.066 2-2.871 0-1.934-.781-2.668-1.953-2.668-.926 0-1.797.672-1.797 1.809 0 1.16.824 1.77 1.676 1.77.746 0 1.23-.376 1.383-.79h.027c-.004 1.316-.461 2.164-1.305 2.164-.664 0-1.008-.45-1.05-.82h-.684zm2.953-2.317c0 .696-.559 1.18-1.184 1.18-.601 0-1.144-.383-1.144-1.2 0-.823.582-1.21 1.168-1.21.633 0 1.16.398 1.16 1.23z"/>
                                                <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col">
                                <h2 class="mb-4">Submit Once You've
                                    Selected Your Date Ranges</h2>
                                <button class="btn btn-primary"
                                        type="submit">Submit</button>
                            </div>
                        </div>

                        </form>
                    </div>

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
                                   id="summary-table">
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
{{--                                            <th scope="col"style="white-space: nowrap;"><strong>#</strong></th>--}}
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
                                            <th style="white-space: nowrap;">Elapsed</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($orders as $order)
                                            @if ($order->status === 'WORKING')
                                                @if ($order->price)
                                                <tr>
{{--                                                    <td scope="row"--}}
{{--                                                        style="white-space: nowrap;">{{--}}
{{--                                                    $loop->index }}</td>--}}
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
                                                        style="text-align:center;">$@if ($order->quantity === 10){{ number_format($order->quantity * .10,2,'.',',') }}@else{{ number_format($order->quantity * .10,2,'.',',') }}@endif</td>
                                                    <td style="white-space: nowrap;">{{ $order->status }}</td>
                                                    <td
                                                        style="text-align:center;">{{ $order->quantity }}</td>
{{--                                                    <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($order->enteredTime)->toDateTimeString() }}</td>--}}
                                                    <td style="white-space: nowrap;">{{ \Carbon\Carbon::parse($order->enteredTime)->setTimezone('America/New_York')->format('Y-m-d g:i A') }}</td>
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

                    <hr class="hr" style="margin-top: 25px;margin-bottom:
                    25px;"/>

                        @isset($displayFilled)
                            @if ($displayFilled === true)
                                <div class="table-responsive">
                                    <table class="table table-striped caption-top"
                                           id="filled-orders-table">
                                        <caption style="text-align: center;">Filled Orders</caption>
                                        <thead>
                                        <tr>
{{--                                            <th scope="col">#</th>--}}
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
                                            <th>Elapsed</th>
                                            {{--<th style="white-space:nowrap;--}}
                                            {{--text-align:right;">Elapsed Time</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody style=".table-striped">
                                        @foreach($orders as $order)
                                            @if ($order->status === 'FILLED')
                                                <tr>
{{--                                                    <th scope="row">{{ $loop->index }}</th>--}}
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
                                                    <td nowrap>{{
                                                    \Carbon\Carbon::parse
                                                    ($order->enteredTime)
                                                    ->setTimezone('America/New_York')->format('Y-m-d g:i A') }}</td>
{{--                                                    <td>{{ \Carbon\Carbon::parse($order->closeTime)->format('g:i:s a') }}</td>--}}
                                                    <td nowrap>{{
                                                    \Carbon\Carbon::parse($order->closeTime)->setTimezone('America/New_York')->format('Y-m-d g:i A') }}</td>
{{--                                                    <td>{{ \Carbon\Carbon::parse($order->closeTime)->toDateTimeString() }}</td>--}}

                                                    <td>{{ gmdate('H:i:s', \Carbon\Carbon::parse($order->closeTime)->diffInSeconds($order->enteredTime)) }}</td>
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

    <script type="text/javascript">
        $('#fromdatetimepicker').datepicker({
            format: 'yyyy-mm-dd',
            // defaultViewDate: 'today',
            daysOfWeekDisabled: ['saturday', 'sunday'],
            orientation: 'auto bottom'
        });
        $('#todatetimepicker').datepicker({
            format: 'yyyy-mm-dd',
            // defaultViewDate: 'today',
            daysOfWeekDisabled: ['saturday', 'sunday'],
            orientation: 'auto bottom'
        });
        let table = new DataTable('#open-orders-table');
        let tableTwo = new DataTable('#filled-orders-table');
    </script>


</x-app-layout>
