<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('template') }}/assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Auto Reply Email DWA | {{ $title ?? 'No Title' }}</title>

    {{-- SEO Basic --}}
    <meta name="description" content="" />
    {{-- / SEO Basic --}}

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Favicon --}}
    <link rel="icon" type="image/x-icon" href="{{ asset('template') }}/assets/img/favicon/black-logo.png" />
    {{-- / Favicon --}}

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    {{-- / Fonts --}}

    {{-- Core CSS --}}
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/css/core.css"
        class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/css/theme-default.css"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('template') }}/assets/css/demo.css" />
    <link rel="stylesheet" href="{{ asset('template') }}/assets/css/custom.css" />
    {{-- / Core CSS --}}

    {{-- Vendors CSS --}}
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="{{ asset('template') }}/assets/vendor/izitoast/dist/css/iziToast.min.css">

    <!-- Main Quill library -->
    <script src="https://cdn.jsdelivr.net/npm/quill@2/dist/quill.js"></script>

    <!-- Theme included stylesheets -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />

    <!-- Datepicker CSS (Bootstrap 5) -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <!-- Timepicker CSS (Bootstrap 5) -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">




    {{-- Helpers --}}
    <script src="{{ asset('template') }}/assets/vendor/js/helpers.js"></script>
    {{-- / Helpers --}}

    {{-- Penyesuai template & file konfigurasi Tema HARUS disertakan setelah stylesheet inti dan helpers.js di bagian <head>  --}}
    {{-- Konfigurasi: File konfigurasi tema wajib berisi vars global & opsi tema default, Tetapkan opsi tema pilihan Anda di file ini. --}}
    <script src="{{ asset('template') }}/assets/js/config.js"></script>
    {{-- / Penyesuai template & file konfigurasi Tema HARUS disertakan setelah stylesheet inti dan helpers.js di bagian <head>  --}}
    {{-- / Konfigurasi: File konfigurasi tema wajib berisi vars global & opsi tema default, Tetapkan opsi tema pilihan Anda di file ini. --}}

    {{-- Page CSS --}}
    @stack('css-konten')
    {{-- Page CSS --}}

    <style>
        #editor-container {
            height: 300px;
        }
    </style>

</head>

<body>

    @if (request()->routeIs('auth*'))
        <div class="container-xxl">
            @yield('konten')
        </div>
    @else
        {{-- Layout wrapper --}}
        <div class="layout-wrapper layout-content-navbar">
            {{-- Layout  --}}
            <div class="layout-container">

                {{-- Side Bar --}}
                <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                    @include('layouts.sidebar')
                </aside>
                {{-- / Side Bar --}}

                {{-- Layout container --}}
                <div class="layout-page">

                    {{-- Navbar --}}
                    <nav class="layout-navbar nav-sticky-top navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                        id="layout-navbar">
                        @include('layouts.navbar')
                    </nav>
                    {{-- Navbar --}}

                    {{-- Content wrapper --}}
                    <div class="content-wrapper">

                        {{-- Content --}}
                        <div class="container-xxl flex-grow-1 container-p-y">
                            @yield('konten')
                        </div>
                        {{-- Content --}}

                        {{-- Footer --}}
                        <footer class="content-footer footer bg-footer-theme mt-3">
                            @include('layouts.footer')
                        </footer>
                        {{-- Footer --}}

                        <div class="content-backdrop fade"></div>

                    </div>
                    {{-- / Content wrapper --}}
                </div>
                {{-- / Layout page --}}
            </div>
            {{-- / Layout  --}}

            {{-- Overlay --}}
            <div class="layout-overlay layout-menu-toggle"></div>
            {{-- / Overlay --}}

        </div>
        {{-- / Layout wrapper --}}
    @endif


    {{-- Core JS --}}
    {{-- build:js assets/vendor/js/core.js --}}
    <script src="{{ asset('template') }}/assets/vendor/libs/jquery/jquery.js"></script>
    <script src="{{ asset('template') }}/assets/vendor/libs/popper/popper.js"></script>
    <script src="{{ asset('template') }}/assets/vendor/js/bootstrap.js"></script>
    <script src="{{ asset('template') }}/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="{{ asset('template') }}/assets/vendor/js/menu.js"></script>
    {{-- endbuild --}}
    {{-- / Core JS --}}


    {{-- Vendors JS --}}
    <script src="{{ asset('template') }}/assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="{{ asset('template') }}/assets/vendor/izitoast/dist/js/iziToast.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>


    <!-- Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <!-- Timepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>


    {{-- / Vendors JS --}}

    {{-- Main JS --}}
    <script src="{{ asset('template') }}/assets/js/main.js"></script>
    {{-- / Main JS --}}

    {{-- Toast Response Session --}}
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                iziToast.error({
                    title: 'Error',
                    message: '{{ $error }}',
                    position: 'topRight',
                });
            </script>
        @endforeach
    @endif

    @if (Session::has('error'))
        <script>
            iziToast.error({
                title: 'Error',
                message: '{{ Session::get('error') }}',
                position: 'topRight',
            });
        </script>
    @endif

    @if (Session::has('info'))
        <script>
            iziToast.info({
                title: 'Info',
                message: '{{ Session::get('info') }}',
                position: 'topRight',
            });
        </script>
    @endif
    @if (Session::has('warning'))
        <script>
            iziToast.warning({
                title: 'Warning',
                message: '{{ Session::get('warning') }}',
                position: 'topRight',
            });
        </script>
    @endif

    @if (Session::has('success'))
        <script>
            iziToast.success({
                title: 'Success',
                message: '{{ Session::get('success') }}',
                position: 'topRight',
            });
        </script>
    @endif
    {{-- / Toast Response Session --}}

    {{-- Page JS --}}
    @stack('js-konten')
    {{-- / Page JS --}}
</body>

</html>
