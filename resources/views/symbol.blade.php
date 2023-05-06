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
                            <button class="nav-link" id="disabled-tab" data-bs-toggle="tab" data-bs-target="#disabled-tab-pane" type="button" role="tab" aria-controls="disabled-tab-pane" aria-selected="false" disabled>Disabled</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="summary-tab-pane" role="tabpanel" aria-labelledby="summary-tab" tabindex="0">{{ $symbol['0']['Description']}}</div>
                        <div class="tab-pane fade" id="news-tab-pane"
                             role="tabpanel" aria-labelledby="news-tab"
                             tabindex="0">{{ $symbol['0']['symbol'] }}
                            News</div>
                        <div class="tab-pane fade" id="chart-tab-pane" role="tabpanel" aria-labelledby="chart-tab" tabindex="0">...</div>
                        <div class="tab-pane fade" id="disabled-tab-pane" role="tabpanel" aria-labelledby="disabled-tab" tabindex="0">...</div>
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



</x-app-layout>
