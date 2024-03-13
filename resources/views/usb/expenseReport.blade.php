

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


