<!-- MODERNIZR-->
<script src="{{ asset('angle/vendor/modernizr/modernizr.custom.js') }}"></script>
<!-- STORAGE API-->
<script src="{{ asset('angle/vendor/js-storage/js.storage.js') }}"></script>
<!-- SCREENFULL-->
<script src="{{ asset('angle/vendor/screenfull/dist/screenfull.js') }}"></script>
<!-- i18next-->
<script src="{{ asset('angle/vendor/i18next/i18next.js') }}></script>
<script src="{{ asset('angle/vendor/i18next-xhr-backend/i18nextXHRBackend.js') }}"></script>
<script src="{{ asset('angle/vendor/jquery/dist/jquery.js') }}"></script>
<script src="{{ asset('angle/vendor/popper.js/dist/umd/popper.js') }}"></script>
<!-- =============== PAGE VENDOR SCRIPTS ===============-->
<script src="{{ asset('angle/vendor/bootstrap/dist/js/bootstrap.js') }}"></script>

<!-- PARSLEY VALIDATION INPUT-->
<script src="{{ asset('angle/vendor/parsleyjs/dist/parsley.min.js') }}"></script>
<script src="{{ asset('angle/vendor/parsleyjs/dist/i18n/ar.js') }}"></script>

<!-- notify -->
<script type="text/javascript" src="{{ asset('js/notifyjs/dist/notify.min.js') }}"></script>

<!-- =============== APP SCRIPTS ===============-->
<script src="{{ asset('angle/js/app.js') }}"></script>

<!-- =============== SCRIPTS SHARE ===============-->
<script src="{{ asset('js/sharejs/libjs.js') }}"></script>
<!-- =============== PAGE SCRIPTS ===============-->

@if(isset($dataTables) and $dataTables=='v1')
    <!-- Datatables-->
    <script src="{{ asset('angle/vendor/datatables.net/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/dataTables.buttons.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.colVis.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.flash.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.html5.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.print.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-keytable/js/dataTables.keyTable.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('angle/vendor/jszip/dist/jszip.js') }}"></script>
    <script src="{{ asset('angle/vendor/pdfmake/build/pdfmake.js') }}"></script>
    <script src="{{ asset('angle/vendor/pdfmake/build/vfs_fonts.js') }}"></script>
@endif
@yield('page-script')
{{--
@if(isset($fileJs))
    @include( $fileJs )
@endif
--}}




