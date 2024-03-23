
    <script type="text/javascript">


        let myTable,myRowTable=null;

        let _param_url  ={!! json_encode($param_url) !!};

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
                /**
                columnDefs: [
                    {
                        targets: 5,
                        //render: $.fn.dataTable.render.number(',', '.', 3, '')
                    }
                ],
                columnDefs: [
                    {
                targets: 5,
                render: function (data, type, row) {
                    if (type === 'sort') {

                         //https://datatables.net/forums/discussion/53184/sorting-number-with-text
                        // Use jQuery to extract text from HTML element
                        //let content = $(data).text().split(' ');
                        // Expected result: ["15.62", "KH/s"]
                        // if (content && content.length === 2) {
                        //     let prefix = '0';  // If no match sort this to the top
                        //     let hr = content[1]; //Hash Rate "KH/s"
                        //     if (hr === 'MH/s') {
                        //         prefix = '1';
                        //     } else if (hr === 'KH/s') {
                        //         prefix = '2';
                        //     } else if (hr === 'H/s') {
                        //         prefix = '3';
                        //     }
                        //     return prefix + content[0].padStart(7, '0');
                        //     // Example: 20015.62
                        // }

                        console.log(row)
                        return (data.substring(1));
                    }
                    //console.log(data.substring(1))
                    //return (data.substring(1));
                    return data;
                },
                    }
                ],
                 **/

                iDisplayLength: -1
            });

            InitPage();
        });



        $('#id_proj').on( 'change', function () {
            $('#id_incom').find('option').remove()

            let url= '{{route('table.incomebyproject.store')}}';

            url +="/"+$(this).val();
            let resultAjax = SendToAjax(url,'GET');
            //console.log(resultAjax);
            //$('#id_incom').append(`<option value="0">اختر النوع</option>`);
            for(let i=0;i<resultAjax.length;i++){
                $('#id_incom').append(`<option value="${resultAjax[i]['id']}">  ${resultAjax[i]['name']} </option>`);
            }

            _param_url['id_proj']= $('#id_proj').val();

        }).change();

        $('#kabala').on( 'change', function () {
            //להציג קבלות לאותו מס קבלה
            $("#listkabala").hide();
            $("#listkabalabody").html('');
            if($(this).val()==''){
                return ;
            }
            let url= '{{route('usb_income.showKabala',['p1','p2','p3'])}}';
            url = urlParam(url);
            let resultAjax = SendToAjax(url,'GET');

            if(!resultAjax['status']){
                return false;c
            }
            let row = resultAjax['row'];
            console.log(row)
            $("#listkabala").show();
            for(let i=0;i<row.length;i++){
                $('#listkabalabody').append(`<tr><td>${row[i]['dateincome']}</td><td>${row[i]['nameclient']}</td><td>${row[i]['amount']}${row[i]['currency']['symbol']}</td><td>${row[i]['income']['name']}</td><td>${row[i]['titletwo']['ttwo_text']}</td></tr>`);
            }

        });
        /**
         * שמירה
         * save new data or update data exists
         */
        $('#btn_save ,#btn_save_again').on( 'click', function (a,b,c) {

            let id_line = $("#id_line").val();

            if((id_line=='0' && myRowTable!=null) || (id_line!='0' && myRowTable==null)){
                notify('תקלה - נא לדווח לאיש מחשוב');
                return;
            }

            //alert(id_line);
            if(id_line=='0'){
                //insert
                let url= '{{route('usb_income.storeajax',['p1','p2','p3'])}}';
                url = urlParam(url);
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
                let url= '{{route('usb_income.updateajax',['p1','p2','p3'])}}';
                url = urlParam(url,id_line);

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
                let thisRow = row.node();
                //animationNewElement(thisRow);
                //let thisRow = row.node();
                //$(thisRow).find('td').eq(1).css("background-color",newData['color_code']);


            }
            let customRest = false;
            if($(this).attr('id')=='btn_save_again'){
                customRest = true;
            }
            InitPage(customRest);
        });

        $(document).on('click', 'a.edit_row', function (e) {
            e.preventDefault();
            InitPage();
            let idline = $(this).data('idline');

            var nRow = $(this).parents('tr')[0];
            var aData = myTable.row(nRow).data();

            let url='{{route('usb_income.editajax',['p1','p2','p3'])}}';
            url = urlParam(url,idline);
            //alert(url);

            let resultAjax = SendToAjax(url,'GET');
            //console.log(resultAjax);

            if(resultAjax.status===false){
                notify(resultAjax.msg ,resultAjax.cls);
                return;
            }
            let row = resultAjax.row;
            $("#id_line").val('0');


            $("#id_proj").val(row.id_proj).change();
            $("#nameclient").val(row.nameclient);
            $("#amount").val(row.amount);
            $("#id_curn").val(row.id_curn);
            $("#id_titletwo").val(row.id_titletwo);
            $("#id_incom").val(row.id_incom);
            $("#kabala").val(row.kabala).change();
            $("#nameovid").val(row.nameovid);
            $("#note").val(row.note);
            $("#kabladat").val(row.kabladat);

            if(row.son =='1'){
                $("#son").prop('checked', true);
            }
            $("#phone").val(row.phone);

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
            let url= '{{route('usb_income.deleteajax',['p1','p2','p3'])}}';
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
            let url='{{route('usb_income_entrep.show' ,['p1','p3'])}}';
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

            let url='{{route('usb_report.show' ,['p1'])}}';
            url = urlParam(url);
            url += "?fromDate=" + fdate + "&toDate=" + tdate;

            //alert(url);
            window.location.href = url;
        });

        $(document).on('click', '#sadkatfter', function (e) {
            let url='{{route('usb_expense_entrep.show' ,['p1','p3'])}}';
            url = urlParam(url);
            url += "?fter=1";

            //alert(url);
            window.location.href = url;
        });

        function urlParam(url ,id_line){
            url = url.replace('p1', _param_url['id_entrep'] ).replace('p2', _param_url['id_proj'] ).replace('p3', _param_url['id_city'] )
            if(id_line!= undefined ){
                url += "/" + id_line;
            }
            return url;
        }




        function InitPage(customRest){

            myRowTable=null;
            $("#id_line").val('0');

            $('#kabala').change();

            if(customRest==true){
                $("#amount").val('');
                return;
            }
            $("#id_proj").val('1').change();
            $("#nameclient").val('');
            $("#amount").val('');//
            $("#id_curn").val('1');
            $("#id_titletwo").val('3');
            //$("#id_incom").val('0');
            $("#kabala").val('').change();

            $("#phone").val('');
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
            $("#kabladat").val(formattedToday);
        }

    </script>


