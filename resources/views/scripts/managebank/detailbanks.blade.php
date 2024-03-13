
    <script type="text/javascript">
        let myTable,myRowTable=null;
        $(document).ready(function(){

            myTable = $('#datatable1').DataTable({
                'paging': true, // Table pagination
                'ordering': true, // Column ordering

                'info': true, // Bottom left status text
                responsive: true,
            });

            //https://www.iranthajayasekara.com/blog/jquery-dynamic-table-row-inserter.html

        });


        $('#btn_save').on( 'click', function () {

            let dropmenu=`<div class="btn-group mb-1">
                                    <button class="btn dropdown-toggle btn-primary" type="button" data-toggle="dropdown"
                                            aria-expanded="false">בחר
                                    </button>
                                    <div class="dropdown-menu dropmenu" role="menu" x-placement="bottom-start">
                                        <a class="dropdown-item edit_row" href="javascript:void(0)" data-idline="ID_LINE"><i class="far fa-edit"></i> עריכה</a>
                                        <a class="dropdown-item delete_row" href="javascript:void(0)" data-idline="ID_LINE"><i class="far fa-trash-alt"></i> מחיקה</a>
                                    </div>
                                </div>`;

            var id_line = $("#id_line").val();

            if((id_line=='0' && myRowTable!=null) || (id_line!='0' && myRowTable==null)){
                notify('תקלה - נא לדווח לאיש מחשוב');
                return;
            }

            if(id_line=='0'){
                //insert
                //notify('insert');
                //return;

                let url= '{{ route('linedetail.storeajax',$bankslin['id_line']) }}';
                //alert(url);
                let resultAjax = SendToAjax(url,'POST');
                //console.log(resultAjax);
                if(resultAjax==undefined){
                    notify('حدث خطأ','error');
                    return false;
                }
                notify(resultAjax.msg ,resultAjax.cls);
                //return;
                if(resultAjax.status===false){
                    return;
                }
                //return;
                let newData = resultAjax['row'];

                let id_detail = newData.id_detail;
                dropmenu = dropmenu.replaceAll("ID_LINE", id_detail);
                //const name = 'Chris';
                //const greeting = `Hello, ${name}`;
                //
                //dropmenu,
                let rowNode = myTable.row.add( [
                    newData.a,
                    newData.b,
                    newData.c,
                    newData.d,
                    newData.campn,
                    newData.e,
                    dropmenu,
                ] ).draw().node();
                $("#msginfo").text(resultAjax.msginfo);
            }else{
                //update
                let url= '{{ route('linedetail.updateajax',$bankslin['id_line']) }}';
                //alert(url);alert(id_line);

                url += "/" + id_line;
                //alert(url); return;

                let resultAjax = SendToAjax(url,'PUT');
                //console.log(resultAjax);
                if(resultAjax==undefined){
                    notify('حدث خطأ','error');
                    return false;
                }
                notify(resultAjax.msg ,resultAjax.cls);
                if(resultAjax.status===false){
                    return;
                }
                let newData = resultAjax['row'];

                //let id_line = newData.id_line;
                dropmenu = dropmenu.replaceAll("ID_LINE", id_line);
                //'שינוי ערך שדה בטבלה'
                let numberRow = myTable.row(myRowTable)[0][0];
                //console.log(newData);
                let row = myTable.row(numberRow);
                myTable.cell(row, 0).data(newData.a);
                myTable.cell(row, 1).data(newData.b);
                myTable.cell(row, 2).data(newData.c);
                myTable.cell(row, 3).data(newData.d);
                myTable.cell(row, 4).data(newData.campn);
                myTable.cell(row, 5).data(newData.e);
                myTable.cell(row, 6).data(dropmenu);
                myTable.draw();

                $("#msginfo").text(resultAjax.msginfo);
            }

            InitPage();

        });

        $(document).on('click', 'a.edit_row', function (e) {
            e.preventDefault();
            InitPage();
            let idline = $(this).data('idline');
            //alert($(this).data('idline'));
            //return;
            var nRow = $(this).parents('tr')[0];
            var aData = myTable.row(nRow).data();

            //return;

            //['id' => $artist->id, 'name' => $artist->name]
            let url='{{ route('linedetail.editajax',$bankslin['id_line']) }}';
            url +="/"+idline;
            //alert(url);

            let resultAjax = SendToAjax(url,'GET');
            //console.log(resultAjax);

            if(resultAjax.status===false){
                notify(resultAjax.msg ,resultAjax.cls);
                return;
            }

            let row = resultAjax.row;
            //console.log(row);
            $("#scome").val(row.a);
            $("#proj").val(row.b).change();
            $("#city").val(row.c);
            $("#incmexpe").val(row.d);
            $("#id_campn").val(row.campn);
            $("#note").val(row.e);

            myRowTable=nRow;
            $("#id_line").val(idline);

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
            let url= '{{ route('linedetail.deleteajax',$bankslin['id_line']) }}';
            url +="/"+idline;
            let resultAjax = SendToAjax(url,'DELETE');
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

            myTable.row( nRow) .remove().draw();

            $("#msginfo").text(resultAjax.msginfo);
            InitPage();
        });


        $(document).on('click', '#btn_div', function (e) {
            let sumscum = $(this).data('scum');
            let inputscum = $(".allinptscom");

            let scum = (sumscum/inputscum.length).toFixed(2);

            for(var i = 0; i < inputscum.length-1; i++){
                $(inputscum[i]).val(scum);
                sumscum = sumscum - scum;
            }
            //שכום שנשאר - מכנסים לשדה האחרון - מתאים לחלוקה עם שאריות
            $(inputscum[inputscum.length - 1]).val(sumscum.toFixed(2));
            culcMsginfo2();
        });

        $(document).on('change', '.allinptscom', function (e) {
            culcMsginfo2();
        });

        $(document).on('click', '#btn_div_save', function (e) {
            let flg = culcMsginfo2();
            //alert(flg);
            if(flg==false){
                notify("חלוקת סכום לא תקינה" ,'error');
                return;
            }
            $('#myformdivline').submit();
        });
        /**
        $('#myformdivline').submit(function() {
            let flg = culcMsginfo2();
            //alert(flg);
            if(flg==false){
                notify("חלוקת סכום לא תקינה" ,'error');
            }
            return flg;
        });
        **/
        function culcMsginfo2(){
            let sumscum = $("#btn_div").data('scum');
            let inputscum = $(".allinptscom");

            let sumline = 0;
            for(var i = 0; i < inputscum.length; i++){
                sumline = sumline + Number($(inputscum[i]).val());
            }
            sumline = sumline.toFixed(2);
            let hefrshsum = sumscum - sumline;
            hefrshsum = hefrshsum.toFixed(2);
            //alert(hefrshsum);
            let msg,flg=false;
            //Math.abs(sumscum)>=0 && Math.abs(sumscum)<=0.1
            //alert(hefrshsum);
            if(hefrshsum==0){
                msg = "שורה תקינה - בוצעה חלוקה שלמה";
                flg = true;
            } else if(hefrshsum<0) {
                msg = `סכום חלוקה גדול ב ${hefrshsum} ש"ח מסכום שורה - נא לתקן`;
            }else{
                msg = `נותר לחלק ${hefrshsum} ש"ח`;
            }
            //alert(sumscum);
            //alert(msg);
            $("#msginfo2").text(msg);
            return flg;
        }


        $(document).on('click', '#showSameLine', function (e) {

            let url= '{{ route('linedetail.sameline',$bankslin['id_line']) }}';
            let resultAjax = SendToAjax(url,'GET');
            //console.log(resultAjax);
            //return;
            Swal.fire({
                title: '<strong>חלוקת שורה</strong>',
                //icon: 'info',
                html: resultAjax['html'],
                width: 1000,
                showDenyButton: false,
                denyButtonText: `اغلاق`,
            }).then((result) => {
            });

        });
        function InitPage(){
            myRowTable=null;
            $("#id_line").val('0');
            $("#scome").val('0');
            $("#proj").val('0');
            $("#city").val('0');
            $("#id_campn").val('0');
            $("#incmexpe").val('0');
            $("#note").val('');






        }
    </script>





