
    <script type="text/javascript">
        let myTable,myRowTable=null;
        $(document).ready(function(){
            myTable = $('#datatable1').DataTable({
                'paging': true, // Table pagination
                'ordering': true, // Column ordering
                "order": [[ 1, 'asc'],[ 0, 'asc']],
                'info': true, // Bottom left status text
                //fixedHeader: true,
                responsive: true,
                aLengthMenu: [
                    [25, 50, 100, 200, -1],
                    [25, 50, 100, 200, "All"]
                ],
                iDisplayLength: -1
            });

           // let rowNode = myTable.row.add( [ '1', 32, 'Edinburgh' ,'xxxx'] ).draw().node();
            $('#formselct').submit(function() {
                //btn_save_divline_amlot
                myTable.page.len( -1 ).search('').draw();
                return true;

            });



        });


        $(document).on('change', '#showLineBankTitleTwo', function (e) {

            var showLineBankTitleTwo= $("#showLineBankTitleTwo").val();
            let url='{{ route('notypeline.show',['id_bank' => $bank['id_bank']]) }}';

            url += "?showLineBankTitleTwo=" + showLineBankTitleTwo;
            //alert(url);
            window.location = url;
        });



    </script>


