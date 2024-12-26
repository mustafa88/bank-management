@php
    $colortd= "bg-green";
    if(isset($rowData['id_proj'])){
        switch ($rowData['id_proj']){
                case "2":
                    $colortd= "bg-info";
                    break;
                    case "3":
                        $colortd= "bg-warning";
                        break;
                        case "12":
                            $colortd= "bg-inverse  ";
                            break;
            }
    }

    $notee = [];
    if($rowData['note']!=null){
        $notee[] = $rowData['note'];
    }
    //$notee[] = "وصل الهرنية ";

    if($rowData['kabala_zekou_heyov']!=null){
         switch ($rowData['id_incom']){
             case "9":
                 $notee[] = "وصل ارجاع الرهنية " . $rowData['kabala_zekou_heyov'];
                 break;
            case "10":
                $notee[] = "وصل الرهنية " . $rowData['kabala_zekou_heyov'];
                 break;

            default:

         }
    }
    if($rowData['export_at']!=null){
        $notee[] = "تصدير:" . Str::substr($rowData['export_at'],0,10);
    }
    $noteTxt=count($notee);
    if(count($notee)>0){
        $noteTxt = implode("<br>",$notee);
    }

@endphp

<tr>

    <td>{{$rowData['dateincome']}}</td>
    <td><a class="{{$colortd}}">{{$rowData['projects']['name']}}</a></td>
    <td>{{$rowData['kabala']}}</td>
    <td>{{$rowData['kabladat']}}</td>
    <td>{{$rowData['nameclient']}}</td>
    <td>{{$rowData['amount']}}&nbsp;{{$rowData['currency']['symbol']}}</td>
    <td>{{$rowData['income']['name']}}</td>
    <td>{{$rowData['titletwo']['ttwo_text']}}</td>
    <td>{{$rowData['phone']}}</td>
    <td>@if(!is_null($rowData['son']) and $rowData['son']=='1')
            نعم
        @else
            لا
        @endif</td>
    <td>{{$rowData['nameovid']}}</td>
    <td>{{$noteTxt}}


    </td>

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
        @if(!isset($flgZaka) or $flgZaka!=1)
    <label class="c-checkbox">
        <input type="checkbox" name="selectbox[]" class="selectbox" value="{{$rowData['uuid_usb']}}" data-amount="{{$rowData['amount']}}" >
        <span class="fa fa-check"></span>
        اختيار</label>
        @endif

    </td>
</tr>

