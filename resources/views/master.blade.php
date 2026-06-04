<!DOCTYPE html>
<html lang="{{ App\Models\Setting::get('site_language', 'en') }}">
    <head>
        @include('settings.inc._meta_head')
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> @yield('title') </title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"/>
        @if (env('APP_ENV') == 'local')
            <link rel="stylesheet" href="{{asset('/css/adminlte.css')}}" />
        @else
            <link rel="stylesheet" href="{{asset('/public/css/adminlte.css')}}" />
        @endif
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
        <style>
            input[type="checkbox"] {
                appearance: none;
                -webkit-appearance: none;
                width: 18px;
                height: 18px;
                border: 2px solid #0d6efd;
                border-radius: 4px;
                background-color: #fff;
                cursor: pointer;
                position: relative;
            }
        </style>
        @stack('css')
    </head>
 <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
            <div class="app-wrapper">

                @include('includes.nav')

                @include('includes.sidebar')
                

                <main class="app-main">

                    <div class="app-content-header">
                        <div class="container-fluid">
                       
                            <div class="row text-center">
                                <div class="col-12">
                                    <h3 class="mb-0 text-secondary">@yield('header')</h3>
                                </div>
                            </div>
                            <!--end::Row-->
                        </div>
                    </div>

                    <div class="app-content">
                        <div class="container-fluid">

                            @yield('body')
  
                        </div>
                    </div>
                </main>

                
                @include('includes.footer')
                
            </div>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
        @if (env('APP_ENV') == 'local')
            <script src="{{asset('/js/adminlte.js')}}"></script>
        @else
            <script src="{{asset('/public/js/adminlte.js')}}"></script>
        @endif
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


        @if(session('success'))
        <script>
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "preventDuplicates": true,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
                }
            toastr["success"]("{{session('success')}}", "{{env('APP_NAME')}}")
        </script>
        @endif

        @stack('js')
        

    </body>
</html>
