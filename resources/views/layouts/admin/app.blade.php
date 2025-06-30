<!doctype html>
<!-- 
* Bootstrap Simple Admin Template
* Version: 2.1
* Author: Alexis Luna
* Website: https://github.com/alexis-luna/bootstrap-simple-admin-template
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title', env('APP_NAME', 'Trading Panel - Polaris Option'))</title>
    <link href="{{ asset('base') }}/assets/vendor/fontawesome/css/fontawesome.min.css" rel="stylesheet">
    <link href="{{ asset('base') }}/assets/vendor/fontawesome/css/solid.min.css" rel="stylesheet">
    <link href="{{ asset('base') }}/assets/vendor/fontawesome/css/brands.min.css" rel="stylesheet">
    <link href="{{ asset('base') }}/assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('base') }}/assets/css/master.css" rel="stylesheet">
    <link href="{{ asset('base') }}/assets/vendor/flagiconcss/css/flag-icon.min.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        @include('layouts.admin.sidebar')
        <div id="body" class="">
            <!-- navbar navigation component -->
            @include('layouts.admin.navbar')
            <!-- end of navbar navigation -->
            <div class="content">
                <div class="container">
                    {{-- Validation Errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>There were some problems with your input:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Flash Error Message --}}
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Flash Success Message --}}
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                </div>
                @yield('content')
            </div>
        </div>
    </div>
    <script src="{{ asset('base') }}/assets/vendor/jquery/jquery.min.js"></script>
    <script src="{{ asset('base') }}/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('base') }}/assets/vendor/chartsjs/Chart.min.js"></script>
    <script src="{{ asset('base') }}/assets/js/dashboard-charts.js"></script>
    <script src="{{ asset('base') }}/assets/js/script.js"></script>
</body>

</html>