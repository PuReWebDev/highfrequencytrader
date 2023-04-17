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
{{--                                @include('urls.table')--}}

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
