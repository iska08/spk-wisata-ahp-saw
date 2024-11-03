<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="SPK Pemilihan Destinasi Wisata" />
    <meta name="author" content="##" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ url('frontend/images/logo-no-background.png') }}" />
    <title>SPK | {{ $title }}</title>
    {{-- style --}}
    @include('includes.admin.style')
</head>

<body class="sb-nav-fixed" style="font-family: 'Times New Roman', Times, serif">
    {{-- navbar --}}
    @include('includes.admin.navbar')
    <div id="layoutSidenav">
        {{-- sidenav --}}
        @include('includes.admin.sidenav')
        {{-- content --}}
        <div id="layoutSidenav_content">
            {{-- content --}}
            @yield('content')
            {{-- footer --}}
            @include('includes.admin.footer')
        </div>
    </div>
    @include('includes.admin.script')
</body>

</html>