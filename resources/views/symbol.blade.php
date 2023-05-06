<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Symbol') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

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
