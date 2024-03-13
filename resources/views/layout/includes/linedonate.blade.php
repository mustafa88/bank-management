<tr>
    <td>{{$rowDonate['datedont']}}</td>
    {{--
    <td>{{$rowDonate['enterprise']['name']}}</td>
    <td>{{$rowDonate['projects']['name']}}</td>
    <td>{{$rowDonate['city']['city_name']}}</td>
    --}}

    <td>{{$rowDonate['donatetype']['name']}}</td>
    <td>{{$rowDonate['price']}}</td>
    <td>{{$rowDonate['quantity']}}</td>
    <td>{{$rowDonate['amount']}}</td>
    <td>{{$rowDonate['description']}}</td>
    <td>{{$rowDonate['namedont']}}</td>

{{--<td style="direction: ltr;">{{Str::substr($rowDonate['updated_at'],0,-3)}}</td>--}}
    <td>
    <div class="btn-group mb-1">
        <button class="btn dropdown-toggle btn-primary" type="button" data-toggle="dropdown"
                aria-expanded="false">בחר
        </button>
        <div class="dropdown-menu dropmenu" role="menu" x-placement="bottom-start">
            <a class="dropdown-item edit_row" href="javascript:void(0)" data-idline="{{$rowDonate['uuid_donate']}}"><i
                    class="far fa-edit"></i> עריכה</a>
            <a class="dropdown-item delete_row" href="javascript:void(0)" data-idline="{{$rowDonate['uuid_donate']}}"><i
                    class="far fa-trash-alt"></i> מחיקה</a>
        </div>
    </div>
    </td>
</tr>

