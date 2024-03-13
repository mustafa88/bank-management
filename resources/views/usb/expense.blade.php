@extends('layout.mainangle')



@section('page-head')

    <style>
    </style>
@endsection

@section('page-content')

    @if($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    @endif



    <div class="card card-default">
        <div class="card-header">
            <h4 class="card-title">
                <a class="text-inherit" data-toggle="collapse" href="#addline" aria-expanded="true">
                    <small><em class="fa fa-plus text-primary mr-2"></em></small>
                    <span>اضافة \ تعديل</span>
                </a>
            </h4>

        </div>
        <div class="card-body collapse show" id="addline">
            <form method="post" name="myform" id="myform" action="#">
                @csrf
                <div class="form-row align-items-center">

                    <div class="col-auto">
                        <label for="dateexpense">تاريخ الدفع</label>
                        <input class="form-control mb-2" name="dateexpense" id="dateexpense" type="date">
                    </div>

                    <div class="col-auto">
                        <label for="asmctaexpense">אסמכתא</label>
                        <input class="form-control mb-2" name='asmctaexpense' id="asmctaexpense" type="number">
                    </div>

                    <div class="col-auto">
                        <label for="id_expense">مورد</label>
                        <select name="id_expense" id="id_expense" class="custom-select custom-select-sm">
                            <option value="0">اختر</option>
                            <option value="999999">אחר</option>
                            @foreach($expense as $item)
                                <option value="{{$item['id']}}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                        <input class="form-control mb-2" name="id_expenseother" id="id_expenseother" type="text" placeholder="اسم مورد">
                    </div>

                    <div class="col-auto">
                        <label for="amount">المبلغ</label>
                        <input class="form-control mb-2" name='amount' id="amount" type="number">
                    </div>

                    <div class="col-auto">
                        <label for="id_titletwo">طريقة الدفع</label>
                        <select name="id_titletwo" id="id_titletwo" class="custom-select custom-select-sm">
                            @foreach($title_two as $item)
                                <option value="{{$item['ttwo_id']}}">{{$item['ttwo_text']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto">
                        <label for="numinvoice">رقم الفاتورة</label>
                        <input class="form-control mb-2" name='numinvoice' id="numinvoice" type="number">
                    </div>

                    <div class="col-auto">
                        <label for="dateinvoice">تاريخ الفاتورة</label>
                        <input class="form-control mb-2" name="dateinvoice" id="dateinvoice" type="date">
                    </div>
                    <div class="col-auto">
                        <label for="note">ملاحظة</label>
                        <input class="form-control mb-6" name='note' id="note" type="text">
                    </div>

                </div>

                <div class="form-row align-items-center">


                    <div class="col-auto">
                        <button class="btn btn-primary mb-2" type="button" name="btn_save" id="btn_save">حفظ</button>
                        <button class="btn btn-secondary mb-2" type="button" name="btn_cancel" id="btn_cancel">الغاء</button>
                    </div>
                </div>
                <input type="hidden" name="id_line" id="id_line" value="0">
            </form>

            <div>
                @if (Session::has('success'))
                    <div class="row">
                        <div class="alert alert-success" role="alert"><strong>{{ Session::get('success') }}</strong>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>

    <form method="post" action="#" id="formselct">
        @csrf

        <div>

            <!-- DATATABLE DEMO 1-->
            <div class="card card-default">
                <div class="card-header">
                    <div class="card-title">سجل المدخولات</div>

                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <label for="fromdate">מתאריך</label>
                            <input type="date" name="fromdate" id="fromdate"
                                   value="{{session()->get('showLineFromDate')}}" class="form-control">
                        </div>
                        <div class="col-auto">
                            <label for="todate">עד תאריך</label>
                            <input type="date" name="todate" id="todate" value="{{session()->get('showLineToDate')}}"
                                   class="form-control">
                        </div>
                        <div class="col-auto">
                            <button class="mb-2 btn btn-success" type="button" id="showbydate">عرض الجدول</button>
                            <button class="mb-2 btn btn-success" type="button" id="showbydatereport">عرض تلخيص</button>
                        </div>
                    </div>


                </div>
                <div class="card-body">
                    <table class="table table-striped my-4 w-100 hover" id="datatable1">
                        <thead>
                        <tr>
                            <th>تاريخ</th>
                            <th>אסמכתא <br>מס צק, מס העברה וכו</th>
                            <th>المورد/شخص</th>
                            <th>مبلغ</th>
                            <th>طريقة الدفع</th>
                            <th>رقم الفاتوره</th>
                            <th>تاريخ الفاتورة</th>
                            <th>ملاحظه</th>
                            <th>פעולה</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($usbexpense as $item)
                            @include('layout.includes.usbexpense',['rowData' => $item])
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </form>
@endsection



@section('page-script')

    <script src="{{ asset('angle/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script><!-- SWEET ALERT-->
    @include( "scripts.usb.expense" )

@endsection





