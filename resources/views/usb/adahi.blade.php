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
                        <label for="invoicedate">تاريخ الوصل <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name="invoicedate" id="invoicedate" type="date">
                    </div>

                    <div class="col-auto">
                        <label for="invoice">رقم الوصل <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='invoice' id="invoice" type="number">
                    </div>

                    <div class="col-auto">
                        <label for="nameclient">اسم المتبرع <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='nameclient' id="nameclient" type="text" list="list-nameclient">
                        <datalist id="list-nameclient">
                            <option>فاعل خير</option>
                        </datalist>
                    </div>

                    <div class="col-auto">
                        <label for="sheep">خروف - 2,000<span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='sheep' id="sheep" type="number" >
                    </div>
                    <div class="col-auto">
                        <label for="cowseven">سبع عجل  - 1,400<span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='cowseven' id="cowseven" type="number"  max="6">
                    </div>
                    <div class="col-auto">
                        <label for="cow">عجل - 9,800<span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='cow' id="cow" type="number" >
                    </div>

                    <div class="col-auto">
                        <label for="expens">مصاريف الذبح <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='expens' id="expens" type="number" >
                    </div>
                    <div class="col-auto">
                        <label for="totalmoney">المجموع <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='totalmoney' id="totalmoney" type="number" readonly>
                    </div>


                </div>
                    <div class="form-row align-items-center">

                    <div class="col-auto">
                        <label for="id_titletwo">طريقة الدفع <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <select name="id_titletwo" id="id_titletwo" class="custom-select  mb-2 custom-select-sm">
                            @foreach($title_two as $item)
                                <option value="{{$item['ttwo_id']}}">{{$item['ttwo_text']}}</option>
                            @endforeach
                        </select>
                    </div>


                        <div class="col-auto">
                            <label for="phone">هاتف المتبرع</label>
                            <input class="form-control mb-2" name="phone" id="phone" type="text" maxlength="10">
                        </div>

                        <div class="col-auto">
                            <label for="waitthll">منتظر تحلل</label>
                            <input class="form-control mb-2" name="waitthll" id="waitthll" type="checkbox">
                        </div>

                        <div class="col-auto">
                            <label for="partahadi">جزء من الاضحية</label>
                            <input class="form-control mb-2" name="partahadi" id="partahadi" type="checkbox">
                        </div>

                        <div class="col-auto">
                            <label for="partdesc">وصف الجزء</label>
                            <input class="form-control mb-6" name='partdesc' id="partdesc" type="text">
                        </div>

                        <div class="col-auto">
                            <label for="son">ابن الجمعية</label>
                            <input class="form-control mb-2" name="son" id="son" type="checkbox">
                        </div>




                    <div class="col-auto">
                        <label for="nameovid">اسم المستقبل <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='nameovid' id="nameovid" type="text">
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
                    <div class="card-title">اضاحي عطاء</div>

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
                            <th>تاريخ الادخال</th>
                            <th>الوصل</th>
                            <th>تاريخ الوصل</th>
                            <th>اسم المتبرع</th>
                            {{--
                            <th>خروف</th>
                            <th>سبع عجل</th>
                            <th>عجل</th>
                            <th>مصاريف ذبح</th>
                            <th>مجموع</th>
                            --}}
                            <th>تبرع</th>
                            <th>طريقة الدفع</th>
                            <th>هاتف المتبرع</th>
                            <th>تحلل</th>
                            <th>جزء من الاضحيه</th>
                            <th>وصف</th>
                            <th>ابن الجمعية</th>
                            <th>اسم المستقبل</th>
                            <th>ملاحظه</th>
                            <th>פעולה</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($adahi as $item)
                            @include('layout.includes.adahi',['rowData' => $item])
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
    @include( "scripts.usb.adahi" )

    {{--
    @include('layout.includes.linedetailedit')

    @stack('linedetailedit-script')
    --}}
@endsection





