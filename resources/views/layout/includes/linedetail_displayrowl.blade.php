
<tr><td>{{$rowBanksLine['datemovement']}}</td>
    <td>{{$rowBanksLine['description']}}</td>
    <td>{{$rowBanksLine['asmcta']}}</td>
    <td>{{$rowBanksLine['amountmandatory']}}</td>
    <td>{{$rowBanksLine['amountright']}}</td>


    <td>@if($rowBanksLine['nobank']=='1')לא @else כן @endif</td>

    <td>
        @if(!empty($rowBanksLine['titletwo']['ttwo_text']))
            {{$rowBanksLine['titletwo']['ttwo_text']}}
        @endif
    </td>
    <td>
        @if(!empty($rowBanksLine['enterprise']['name']))
            {{$rowBanksLine['enterprise']['name']}}
        @endif
    </td>
    <td>{{$rowBanksLine['note']}}</td>
    <td><span
            class="@if($rowBanksLine['duplicate']==1) table-danger  @elseif($rowBanksLine['done']==1) table-success @else table-warning @endif">
            @if($rowBanksLine['duplicate']==1) שורה כפולה  @elseif($rowBanksLine['done']==1) שורה תקינה @else שורה
            לא תקינה @endif
        </span></td>
    <td>

        @if(!empty($rowBanksLine['enterprise']['name']) and !empty($rowBanksLine['titletwo']['ttwo_text']))
        <div class="btn-group mb-1">
            <button class="btn dropdown-toggle btn-primary" type="button" data-toggle="dropdown"
                    aria-expanded="false">בחר
            </button>
            <div class="dropdown-menu dropmenu" role="menu" x-placement="bottom-start">
                <a class="dropdown-item edit_row" href="javascript:void(0)" data-idline="{{$rowBanksLine['id_line']}}"><i
                        class="far fa-edit"></i> עריכה</a>
                <a class="dropdown-item delete_row" href="javascript:void(0)" data-idline="{{$rowBanksLine['id_line']}}"><i
                        class="far fa-trash-alt"></i> מחיקה</a>


                @if($rowBanksLine['duplicate']==1)
                    <a class="dropdown-item dulicate_row" href="javascript:void(0)"
                       data-idline="{{$rowBanksLine['id_line']}}"><i class="far fa-check-circle"></i> אישור שורה</a>
                @endif
                <div class="dropdown-divider"></div>
                <a class="dropdown-item divdetail_row" href="javascript:void(0)"
                   data-idline="{{$rowBanksLine['id_line']}}">חלוקת שורה נוכחית</a>

                <a class="dropdown-item detail_row" href="{{route('linedetail.show',$rowBanksLine['id_line'])}}"
                   data-idline="{{$rowBanksLine['id_line']}}">עריכת פירוט שורה</a>

                <a class="dropdown-item sameline_row" href="javascript:void(0)"
                   data-idline="{{$rowBanksLine['id_line']}}">שורה דומה</a>
            </div>
        </div>
        @endif
        <label class="c-checkbox">
            <input type="checkbox" name="selectbox[]" value="{{$rowBanksLine['id_line']}}"
                   data-titletwo="{{$rowBanksLine['id_titletwo']}}" data-done="{{$rowBanksLine['done']}}"
                   data-titletwo="{{$rowBanksLine['id_titletwo']}}">
            <span class="fa fa-check"></span>
            סמן שורה</label>
    </td>
</tr>
