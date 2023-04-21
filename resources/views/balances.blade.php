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

                                <div class="table-responsive">
                                    <table class="table" id="open-orders-table">
                                        <thead>
                                        <tr>
                                            <th>Account ID</th>
                                            <th>Balance Type</th>
                                            <th>Available Funds</th>
                                            <th>Available Funds
                                                Non-Marginable Trade</th>
                                            <th>Buying Power</th>
                                            <th>Buying
                                                Power Non-Marginable Trade</th>
                                            <th>Cash Balance</th>
                                            <th>Cash Available For Trading</th>
                                            <th>Cash Receipts</th>
                                            <th>Day Trading Buying Power</th>
                                            <th>Day Trading Buying
                                                Power Call</th>
                                            <th>Day Trading Equity Call</th>
                                            <th>Equity</th>
                                            <th>Liquidation Value</th>
                                            <th>Long Margin Value</th>
                                            <th>longOptionMarketValue</th>
                                            <th>longStockValue</th>
                                            <th>maintenanceCall</th>
                                            <th>maintenanceRequirement</th>
                                            <th>margin</th>
                                            <th>marginEquity</th>
                                            <th>regTCall</th>
                                            <th>totalCash</th>
                                            <th>isInCall</th>
                                            <th>pendingDeposits</th>
                                            <th>marginBalance</th>
                                            <th>accountValue</th>
                                            <th>stockBuyingPower</th>

                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($balances as $balance)
                                            @if ($balance->balanceType === 'initialBalances')
                                                <tr>
                                                    <td>{{ $balance->accountId }}</td>
                                                    <td>{{ $balance->balanceType }}</td>
                                                    <td>{{ $balance->availableFunds }}</td>
                                                    <td>{{ $balance->availableFundsNonMarginableTrade }}</td>
                                                    <td>{{ $balance->buyingPower }}</td>
                                                    <td>{{ $balance->buyingPowerNonMarginableTrade }}</td>
                                                    <td>{{ $balance->cashBalance }}</td>
                                                    <td>{{ $balance->cashAvailableForTrading }}</td>
                                                    <td>{{ $balance->cashReceipts }}</td>
                                                    <td>{{ $balance->dayTradingBuyingPower }}</td>
                                                    <td>{{ $balance->dayTradingBuyingPowerCall }}</td>
                                                    <td>{{ $balance->dayTradingEquityCall }}</td>
                                                    <td>{{ $balance->equity }}</td>
                                                    <td>{{ $balance->liquidationValue }}</td>
                                                    <td>{{ $balance->longMarginValue }}</td>
                                                    <td>{{ $balance->longOptionMarketValue }}</td>
                                                    <td>{{ $balance->longStockValue }}</td>
                                                    <td>{{ $balance->maintenanceCall }}</td>
                                                    <td>{{ $balance->maintenanceRequirement }}</td>
                                                    <td>{{ $balance->margin }}</td>
                                                    <td>{{ $balance->marginEquity }}</td>
                                                    <td>{{ $balance->regTCall }}</td>
                                                    <td>{{ $balance->totalCash }}</td>
                                                    <td>{{ $balance->isInCall }}</td>
                                                    <td>{{ $balance->pendingDeposits }}</td>
                                                    <td>{{ $balance->marginBalance }}</td>
                                                    <td>{{ $balance->accountValue }}</td>
                                                    <td>{{ $balance->stockBuyingPower }}</td>

                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>


                </div>

            </div>
        </div>
    </div>



</x-app-layout>
