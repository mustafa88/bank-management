@if(isset($bankslin))
    <input type="hidden" name="help_idlinebank" id="help_idlinebank" value="{{$bankslin['id_line']}}" >
<table class="table">
    <thead>
    <tr>
        <th>סכום @if($bankslin['amountmandatory']=='0')זכות@elseחובה@endif</th>
        <th>פרויטק</th>
        <th>עיר</th>
        <th>סוג @if($bankslin['amountmandatory']=='0')זכות@elseחובה@endif</th>
        <th>קמפיין</th>
        <th>הערה</th>
        <th>פעולה</th>
    </tr>
    </thead>
    <tr>
        <td>
            <input type="number" name="scome"  id="scome" value=""
                   class="form-control mb-2 inptrowdetl" @if(isset($fullscome)) readonly @endif >
            @if(isset($fullscome)) ירשם סכום מלא  @endif
        </td>

        <td>
            <select name="proj" id="proj"
                    class="form-control mb-2 custom-select custom-select-sm select-project inptrowdetl"   >
                <option value="0">בחר</option>
                @foreach($bankslin['enterprise']['project'] as $item)
                    <option value="{{$item['id']}}" >{{$item['name']}} </option>
                @endforeach
            </select>
        </td>
        <td>
            <select name="city" id="city" class="form-control mb-2 custom-select custom-select-sm inptrowdetl"   >
                <option value="0">בחר</option>
            </select>
        </td>

        <td>
            <select name="incmexpe" id="incmexpe" class="form-control mb-2 custom-select custom-select-sm inptrowdetl"   >
                <option value="0">בחר</option>
            </select>
        </td>

        <td>
            <select name="id_campn" id="id_campn" class="form-control mb-2 custom-select custom-select-sm inptrowdetl"   >
                <option value="0">בחר</option>
            </select>
        </td>

        <td><input type="text" name="note" id="note" class="form-control mb-2 inptrowdetl" value=""></td>

        <td>
            @if(!isset($fullscome))
                <button class="btn btn-primary mb-2" type="button" name="btn_save" id="btn_save">حفظ</button>
           @endif

        </td>

    </tr>
</table>
@endif

@push('linedetailedit-script')
<script>
    $(document).on('change', '.select-project', function (e) {

        $("#city" ).find('option').remove();
        $("#incmexpe" ).find('option').remove();
        $("#id_campn" ).find('option').remove();

        var sum_mandatory = $("#sum_mandatory").text();
        var sum_right = $("#sum_right").text();
        let url= '{{ route('autocomplate.city.income.expense') }}';
        dataObj = {};
        dataObj['id_proj']= $(this).val();

        dataObj['id_line_bank']= $("#help_idlinebank").val();
        let resultAjax = SendToAjax(url,'POST',null,dataObj);
        //console.log(resultAjax);

        city = resultAjax['row']['city'];
        $("#city").append(`<option value="0">בחר</option>`);
        $.each( city, function( key, value ) {
            //alert( key + ": " + value );
            $("#city").append(`<option value="${value["city_id"]}">${value["city_name"]}</option>`);
        });

        let tmpatt;
        /**
        if(sum_mandatory==""){

            tmpatt = resultAjax['row']['income'];
        }else{

            tmpatt = resultAjax['row']['expense'];
        }
        **/
        if(resultAjax['typeselect']=="expense"){
            tmpatt = resultAjax['row']['expense'];
        }else{
            tmpatt = resultAjax['row']['income'];
        }

        $("#incmexpe").append(`<option value="0">בחר</option>`);
        $.each( tmpatt, function( key, value ) {
            $("#incmexpe" ).append(`<option value="${value["id"]}">${value["name"]}</option>`);
        });


        campn = resultAjax['row']['campaigns'];
        $("#id_campn").append(`<option value="0">בחר</option>`);
        $.each( campn, function( key, value ) {
            $("#id_campn").append(`<option value="${value["id"]}">${value["name_camp"]}</option>`);
        });
    });
</script>
@endpush
