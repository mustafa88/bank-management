
    <script type="text/javascript">


        $(document).ready(function(){

            InitPage();
        });


        $(document).on('change', '#selectyear', function (e) {

            let url  = window.location.pathname + "?year=" + $(this).val();
            //alert(url);
            //return;
            window.location.href = url;
        });




        function InitPage(customRest){


        }

    </script>


