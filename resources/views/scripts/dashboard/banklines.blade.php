
    <script type="text/javascript">


        $(document).ready(function(){

            InitPage();
        });


        $(document).on('change', '#selectyear', function (e) {
            let url='{{route('dashboard.banklines')}}';
            url += "/" + $(this).val();
            //alert(url);
            window.location.href = url;
        });




        function InitPage(customRest){


        }

    </script>


