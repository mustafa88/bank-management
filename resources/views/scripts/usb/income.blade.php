
    <script type="text/javascript">


        let myTable,myRowTable=null;



        $(document).ready(function(){
            myTable = $('#datatable1').DataTable({
                'paging': true, // Table pagination
                'ordering': true, // Column ordering
                "order": [[ 0, 'desc']],
                'info': true, // Bottom left status text
                //fixedHeader: true,
                responsive: true,
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                iDisplayLength: -1
            });

            InitPage();
        });

        /**
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
        **/

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
                let url= '{{route('usb_income.storeajax',$param_url)}}';
                //console.log(url);

                //return url;
                let resultAjax = SendToAjax(url,'POST');
                console.log(resultAjax);
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
                let url= '{{route('usb_income.updateajax',$param_url)}}';

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

            let url='{{route('usb_income.editajax',$param_url)}}';
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


            $("#nameclient").val(row.nameclient);
            $("#amount").val(row.amount);
            $("#id_curn").val(row.id_curn);
            $("#id_titletwo").val(row.id_titletwo);
            $("#id_incom").val(row.id_incom);
            $("#kabala").val(row.kabala);
            $("#nameovid").val(row.nameovid);
            $("#note").val(row.note);
            $("#kabladat").val(row.kabladat);

            $("#phone").val(row.phone);
            if(row.son =='1'){
                $("#son").prop('checked', true);
            }


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
            let url= '{{route('usb_income.deleteajax',$param_url)}}';
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
            let url='{{route('usb_income.show' ,$param_url)}}';

            if(fdate=="" || tdate==""){
                notify("תאריך לא תקין" ,"error");
                return false;
            }
            //url += "/" + fdate + "/" + tdate;
            url += "?fromDate=" + fdate + "&toDate=" + tdate;
            //alert(url);
            window.location = url;
        });


        $(document).on('click', '#showbydatereport', function (e) {
            var fdate= $("#fromdate").val();
            var tdate= $("#todate").val();
            if(fdate=="" || tdate==""){
                notify("תאריך לא תקין" ,"error");
                return false;
            }

            let url='{{route('usb_income.show.report' ,$param_url)}}';
            url += "/" + fdate + "/" + tdate;

            let resultAjax = SendToAjax(url,'GET');

            console.log(resultAjax);

            Swal.fire({
                title: '<strong>تلخيص المدخولات</strong>',
                //icon: 'info',
                html: resultAjax['html'],
                width: 1000,
                showDenyButton: false,
                //showCancelButton: true,
                confirmButtonText: 'أغلاق',
                //denyButtonText: `اغلاق`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below
                if (result.isConfirmed) {
                    var dataObj = {};

                    Swal.fire("   שורות "  )
                } else if (result.isDenied) {
                    Swal.fire('שינוי לא בוצע', '', 'info')
                } */
            })
        });


        function InitPage(){

            myRowTable=null;
            $("#id_line").val('0');

            $("#nameclient").val('');
            $("#amount").val('');
            $("#id_curn").val('1');
            $("#id_titletwo").val('3');
            $("#id_incom").val('0');
            $("#kabala").val('');
            $("#nameovid").val('');
            $("#phone").val('');
            $("#son").prop('checked', false);
            $("#note").val('');

            let today = new Date();
            let yyyy = today.getFullYear();
            let mm = today.getMonth() + 1; // Months start at 0!
            let dd = today.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            const formattedToday =  yyyy + '-' + mm + '-' + dd;
            $("#kabladat").val(formattedToday);
        }

    </script>


