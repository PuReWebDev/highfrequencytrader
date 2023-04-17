<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Preference') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    You're logged in! <br /> {{ $user['0']->name }}

                    <div class="content px-3">

{{--                        @include('flash::message')--}}

                        <div class="clearfix"></div>

                        <div class="card">
                            <div class="card-body p-0">


                                <div class="table-responsive">
                                    <table class="table" id="urls-table">
                                        <thead>
                                        <tr>
                                            <th>Trading Enabled</th>
                                            <th>Trade Pre-Market</th>
                                            <th>Trade Post-Market</th>
                                            <th>Accept TOS</th>
                                            <th>Trade Quantity</th>
                                            <th colspan="3">Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>{{
                                                $preference['0']['tradingEnabled'] }}</td>
                                                <td>{{ $preference['0']['tradingEnabled']}}</td>
                                                <td>{{
                                                $preference['0']['tradePreMarket'] }}</td>
                                                <td>{{
                                                $preference['0']['tradePostMarket'] }}</td>
                                                <td>{{
                                                $preference['0']['acceptTos']
                                                }}</td>
                                                <td>{{
                                                $preference['0']['tradeQuantity']
                                                }}</td>
                                                <td width="120">
                                                    {!! Form::open() !!}
                                                    <div class='btn-group'>
                                                        <a href="{{ route
                                                        ('preferences
                                                        .edit', [$user['0']->id])
                                                         }}"
                                                           class='btn btn-default btn-xs'>
                                                            <i class="far fa-edit"></i>
                                                        </a>
                                                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                                                    </div>
                                                    {!! Form::close() !!}
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>


                                <div class="card-footer clearfix">
                                    <div class="float-right">

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
