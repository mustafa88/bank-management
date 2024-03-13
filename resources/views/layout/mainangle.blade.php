<!DOCTYPE html>
<html lang="en">
<head>
    @include('layout.partangle.head')
    {{--
     page-head
     --}}
</head>
<body>
<div class="wrapper">
    <!-- top navbar-->
    @include('layout.partangle.topnavbar.topnavbar-wrapper')
    <!-- sidebar-->
    @include('layout.partangle.sidebar.aside-container')
    <!-- offsidebar-->
    @include('layout.partangle.offsidebar.offsidebar')
    <!-- Main section-->
    @include('layout.partangle.mainsection.container')
    {{--
     page-content
     --}}

    <!-- Page footer-->
    @include('layout.partangle.footer')
</div>
@include('layout.partangle.footer-scripts')
    {{--
    LOAD AUTOMATIC FILE JS SOME NAME FILE PROGRAM
    page-script
    --}}

</body>
</html>
