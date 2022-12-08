<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ getenv('APP_NAME') }} | @yield('title')</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Boststrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

    <!-- Boststrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">

    <!-- CSS Datatables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap4.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('/css/app.css') }}">

    <style>
        body {
            font-family: 'Nunito';
        }

    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-theme-blue">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('/images/coffee.png') }}" width="24" height="24" class="d-inline-block align-top" alt="">
                {{ getenv('APP_NAME') }}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">

                </ul>
                @if(auth()->user())
                    @if (auth()->user()->role == 'customer')
                        <a id="cart" type="button" class="{{ (request()->is('/')) ? '' : 'd-none' }} mr-3">
                            <button id="myBtn" type="button" class="btn">
                                <div class="navbar-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-cart3" viewBox="0 0 16 16">
                                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .49.598l-1 5a.5.5 0 0 1-.465.401l-9.397.472L4.415 11H13a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l.84 4.479 9.144-.459L13.89 4H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </svg>
                                    <span class="notify-custom" id="CheckOut">
                                        {{ Helper::totalDraft() }}
                                    </span>
                                </div>
                            </button>
                        </a>
                    @endif
                    @if (auth()->user()->role == 'admin')
                        <a class="nav-link text-white" href="{{ route('menus') }}">Menu</a>
                    @endif
                    <a class="nav-link text-white" href="{{ route('orders') }}" id="link-order">Order</a>
                    <a class="nav-link text-white" href="{{ route('logout') }}">Logout</a>
                @else
                    <a class="nav-link text-white" href="{{ route('login') }}">Login</a>
                @endif
            </div>
        </div>
    </nav>

    <section>
        <div class="container">
            <div class="w-100 p-3">
                @yield('content')
            </div>
        </div>
    </section>

    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap4.min.js"></script>
    @yield('scripts')
</body>
</html>
