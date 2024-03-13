
    <script type="text/javascript">


        let myTable,myRowTable=null;


        $(document).ready(function(){
            myTable = $('#datatable1').DataTable({
                'paging': true, // Table pagination
                'ordering': true, // Column ordering
                "order": [[ 0, 'asc']],
                'info': true, // Bottom left status text
                //fixedHeader: true,
                responsive: true,
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                iDisplayLength: -1
            });
        });

        $(document).on('change', '.priceitem', function (e) {

            dataObj = {};
            dataObj['price']= $(this).val();
            dontetypeid = $(this).data('dontetypeid');
            let url= '{{route('donateType.updatepriceajax')}}';
            url += "/" + dontetypeid;

            let resultAjax = SendToAjax(url,'PUT' ,'-1',dataObj);
            //console.log(resultAjax);
            if(resultAjax==undefined){
                notify('حدث خطأ','error');
                return false;
            }
            notify(resultAjax.msg ,resultAjax.cls);
            if(resultAjax.status===false){
                return;
            }

        });


        function InitPage(){
            myRowTable=null;
            return;
            $("#id_line").val('0');

            $("#datedont").val('');
            $("#enterp").val('0');
            $("#id_city").val('0');
            $("#id_typedont").val('0');
            $("#price").val('');
            $("#quantity").val('');
            $("#amount").val('');
            $("#description").val('');
            $("#namedont").val('');

        }

        /**
         * חישוב סכום שורה
         */
        function culcAmountLine(){
            let price= $("#price").val();
            let quantity= $("#quantity").val();
            let amount = price * quantity;
            $("#amount").val(amount);
        }

    </script>


