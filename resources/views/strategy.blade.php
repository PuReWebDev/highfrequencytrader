<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Strategies') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <a href="/strategy/create">Create A New Trade
                        Strategy</a><br />
                    @if (count($strategies) >= 1)
                    <div class="table-responsive">
                        <table class="table table-striped caption-top"
                               id="strategies-table">
                            <caption style="text-align: center;">Your Strategies
                            </caption>
                            <thead>
                            <tr>
                                <th scope="col" style="white-space: nowrap;">
                                    <strong>#</strong></th>
                                <th style="white-space: nowrap;">Name</th>
                                <th style="white-space: nowrap;">Edit</th>
                                <th style="white-space: nowrap;">Delete</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($strategies as $strategy)
                                <tr>
                                    <td scope="row"
                                        style="white-space: nowrap;">{{$loop->index }}</td>
{{--                                    <td style="white-space:--}}
{{--                                                    nowrap;"><a href="{{url--}}
{{--                                                    ('symbol',[$mover->symbol])}}">{{ $mover->symbol--}}
{{--                                                    }}</a></td>--}}
                                    <td style="white-space: nowrap;">{{$strategy['strategy_name']}}</td>
                                    <td style="white-space: nowrap;"><a
                                            href="#">Edit</a> </td>
                                    <td style="white-space: nowrap;"><a
                                            href="#">Delete</a></td>
{{--                                    <td style="white-space: nowrap;">+${{--}}
{{--                                    $mover->change }}</td>--}}
{{--                                    <td style="white-space: nowrap;">{{ $mover->totalVolume }}</td>--}}

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
