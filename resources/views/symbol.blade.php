<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $symbol['0']['symbol'] }} Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="card">
                        <div class="card-body">

                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="summary-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#summary-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="summary-tab-pane"
                                            aria-selected="true">Summary</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="news-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#news-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="news-tab-pane"
                                            aria-selected="false">News</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="chart-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#chart-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="chart-tab-pane"
                                            aria-selected="false">Charts</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="earnings-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#earnings-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="earnings-tab-pane"
                                            aria-selected="false">Earnings</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="fundamentals-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#fundamentals-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="fundamentals-tab-pane"
                                            aria-selected="false">Fundamentals</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="valuation-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#valuation-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="valuation-tab-pane"
                                            aria-selected="false">Valuation</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="calendar-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#calendar-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="calendar-tab-pane"
                                            aria-selected="false">Calendar</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="analyst-reports-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#analyst-reports-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="analyst-reports-tab-pane"
                                            aria-selected="false">Analyst
                                        Reports</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="peer-comparison-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#peer-comparison-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="peer-comparison-tab-pane"
                                            aria-selected="false">Peer
                                        Comparison</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="sec-filings-tab"
                                            data-bs-toggle="tab"
                                            data-bs-target="#sec-filings-tab-pane"
                                            type="button" role="tab"
                                            aria-controls="sec-filings-tab-pane"
                                            aria-selected="false">Sec Filings</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">


                                    <div class="card">
                                        <div class="card-body">


                                            <div class="container text-center">
                                                <div class="row align-items-start">
                                                    <div class="col">

                                                        <ul class="list-group">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Open Price
                                                                <span class="">{{ $quote['0']['openPrice'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Close Price
                                                                <span class="">{{ $quote['0']['closePrice'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Day's Range
                                                                <span class="">{{ $quote['0']['lowPrice'] }} - {{ $quote['0']['highPrice'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                52-Wk Range
                                                                <span class="">{{ $quote['0']['52WkLow'] }} - {{ $quote['0']['52WkHigh'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Volatility
                                                                <span class="">{{ $quote['0']['volatility'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                EPS
                                                                <span class="">{{ $symbol['0']['EPS'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Latest Quarter
                                                                <span class="">{{ $symbol['0']['LatestQuarter'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Fiscal Year End
                                                                <span class="">{{ $symbol['0']['FiscalYearEnd'] }}</span>
                                                            </li>
                                                        </ul>

                                                    </div>
                                                    <div class="col">

                                                        <ul class="list-group">
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Market Cap
                                                                <span class="">{{ $symbol['0']['MarketCapitalization'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Shares Outstanding
                                                                <span class="">{{ $symbol['0']['SharesOutstanding'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Ex-dividend Date
                                                                <span class="">{{ $symbol['0']['ExDividendDate'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Dividend Pay Date
                                                                <span class="">{{ $symbol['0']['DividendDate'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Beta
                                                                <span class="">{{ $symbol['0']['Beta'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Exchange
                                                                <span class="">{{ $symbol['0']['Exchange'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Sector
                                                                <span class="">{{ $symbol['0']['Sector'] }}</span>
                                                            </li>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                Industry
                                                                <span
                                                                    class=""><small style="font-size: 55%">{{ $symbol['0']['Industry'] }}</small></span>
                                                            </li>
                                                        </ul>

                                                    </div>
                                                    <div class="col">
                                                        <div id="chart"></div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>


                                </div>
                                <div class="tab-pane fade" id="news-tab-pane"
                                     role="tabpanel" aria-labelledby="news-tab"
                                     tabindex="0">{{ $symbol['0']['symbol'] }}
                                    News</div>
                                <div class="tab-pane fade" id="chart-tab-pane"
                                     role="tabpanel" aria-labelledby="chart-tab"
                                     tabindex="0">Charts Here
                                    <div id="large-chart"></div>
                                </div>
                                <div class="tab-pane fade" id="earnings-tab-pane"
                                     role="tabpanel" aria-labelledby="earnings-tab"
                                     tabindex="0">Earnings Here</div>
                                <div class="tab-pane fade" id="fundamentals-tab-pane"
                                     role="tabpanel" aria-labelledby="fundamentals-tab"
                                     tabindex="0">Fundamentals Here</div>
                                <div class="tab-pane fade" id="valuation-tab-pane"
                                     role="tabpanel" aria-labelledby="valuation-tab"
                                     tabindex="0">Valuation Here</div>
                                <div class="tab-pane fade" id="calendar-tab-pane"
                                     role="tabpanel" aria-labelledby="calendar-tab"
                                     tabindex="0">Calendar Here</div>
                                <div class="tab-pane fade" id="analyst-reports-tab-pane"
                                     role="tabpanel" aria-labelledby="analyst-reports-tab"
                                     tabindex="0">Analyst Reports Here</div>
                                <div class="tab-pane fade" id="peer-comparison-tab-pane"
                                     role="tabpanel" aria-labelledby="peer-comparison-tab"
                                     tabindex="0">Peer Comparison Here</div>
                                <div class="tab-pane fade" id="sec-filings-tab-pane"
                                     role="tabpanel" aria-labelledby="sec-filings-tab"
                                     tabindex="0">Sec Filings Here</div>
                            </div>

                            <div class="card">
                                <div class="card-body">

                                    <div class="table-responsive">
                                        <table class="table table-striped caption-top"
                                               id="symbol-detail-table">
                                            <caption style="text-align: center;">Symbol Detail</caption>
                                            <thead>
                                            <tr>
                                                <th style="white-space: nowrap;">Symbol</th>
                                                <th style="">Description</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td style="white-space:
                                                    nowrap;">{{ $symbol['0']['symbol'] }}</td>
                                                <td style="">{{ $symbol['0']['Description']}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    // Create our number formatter.
    const formatter = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD',

        // These options are needed to round to whole numbers if that's what you want.
        //minimumFractionDigits: 0, // (this suffices for whole numbers, but will print 2500.10 as $2,500.1)
        //maximumFractionDigits: 0, // (causes 2500.99 to be printed as $2,501)
    });

    var options = {
        series: [{
            data: [
            @foreach($candles as $candle)
                {
                x: new Date({{ $candle['datetime'] }}),
                y: [{{ $candle['open'] }}, {{ $candle['high'] }}, {{
                $candle['low'] }}, {{ $candle['close'] }}]
            },
            @endforeach
            ]
        }],
        chart: {
            type: 'candlestick',
            height: 350,
            // zoom: {
            //     enabled: true,
            //     type: 'x',
            //     autoScaleYaxis: true
            // }
        },
        title: {
            text: 'CandleStick Chart',
            align: 'left'
        },
        xaxis: {
            type: 'datetime',
            labels: {
                datetimeFormatter: {
                    year: 'yyyy',
                    month: 'MMM \'yy',
                    day: 'dd MMM',
                    hour: 'hh:mm'
                }
            }
        },
        yaxis: {
            tooltip: {
                enabled: true
            },
            labels: {
                formatter: function (value) {
                    // return "$" + value;
                    return formatter.format(value);
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

    var chartTab = new ApexCharts(document.querySelector("#large-chart"),
        options);
    chartTab.render();
</script>

</x-app-layout>
