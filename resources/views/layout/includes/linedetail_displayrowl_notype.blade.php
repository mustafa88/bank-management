
<tr><td>{{$rowBanksLine['datemovement']}}</td>
    <td>{{$rowBanksLine['description']}}</td>
    <td>{{$rowBanksLine['asmcta']}}</td>
    <td>{{$rowBanksLine['amountmandatory']}}</td>
    <td>{{$rowBanksLine['amountright']}}</td>

    <td>
        <div class="col-8">
            <select class="form-control custom-select custom-select-sm  " name="selectTitleTwo[]"  >
                <option value="0*0">בחר</option>
                @foreach($title as $item)
                    <optgroup label="{{$item['tone_text']}}">
                        @if(isset($item['title_two']))
                            @foreach ($item['title_two'] as $item2 )
                                <option value="{{$rowBanksLine['id_line']}}*{{$item2['ttwo_id']}}"
                                        @if($rowBanksLine['ofer_title_two']==$item2['ttwo_id']) selected @endif> {{$item2['ttwo_text']}}</option>
                            @endforeach
                        @endif
                    </optgroup>
                @endforeach
            </select>
        </div>
    </td>
</tr>
