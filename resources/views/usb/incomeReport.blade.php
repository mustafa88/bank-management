

@foreach($row_titletwo as $item)
<div class="table-responsive table-bordered">
    <table class="table">
        <thead>
        <tr>
            <th colspan="3" class="bg-primary">{{$item['ttwo_text']}}</th>
        </tr>
<tr class="bg-primary-light">
    <th>نوع</th>
    <th>مبلغ بالعملة</th>
    <th>عدد المتبرعين</th>
</tr>
        </thead>
        <tbody>
        @foreach($arrProg[$item['id_titletwo']] as $key1=>$item1)

                <tr >
                    <td>{{$item1->nameincome}}</td>
                    <td>{{$item1->amount}}{{$item1->symbol}}</td>
                    <td>{{$item1->count}}</td>
                </tr>

        @endforeach
        </tbody>
    </table>
</div>
<div class="table-responsive table-bordered">
    <table class="table">
<thead>
<tr class="bg-warning">
    <th colspan="3">جدول تلخيص - {{$item['ttwo_text']}}</th>
</tr>

</thead>
        <tbody>
        @foreach($arrProgSum[$item['id_titletwo']] as $key1=>$item1)

            <tr>
                <td>مجموع {{$item1->symbol}}</td>
                <td>{{$item1->amount}}{{$item1->symbol}}</td>
                <td>{{$item1->count}} متبرعين</td>
            </tr>

        @endforeach
        </tbody>
    </table>
</div>
@endforeach


<div class="table-responsive table-bordered">
    <table class="table">
        <thead>
        <tr>
            <th colspan="3" class="bg-danger">مصروفات</th>
        </tr>
        <tr class="bg-primary-light">
            <th>طريقة الدفع</th>
            <th>المبلغ</th>
            <th>عدد الدفعات</th>
        </tr>
        </thead>
        <tbody>
        @foreach($arrProgExpense as $key1=>$item1)

            <tr >
                <td>{{$item1->ttwo_text}}</td>
                <td>{{$item1->amount}}</td>
                <td>{{$item1->count}}</td>
            </tr>

        @endforeach
        </tbody>
    </table>
</div>

