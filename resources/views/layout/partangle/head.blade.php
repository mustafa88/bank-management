<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="description" content="Bootstrap Admin App">
<meta name="keywords" content="app, responsive, jquery, bootstrap, dashboard, admin">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/x-icon" href="{{ asset('angle/img/favicon.ico') }}">
<!-- =============== VENDOR STYLES ===============-->
<title>ادارة الحسابات البنكية</title>
<!-- FONT AWESOME-->
<link rel="stylesheet" href="{{ asset('angle/vendor/@fortawesome/fontawesome-free/css/brands.css') }}">
<link rel="stylesheet" href="{{ asset('angle/vendor/@fortawesome/fontawesome-free/css/regular.css') }}">
<link rel="stylesheet" href="{{ asset('angle/vendor/@fortawesome/fontawesome-free/css/solid.css') }}">
<link rel="stylesheet" href="{{ asset('angle/vendor/@fortawesome/fontawesome-free/css/fontawesome.css') }}">
<!-- SIMPLE LINE ICONS-->
<link rel="stylesheet" href="{{ asset('angle/vendor/simple-line-icons/css/simple-line-icons.css') }}">
<!-- ANIMATE.CSS-->
<link rel="stylesheet" href="{{ asset('angle/vendor/animate.css/animate.css') }}">
<!-- =============== PAGE VENDOR STYLES ===============-->
<!-- WHIRL (spinners)-->
<link rel="stylesheet" href="{{ asset('angle/vendor/whirl/dist/whirl.css') }}">
<!-- =============== BOOTSTRAP STYLES RTL ===============-->
<link rel="stylesheet" href="{{ asset('angle/css/bootstrap-rtl.css') }}" id="bscss">
<!-- =============== APP STYLES RTL ===============-->
<link rel="stylesheet" href="{{ asset('angle/css/app-rtl.css') }}" id="maincss">
<!-- ===============  SHARE FILE CSS - MUSTAFA ===============-->
<link rel="stylesheet" href="{{ asset('css/sharecss/libcss.css') }}" >

@if(isset($dataTables) and $dataTables=='v1')
    <!-- Datatables-->
    <link rel="stylesheet" href="{{ asset('angle/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('angle/vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('angle/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css') }}">

@endif
<!-- =============== PAGE HEAD ===============-->
@yield('page-head')
