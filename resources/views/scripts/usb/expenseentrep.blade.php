
    <script type="text/javascript">


        let myTable,myRowTable=null;

        let _param_url  ={!! json_encode($param_url) !!}
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


            /**
            const queryString = window.location.search;
            const urlParams = new URLSearchParams(queryString);
            if( urlParams.get('fter') != null && urlParams.get('fter')=='1'){
                ramdanFter();
                $("#divselectprog").hide();
                $("#id_expense").hide();
                $('label[for="note"]').text('عدد العائلات');
                $('label[for="id_expense"]').text('صاحب خط التوزيع');
            }else{
                $("#divselectprog").show();
                $("#id_expense").show();
                $('label[for="note"]').text('ملاحظة');
                $('label[for="id_expense"]').text('مورد');
            }
            **/
            InitPage();

            if(_param_url['flgFeter'] != -1){
                ramdanFter();
            }
        });


        $('#dateexpense').on( 'change', function () {
            const queryString = window.location.search;
            //const urlParams = new URLSearchParams(queryString);
            if(_param_url['flgFeter'] != -1){
                $("#dateinvoice").val($(this).val());
            }
            /**
            if( urlParams.get('fter') != null && urlParams.get('fter')=='1') {
                $("#dateinvoice").val($(this).val());
            }**/
        });


        $('#id_proj').on( 'change', function () {
            $('#id_expense').find('option').remove()

            let url= '{{route('table.expensebyproject.store')}}';

            url +="/"+$(this).val();
            let resultAjax = SendToAjax(url,'GET');
            //console.log(resultAjax);
            $('#id_expense').append(`<option value="0">اختر</option>`);
            $('#id_expense').append(`<option value="999999">مورد اخر</option>`);
            for(let i=0;i<resultAjax.length;i++){
                $('#id_expense').append(`<option value="${resultAjax[i]['id']}">  ${resultAjax[i]['name']} </option>`);
            }
            $("#id_expense").val('0');
            _param_url['id_proj']= $('#id_proj').val();

            $('#id_expense').change();
        }).change();

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
                let url= '{{route('usb_expense.storeajax',['p1','p2','p3'])}}';
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
                let thisRow = myTable.row.add($(resultAjax['rowHtml'])[0]).draw().node();
                //console.log(xxx);
                //$(xxx.node()).addClass('add-animation');
                //animationNewElement(thisRow);
            }else{
                //return;
                //update
                //notify('update');
                let url= '{{route('usb_expense.updateajax',['p1','p2','p3'])}}';
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
                /**
                $(thisRow).addClass('add-animation');
                setTimeout(() => {
                    $(thisRow).removeClass('add-animation');
                }, 2000)
                **/
                //console.log($(thisRow));
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

            let url='{{route('usb_expense.editajax',['p1','p2','p3'])}}';
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
            //console.log(row.id_expense);
            if(row.id_expense== null){
                row.id_expense='999999';
            }
            $("#dateexpense").val(row.dateexpense);
            $("#asmctaexpense").val(row.asmctaexpense);
            $("#id_expense").val(row.id_expense);
            $("#id_expenseother").val(row.id_expenseother);
            $("#amount").val(row.amount);
            $("#id_titletwo").val(row.id_titletwo);
            $("#numinvoice").val(row.numinvoice);
            $("#dateinvoice").val(row.dateinvoice);
            $("#note").val(row.note);

            $('#id_expense').change();

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
            let url= '{{route('usb_expense.deleteajax',['p1','p2','p3'])}}';
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
            let url='{{route('usb_expense_entrep.show' ,['p1','p3'])}}';
            url = urlParam(url);

            if(_param_url['id_proj']!='-1'){
                url += `/${_param_url['id_proj']}`;
            }
            if(_param_url['flgFeter']!='-1'){
                url += `/${_param_url['flgFeter']}`;
            }

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

        $(document).on('change', '#id_expense', function (e){
            let a = $('#id_expense').val();
            if(a=='999999'){
                $("#id_expenseother").show();
            }else{
                $("#id_expenseother").val('').hide();
            }
        }).change();


        /**
        $(document).on('click', '#fter', function (e){
            if ($('#fter').is(':checked')) {
                ramdanFter();
            }else{
                InitPage();
            }
        });
         **/
        function urlParam(url ,id_line){
            url = url.replace('p1', _param_url['id_entrep'] ).replace('p2', _param_url['id_proj'] ).replace('p3', _param_url['id_city'] )
            if(id_line!= undefined ){
                url += "/" + id_line;
            }
            return url;
        }

        function InitPage(){

            myRowTable=null;
            $("#id_line").val('0');
            $("#id_proj").change();
            $("#asmctaexpense").val('');
            //$("#").val('');

            //$("#id_expense").val('0').change();
            $("#id_expenseother").val('');
            $("#amount").val('');
            $("#id_titletwo").val('');
            $("#numinvoice").val('');
            $("#dateinvoice").val('');
            $("#note").val('');

            let today = new Date();
            let yyyy = today.getFullYear();
            let mm = today.getMonth() + 1; // Months start at 0!
            let dd = today.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            const formattedToday =  yyyy + '-' + mm + '-' + dd;
            $("#dateexpense").val('');
            //console.log(_param_url['flgHideSelectProj']);
            if(_param_url['flgHideSelectProj'] != -1){
                //alert('aa');
                $("#id_proj").val(_param_url['id_proj']).change();
                $(".cls-proj").hide();

            }





        }

        /**
         * رمضان - صدقة القطر
         */
        function ramdanFter(){
            ///NEXTTTTTTTTTTTTTTTTTTTTTTTTTTTT
            $("#id_expense").hide();
            $('label[for="id_expense"]').text('صاحب خط التوزيع');
            $('#id_expenseother').attr('placeholder', 'صاحب خط التوزيع');

            $('label[for="note"]').text('عدد العائلات');

            let today = new Date();
            let yyyy = today.getFullYear();
            let mm = today.getMonth() + 1; // Months start at 0!
            let dd = today.getDate();

            if (dd < 10) dd = '0' + dd;
            if (mm < 10) mm = '0' + mm;

            const formattedToday =  yyyy + '-' + mm + '-' + dd;
            //alert(formattedToday);
            $("#id_expense").val('999999').change();
            $("#numinvoice").val('0') ;
            //$("#dateinvoice").val(formattedToday);
            $("#id_titletwo").val('3') ;
            //$("#dateexpense").val(formattedToday) ;
            $("#asmctaexpense").val('0') ;

            $(".ramdan").hide();

        }


    </script>


