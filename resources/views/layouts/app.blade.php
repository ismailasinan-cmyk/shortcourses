<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div id="app" class="min-vh-100 d-flex flex-column">
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                    <img src="{{ asset('images/acetel-logo.jpeg') }}" alt="Logo" width="40" height="40" class="me-2 rounded-circle shadow-sm" onerror="this.style.display='none'">

                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                        @auth
                            @if(Auth::user()->is_admin)
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.courses.index') }}">Courses</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.applications.index') }}">Applications</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.reports.index') }}">Reports</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.settings.index') }}">Settings</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}">My Dashboard</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-3">
                            <div class="lang-toggle">
                                <a class="nav-link {{ app()->getLocale() == 'en' ? 'fw-bold' : '' }}" href="{{ route('lang.switch', 'en') }}">EN</a>
                                <a class="nav-link {{ app()->getLocale() == 'fr' ? 'fw-bold' : '' }}" href="{{ route('lang.switch', 'fr') }}">FR</a>
                            </div>
                        </li>
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->surname }} {{ Auth::user()->firstname }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item rounded-3 mb-1" href="{{ route('password.change') }}">
                                        {{ __('Change Password') }}
                                    </a>
                                    <a class="dropdown-item rounded-3 text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-3 py-md-5 flex-grow-1">
            @yield('content')
        </main>

        <footer class="text-center py-5 mt-auto">
            <div class="container">
                <div class="divider-subtle"></div>
                <p class="mb-1 text-dark fw-bold">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
                <small class="text-muted d-block">{{ __('Africa Centre of Excellence on Technology Enhanced Learning') }}</small>
            </div>
        </footer>
    </div>
    @stack('scripts')
</body>
</html>
