<tr>
    <td>{{$rowData['dateincome']}}</td>
    <td>{{$rowData['nameclient']}}</td>
    <td>{{$rowData['amount']}} {{$rowData['currency']['symbol']}}</td>
    <td>{{$rowData['titletwo']['ttwo_text']}}</td>
    <td>{{$rowData['income']['name']}}</td>
    <td>{{$rowData['kabala']}}</td>
    <td>{{$rowData['kabladat']}}</td>
    <td>{{$rowData['phone']}}</td>
    <td>@if(!is_null($rowData['son']) and $rowData['son']=='1')نعم @else لا @endif</td>
    <td>{{$rowData['nameovid']}}</td>
    <td>{{$rowData['note']}}</td>

{{--<td style="direction: ltr;">{{Str::substr($rowData['updated_at'],0,-3)}}</td>--}}
    <td>
    <div class="btn-group mb-1">
        <button class="btn dropdown-toggle btn-primary" type="button" data-toggle="dropdown"
                aria-expanded="false">בחר
        </button>
        <div class="dropdown-menu dropmenu" role="menu" x-placement="bottom-start">
            <a class="dropdown-item edit_row" href="javascript:void(0)" data-idline="{{$rowData['uuid_usb']}}"><i
                    class="far fa-edit"></i> تعديل</a>
            <a class="dropdown-item delete_row" href="javascript:void(0)" data-idline="{{$rowData['uuid_usb']}}"><i
                    class="far fa-trash-alt"></i> حذف</a>
        </div>
    </div>
    </td>
</tr>

