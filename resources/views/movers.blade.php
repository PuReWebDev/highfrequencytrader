<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Movers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="table-responsive">
                        <table class="table table-striped caption-top"
                               id="open-orders-table">
                            <caption style="text-align: center;">Today's
                                Movers
                            </caption>
                            <thead>
                            <tr>
                                <th scope="col" style="white-space: nowrap;">
                                    <strong>#</strong></th>
                                <th style="white-space: nowrap;">Symbol</th>
                                <th style="white-space: nowrap;">Description
                                </th>
                                <th style="white-space: nowrap;">Last Price</th>
                                <th style="white-space: nowrap;">Change</th>
                                <th style="white-space: nowrap;">Total Volume
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($movers as $mover)
                                <tr>
                                    <td scope="row"
                                        style="white-space: nowrap;">{{
                                                    $loop->index }}</td>
                                    <td style="white-space:
                                                    nowrap;"><a href="{{url
                                                    ('symbol',[$mover->symbol])}}">{{ $mover->symbol
                                                    }}</a></td>
                                    <td style="white-space: nowrap;">{{ $mover->description}}</td>
                                    <td style="white-space: nowrap;">{{ $mover->last }}</td>
                                    <td style="white-space: nowrap;">+${{
                                    $mover->change }}</td>
                                    <td style="white-space: nowrap;">{{ $mover->totalVolume }}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>



</x-app-layout>
