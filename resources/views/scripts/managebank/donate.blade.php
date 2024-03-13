
    <script type="text/javascript">


        let myTable,myRowTable=null;


        $(document).ready(function(){
            myTable = $('#datatable1').DataTable({
                'paging': true, // Table pagination
                'ordering': true, // Column ordering
                "order": [[ 0, 'asc'],[ 1, 'asc'],[ 2, 'asc']],
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


        $('#id_typedont').on( 'change', function () {
            //שינוי סוג תרומה - משנה מחיר יחידה
            let price =  $('#id_typedont').find(":selected").data('price');
            $("#price").val(price);
            culcAmountLine();
        });

        $('#price').on( 'change', function () {
            //שינוי מחיר יחידה
            culcAmountLine();
        });

        $('#quantity').on( 'change', function () {
            //שינוי כמות
            culcAmountLine();
        });

        $('#amount').on( 'change', function () {
            if($('#quantity').val()==0){
                return;
            }
             let price = $('#amount').val()/$('#quantity').val();
            price = parseFloat(price).toFixed(2);
            $("#price").val(price);
        });
        /**
         * שמירה
         * save new data or update data exists
         */
        $('#btn_save').on( 'click', function () {

            let id_line = $("#id_line").val();

            if((id_line=='0' && myRowTable!=null) || (id_line!='0' && myRowTable==null)){
                notify('תקלה - נא לדווח לאיש מחשוב');
                return;
            }

            //alert(id_line);
            if(id_line=='0'){
                //insert
                let url= '{{route('mainDonate.storeajax',$param_url)}}';
                //console.log(url);

                //return url;
                let resultAjax = SendToAjax(url,'POST');
                //console.log(resultAjax);
                if(resultAjax==undefined){
                    notify('حدث خطأ','error');
                    return false;
                }
                notify(resultAjax.msg ,resultAjax.cls);
                if(resultAjax.status===false){
                    return;
                }
                myTable.row.add($(resultAjax['rowHtml'])[0]).draw();
            }else{
                //return;
                //update
                //notify('update');
                let url= '{{route('mainDonate.updateajax',$param_url)}}';

                url +="/"+id_line;
                let resultAjax = SendToAjax(url,'PUT');
                //console.log(resultAjax);
                //return;
                if(resultAjax==undefined){
                    notify('حدث خطأ','error');
                    return false;
                }
                notify(resultAjax.msg ,resultAjax.cls);
                if(resultAjax.status===false){
                    return;
                }

                let numberRow = myTable.row(myRowTable)[0][0];
                let row = myTable.row(numberRow);
                let newData = resultAjax.rowHtmlArr;
                for (let i = 0; i < newData.length; i++) {
                    myTable.cell(row, i).data(newData[i]);
                }
                myTable.draw();
                //let thisRow = row.node();
                //$(thisRow).find('td').eq(1).css("background-color",newData['color_code']);


            }

            InitPage();
        });

        $(document).on('click', 'a.edit_row', function (e) {
            e.preventDefault();
            InitPage();
            let idline = $(this).data('idline');

            var nRow = $(this).parents('tr')[0];
            var aData = myTable.row(nRow).data();

            let url='{{route('mainDonate.editajax',$param_url)}}';
            url +="/"+idline;
            //alert(url);

            let resultAjax = SendToAjax(url,'GET');
            //console.log(resultAjax);

            if(resultAjax.status===false){
                notify(resultAjax.msg ,resultAjax.cls);
                return;
            }
            let row = resultAjax.row;
            $("#id_line").val('0');


            $("#datedont").val(row.datedont);
            $("#id_typedont").val(row.id_typedont);
            $("#price").val(row.price);
            $("#quantity").val(row.quantity);
            $("#amount").val(row.amount);
            $("#description").val(row.description);
            $("#namedont").val(row.namedont);

            myRowTable=nRow;
            $("#id_line").val(idline);

            $("#addline").collapse('show');
            $('html, body').animate({
                scrollTop: $("#addline").offset().top
            }, 800);

        });

        $(document).on('click', 'a.delete_row', function (e) {
            e.preventDefault();
            InitPage();
            var r = confirm("يرجى الموافقه على الحذف");
            if(r===false){
                return false;
            }


            var nRow = $(this).parents('tr')[0];
            var aData = myTable.row(nRow).data();
            let idline = $(this).data('idline');
            $("#id_line").val(idline);
            let url= '{{route('mainDonate.deleteajax',$param_url)}}';
            url +="/"+idline;
            let resultAjax = SendToAjax(url,'DELETE');
            //console.log(resultAjax);
            if(resultAjax==undefined){
                notify('حدث خطأ','error');
                return false;
            }
            notify(resultAjax.msg ,resultAjax.cls);
            if(resultAjax.status===false){
                return;
            }
            myTable.row( nRow) .remove().draw();
            InitPage();
        });


        $(document).on('click', '#btn_cancel', function (e) {
            InitPage();
        });

        $(document).on('click', '#showbydate', function (e) {
            var fdate= $("#fromdate").val();
            var tdate= $("#todate").val();
            let url='{{route('mainDonate.show' ,$param_url)}}';

            if(fdate=="" || tdate==""){
                notify("תאריך לא תקין" ,"error");
                return false;
            }
            //url += "/" + fdate + "/" + tdate;
            url += "?fromDate=" + fdate + "&toDate=" + tdate;
            //alert(url);
            window.location = url;
        });


        function InitPage(){
            myRowTable=null;
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


