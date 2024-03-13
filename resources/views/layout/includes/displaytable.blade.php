@if(isset($tableHead) and isset($tableBody))
<table class="table">
    <thead>
    <tr>
        @foreach($tableHead as $item)
            <th>{{$item}}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($tableBody as $row)
        <tr>
@if(isset($tableKeyBody))
{{-- מדפיס רק ערכים שרשומין במערך --}}
                @foreach($tableKeyBody as $item)
                    {{-- אם הערך מתוך מערך ממערך לדוגמא  $item['key1']['key2']--}}
                    {{-- נשלח בצורה הזו key1.key2 --}}
                    @php
                        $format_number = true;
                    @endphp
                    @if(substr($item,-9)=='.noformat')
                        @php
                            $format_number = false;
                            $item = substr($item,0,-9);
                        @endphp
                    @endif


                    @if (strpos($item, '.') !== false)
                        @php

                            if(!empty($row)){
                                $val_item =$row;
                            }
                        @endphp
                        @foreach(explode('.', $item) as $info)

                            @php
                                if(!empty($val_item) and !empty($info)){
                                    $val_item = $val_item[$info];
                                }
                            @endphp
                        @endforeach
                    @else
                        @php
                            if(!empty($row) and !empty($item)){
                               $val_item = $row[$item];
                           }
                        @endphp

                    @endif
                    <td>
                        @if($format_number==true and is_numeric($val_item)){{ number_format($val_item,2) }}@else {{$val_item}} @endif
                    </td>
                @endforeach
@else
{{-- מדפיס כל הערכים --}}
                @foreach($row as $k=>$item)
                    <td>
                        @if(is_numeric($item)){{ number_format($item,2) }}@else {{$item}} @endif
                             </td>
                @endforeach

@endif


</tr>
@endforeach
</tbody>
</table>
@endif

@push('displaytable-script')
<script>
</script>
@endpush
