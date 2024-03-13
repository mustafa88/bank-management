<section class="section-container">
    <!-- Page content-->
    <div class="content-wrapper">

        @if(isset($pageTitle))
        <div class="content-heading">
            <div>{{$pageTitle}}
                @if(isset($subTitle))
                    <small>{{$subTitle}}</small>
                @endif
            </div>
        </div>
        @endif

            <div class="container-fluid">
                @yield('page-content','صفحه فارغه - لا تحتوي على content')
            </div>

    </div>
</section>

