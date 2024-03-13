
    <script type="text/javascript">




        $(document).ready(function(){

            $('#showData').on( 'click', function () {
                let fdate= $("#fromDate").val();
                let tdate= $("#toDate").val();
                let enterprise= $("#enterprise").val();

                if(fdate=="" || tdate=="" || enterprise=='-1'){
                    notify("תאריך לא תקין" ,"error");
                    return false;
                }

                let url='{{route('usb_report.show' )}}';
                url = url +"/" + enterprise;
                url += "?fromDate=" + fdate + "&toDate=" + tdate;

                //alert(url);
                window.location.href = url;

            });

        });


    </script>


