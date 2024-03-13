

<tr>

    <td>{{$rowData['datewrite']}}</td>
    <td>{{$rowData['invoice']}}</td>
    <td>{{$rowData['invoicedate']}}</td>
    <td>{{$rowData['nameclient']}}</td>
    {{--
    <td>{{$rowData['sheep']}}</td>
    <td>{{$rowData['cowseven']}}</td>
    <td>{{$rowData['cow']}}</td>
    <td>{{$rowData['expens']}}</td>
    <td>{{$rowData['totalmoney']}}</td>
    --}}
    <td>
        @if($rowData['sheep']>0){{$rowData['sheep']}} - خروف<br>@endif
        @if($rowData['cowseven']>0){{$rowData['cowseven']}} - سبع عجل<br>@endif
        @if($rowData['cow']>0){{$rowData['cow']}} - عجل<br>@endif
        @if($rowData['expens']>0){{$rowData['expens']}} -مصاريف ذبح <br>@endif
        مجموع: {{number_format($rowData['totalmoney'],0)}}
    </td>
    <td>{{$rowData['titletwo']['ttwo_text']}}</td>
    <td>{{$rowData['phone']}}</td>
    <td>
        @if(!is_null($rowData['waitthll']) and $rowData['waitthll']=='1')
            نعم
        @else
            لا
        @endif
    </td>
    <td>
        @if(!is_null($rowData['partahadi']) and $rowData['partahadi']=='1')
            نعم
        @else
            لا
        @endif
    </td>
    <td>{{$rowData['partdesc']}}</td>
    <td>
        @if(!is_null($rowData['son']) and $rowData['son']=='1')
            نعم
        @else
            لا
        @endif
    </td>
    <td>{{$rowData['nameovid']}}</td>
    <td>{{$rowData['note']}}</td>

    {{--<td style="direction: ltr;">{{Str::substr($rowData['updated_at'],0,-3)}}</td>--}}
    <td>
        <div class="btn-group mb-1">
            <button class="btn dropdown-toggle btn-primary" type="button" data-toggle="dropdown"
                    aria-expanded="false">בחר
            </button>
            <div class="dropdown-menu dropmenu" role="menu" x-placement="bottom-start">
                <a class="dropdown-item edit_row" href="javascript:void(0)" data-idline="{{$rowData['uuid_adha']}}"><i
                        class="far fa-edit"></i> تعديل</a>
                <a class="dropdown-item delete_row" href="javascript:void(0)" data-idline="{{$rowData['uuid_adha']}}"><i
                        class="far fa-trash-alt"></i> حذف</a>
            </div>
        </div>
    </td>
</tr>

