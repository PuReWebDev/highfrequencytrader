<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <!-- Scripts -->
{{--        @vite(['resources/css/app.css', 'resources/js/app.js'])--}}
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <div class="container">
            <footer class="py-5">
                <div class="row">
                    <div class="col-6 col-md-2 mb-3">
                        <h5>Section</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2"><a href="/"
                                                         class="nav-link p-0 text-body-secondary">Home</a></li>
                            <li class="nav-item mb-2"><a href="/dashboard"
                                                         class="nav-link p-0
                                                         text-body-secondary">Dashboard</a></li>
                            <li class="nav-item mb-2"><a href="/orders"
                                                         class="nav-link p-0
                                                         text-body-secondary">Orders</a></li>
                            <li class="nav-item mb-2"><a href="/balances"
                                                         class="nav-link p-0
                                                         text-body-secondary">Balances</a></li>
                            <li class="nav-item mb-2"><a href="/preference"
                                                         class="nav-link p-0
                                                         text-body-secondary">Preferences</a></li>
                            <li class="nav-item mb-2"><a href="/account"
                                                         class="nav-link p-0
                                                         text-body-secondary">Account</a></li>
                        </ul>
                    </div>

                    <div class="col-6 col-md-2 mb-3">
                        <h5>Section</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2"><a href="/"
                                                         class="nav-link p-0 text-body-secondary">Home</a></li>
                            <li class="nav-item mb-2"><a href="/dashboard"
                                                         class="nav-link p-0
                                                         text-body-secondary">Dashboard</a></li>
                            <li class="nav-item mb-2"><a href="/orders"
                                                         class="nav-link p-0
                                                         text-body-secondary">Orders</a></li>
                            <li class="nav-item mb-2"><a href="/balances"
                                                         class="nav-link p-0
                                                         text-body-secondary">Balances</a></li>
                            <li class="nav-item mb-2"><a href="/preference"
                                                         class="nav-link p-0
                                                         text-body-secondary">Preferences</a></li>
                            <li class="nav-item mb-2"><a href="/account"
                                                         class="nav-link p-0
                                                         text-body-secondary">Account</a></li>
                        </ul>
                    </div>

                    <div class="col-6 col-md-2 mb-3">
                        <h5>Section</h5>
                        <ul class="nav flex-column">
                            <li class="nav-item mb-2"><a href="/"
                                                         class="nav-link p-0 text-body-secondary">Home</a></li>
                            <li class="nav-item mb-2"><a href="/dashboard"
                                                         class="nav-link p-0
                                                         text-body-secondary">Dashboard</a></li>
                            <li class="nav-item mb-2"><a href="/orders"
                                                         class="nav-link p-0
                                                         text-body-secondary">Orders</a></li>
                            <li class="nav-item mb-2"><a href="/balances"
                                                         class="nav-link p-0
                                                         text-body-secondary">Balances</a></li>
                            <li class="nav-item mb-2"><a href="/preference"
                                                         class="nav-link p-0
                                                         text-body-secondary">Preferences</a></li>
                            <li class="nav-item mb-2"><a href="/account"
                                                         class="nav-link p-0
                                                         text-body-secondary">Account</a></li>
                        </ul>
                    </div>

                    <div class="col-md-5 offset-md-1 mb-3">
                        <form>
                            <h5>Subscribe to our newsletter</h5>
                            <p>Monthly digest of what's new and exciting from us.</p>
                            <div class="d-flex flex-column flex-sm-row w-100 gap-2">
                                <label for="newsletter1" class="visually-hidden">Email address</label>
                                <input id="newsletter1" type="text" class="form-control" placeholder="Email address">
                                <button class="btn btn-primary" type="button">Subscribe</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
                    <p>&copy; 2023 High Frequency Trading Services All rights
                        reserved.</p>
                    <ul class="list-unstyled d-flex">
                        <li class="ms-3"><a class="link-body-emphasis"
                                            href="#">Social Media LInk</a></li>
                        <li class="ms-3"><a class="link-body-emphasis" href="#">Social Media LInk</a></li>
                        <li class="ms-3"><a class="link-body-emphasis" href="#">Social Media LInk</a></li>
                    </ul>
                </div>
            </footer>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    </body>
</html>
