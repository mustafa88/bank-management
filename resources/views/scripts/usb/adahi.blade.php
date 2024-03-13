
    <script type="text/javascript">
        const _SHEEP = 2000;
        const _COW = 9800;
        const _COWSEVEN = 1400;
        let myTable,myRowTable=null;

        let _param_url  ={!! json_encode($param_url) !!}

        $(document).ready(function(){
            myTable = $('#datatable1').DataTable({
                'paging': true, // Table pagination
                'ordering': true, // Column ordering
                "order": [[ 0, 'desc'],[ 1, 'desc']],
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



        $('#sheep , #cow ,#cowseven ,#expens').on( 'change', function () {
            let sheep = Number($('#sheep').val()) * _SHEEP;
            let cow = Number($('#cow').val()) * _COW;
            let cowseven =Number($('#cowseven').val()) * _COWSEVEN;
            let expens =Number($('#expens').val());

            let totalmoney = sheep + cow + cowseven + expens;
            $('#totalmoney').val(totalmoney)

        }).change();


        /**
         * שמירה
         * save new data or update data exists
         */
        $('#btn_save').on( 'click', function (a,b,c) {

            let id_line = $("#id_line").val();

            if((id_line=='0' && myRowTable!=null) || (id_line!='0' && myRowTable==null)){
                notify('תקלה - נא לדווח לאיש מחשוב');
                return;
            }

            //alert(id_line);
            if(id_line=='0'){
                //insert
                let url= '{{route('adahi.storeajax',['p1'])}}';
                url = urlParam(url);
                //console.log(url);

                //return url;
                let resultAjax = SendToAjax(url,'POST');
                 console.log(resultAjax);
                //return;
                if(resultAjax==undefined){
                    notify('حدث خطأ','error');
                    return false;
                }
                notify(resultAjax.msg ,resultAjax.cls);
                if(resultAjax.status===false){
                    return;
                }
                //console.log(myTable.row)
                let thisRow =  myTable.row.add($(resultAjax['rowHtml'])[0]).draw().node();
                //console.log(newRow.node());
                //console.log($(newRow).find('tr'));
                //console.log($(newRow.node()).find('td'));
                //animationNewElement(thisRow);
            }else{
                //return;
                //update
                //notify('update');
                let url= '{{route('adahi.updateajax',['p1'])}}';
                url = urlParam(url,id_line);

                let resultAjax = SendToAjax(url,'PUT');
                console.log(resultAjax);
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
                let thisRow = row.node();
                //animationNewElement(thisRow);
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

            let url='{{route('adahi.editajax',['p1'])}}';
            url = urlParam(url,idline);
            let resultAjax = SendToAjax(url,'GET');
            console.log(resultAjax);

            if(resultAjax.status===false){
                notify(resultAjax.msg ,resultAjax.cls);
                return;
            }
            let row = resultAjax.row;
            $("#id_line").val('0');
            invoicedate

            $("#invoicedate").val(row.invoicedate);
            $("#invoice").val(row.invoice);
            $("#nameclient").val(row.nameclient);
            $("#sheep").val(row.sheep);
            $("#cowseven").val(row.cowseven);
            $("#cow").val(row.cow);
            $("#expens").val(row.expens);
            $("#totalmoney").val(row.totalmoney);
            $("#id_titletwo").val(row.id_titletwo);
            $("#phone").val(row.phone);
            if(row.waitthll =='1'){
                $("#waitthll").prop('checked', true);
            }
            if(row.partahadi =='1'){
                $("#partahadi").prop('checked', true);
            }
            $("#partdesc").val(row.partdesc);
            if(row.son =='1'){
                $("#son").prop('checked', true);
            }
            $("#nameovid").val(row.nameovid);
            $("#note").val(row.note);


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
            let url= '{{route('adahi.deleteajax',['p1'])}}';
            url = urlParam(url,idline);
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
            let url='{{route('adahi.show' ,['p1'])}}';
            url = urlParam(url);

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

            let url='{{route('adahi_report.show')}}';

            url += "?fromDate=" + fdate + "&toDate=" + tdate;

            //alert(url);
            window.location.href = url;
        });

        function urlParam(url ,id_line){
            url = url.replace('p1', _param_url['id_city'] );
            if(id_line!= undefined ){
                url += "/" + id_line;
            }
            return url;
        }




        function InitPage(customRest){

            myRowTable=null;
            $("#id_line").val('0');


            $("#amount").val('');

            $("#nameclient").val('');
            $("#sheep").val('');
            $("#cowseven").val('');
            $("#cow").val('');
            $("#expens").val('').change();

            $("#id_titletwo").val('3');

            $("#invoice").val('');

            $("#phone").val('');
            $("#waitthll").prop('checked', false);
            $("#partahadi").prop('checked', false);
            $("#partdesc").val('');
            $("#son").prop('checked', false);
            $("#note").val('');

            //$("#nameovid").val('');
            let today = new Date();
            let yyyy = today.getFullYear();
            let mm = today.getMonth() + 1; // Months start at 0!
            let dd = today.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            const formattedToday =  yyyy + '-' + mm + '-' + dd;
            $("#invoicedate").val(formattedToday);
        }

    </script>


