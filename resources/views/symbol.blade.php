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
                                                        A third list item
                                                        <span class="">More
                                                    Content</span>
                                                    </li>
                                                </ul>

                                            </div>
                                            <div class="col">

                                                <ul class="list-group">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        A list item
                                                        <span class="">More
                                                    Content</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        A second list item
                                                        <span class="">More
                                                    Content</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        A third list item
                                                        <span class="">More
                                                    Content</span>
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
                             tabindex="0">Charts Here</div>
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

<script type="text/javascript">
    var options = {
        series: [{
            data: [
            @@foreach($candles as $candle)
                {
                x: new Date({{ $candle['datetime'] }}),
                y: [{{ $candle['open'] }}, {{ $candle['high'] }}, {{
                $candle['low'] }}, {{ $candle['close'] }}]
            },
            @@endforeach
            ]
        }],
        chart: {
            type: 'candlestick',
            height: 350
        },
        title: {
            text: 'CandleStick Chart',
            align: 'left'
        },
        xaxis: {
            type: 'datetime'
        },
        yaxis: {
            tooltip: {
                enabled: true
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>

</x-app-layout>
