<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    @if (count($orders) >= 1)
                    <div class="table-responsive">
                        <table class="table" id="orders-table">
                            <thead>
                            <tr>
                                <th>Symbol</th>
                                <th>Order ID</th>
                                <th>Instruction</th>
                                <th>Position Effect</th>
                                <th>Order Strategy Type</th>
                                <th>Duration</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Quantity</th>
                                <th>Session</th>
                                <th>Created At</th>
                                <th>Last At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{ $order->symbol }}</td>
                                    <td>{{ $order->orderId }}</td>
                                    <td>{{ $order->instruction }}</td>
                                    <td>{{ $order->positionEffect }}</td>
                                    <td>{{ $order->orderStrategyType }}</td>
                                    <td>{{ $order->duration }}</td>
                                    <td>{{ $order->price }}</td>
                                    <td>{{ $order->status }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->session }}</td>
                                    <td>{{ $order->created_at }}</td>
                                    <td>{{ $order->updated_at }}</td>

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
