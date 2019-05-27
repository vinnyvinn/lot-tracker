<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/jquery-ui.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/toastr.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top" id="themeColor">
            <div class="container">
                <div class="navbar-header">
                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- Branding Image -->
                    <a class="navbar-brand logo" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}

                    </a>
                    @auth
                    <span>
                        <a href="{{url('pos')}}" class="btn btn-success" style="margin-left: 80px"><img src="{{asset('assets/img/purchase.png')}}" alt="" width="50px"><span class="menu-i" style="font-weight: 600"> Receive</span> </a>
                          <a href="{{url('so')}}" class="btn btn-success"><img src="{{asset('assets/img/po.png')}}" alt="" width="50px"><span class="menu-i" style="font-weight: 600"> Issue</span> </a>
                           <a href="{{url('reports/create')}}" class="btn btn-success"><img src="{{asset('assets/img/report.png')}}" alt="" width="50px"><span class="menu-i" style="font-weight: 600"> Reports</span> </a>
                   <a href="{{url('settings/create')}}" class="btn btn-success"><img src="{{asset('assets/img/settings.png')}}" alt="" width="50px"><span class="menu-i" style="font-weight: 600"></span> </a>
                    </span>
                        @endauth
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->

                    <ul class="nav navbar-nav">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            {{--<li><a href="{{ route('login') }}">Login</a></li>--}}
                            {{--<li><a href="{{ route('register') }}">Register</a></li>--}}
                        @else

                            <li>
                                {{--<img src="{{asset('assets/img/exit.png')}}" alt="" width="50px">--}}

                                <a href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="btn btn-info">
                                    <img src="{{asset('assets/img/logout.png')}}" alt="" width="30px">

                                </a>



                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>


                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
<div class="container">
    @yield('content')
</div>

    </div>

    <!-- Scripts -->



     <script src="{{asset('assets/js/jquery-3.3.1.js')}}"></script>
    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    <script src="{{asset('assets/js/tether.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/toastr.min.js')}}"></script>
    <script src="{{ asset('js/app.js') }}"></script>


    <script>

        @if(Session::has('success'))
        toastr.success("{{Session::get('success')}}")
        @endif
        @if(Session::has('fail'))
        toastr.fail("{{Session::get('fail')}}")
        @endif
    </script>
    {{--<script src="{{ asset('js/app.js') }}"></script>--}}

    @yield('scripts')
</body>
</html>
