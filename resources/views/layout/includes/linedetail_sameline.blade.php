{{--
linedetail_sameline
שורה דומה מחודש קודם
--}}


@if(count($bankslin_same)==0)
    <p>لا توجد معلومات مشابهة من الشهر السابق</p>
@else

    <div class="container-fluid">
        <p>חודש {{$formattedLastMonth}}</p>

    </div>
    <table class="table table-striped my-4 w-100" >
        <thead>
        <tr>
            <th>תאריך תנועה</th>
            <th>תיאור</th>
            <th>אסמכתא</th>
            <th>חובה</th>
            <th>זכות</th>
            <th>סוג תנועה</th>
            <th>עמותה</th>
            <th>הערה</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bankslin_same  as $item)
            <tr>
                <td>{{$item['datemovement']}}</td>
                <td>{{$item['description']}}</td>
                <td>{{$item['asmcta']}}</td>
                <td id="sum_mandatory">{{$item['amountmandatory']}}</td>
                <td id="sum_right">{{$item['amountright']}}</td>
                <td>@if(!empty($item['titletwo']['ttwo_text']))
                        {{$item['titletwo']['ttwo_text']}}
                    @endif
                </td>
                <td>
                    @if(!empty($item['enterprise']['name']))
                        {{$item['enterprise']['name']}}
                    @endif
                </td>
                <td>{{$item  ['note']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table class="table table-striped my-4 w-100">
        <thead>
        <tr>
            <th>
                @if($item['amountmandatory']=='0')
                    זכות
                @else
                    חוב
                @endif
            </th>

            <th>פרויקט</th>
            <th>עיר</th>

            <th>
                @if($item['amountmandatory']=='0')
                    סוג הכנסה
                @else
                    סוג הוצאה
                @endif
            </th>
            <th>קמפיין</th>
            <th>הערה</th>
        </tr>
        </thead>
        <tbody>
        @foreach($bankslin_same  as $item)
            @foreach($item['banksdetail']  as $item_detail)
                <tr>
                    <td>
                        @if($item['amountmandatory']=='0')
                            {{ $item_detail['amountright'] }}
                        @else
                            {{ $item_detail['amountmandatory'] }}
                        @endif
                    </td>

                    <td>{{ $item_detail['projects']['name'] }}</td>
                    <td>{{ $item_detail['city']['city_name'] }}</td>

                    <td>
                        @if($item['amountmandatory']=='0')
                            @if(!empty($item_detail['income']['name'])){{ $item_detail['income']['name'] }}@endif
                        @else
                            @if(!empty($item_detail['expense']['name'])){{ $item_detail['expense']['name']}}@endif
                        @endif
                    </td>

                    <td> @if(!empty($item_detail['campaigns']['name_camp']))
                            {{$item_detail['campaigns']['name_camp']}}
                        @endif
                    </td>

                    <td>{{ $item_detail['note'] }}</td>

                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
@endif








