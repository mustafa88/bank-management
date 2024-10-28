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
                <input type="hidden" name="zaka" value="{{$flgZaka}}">
                <div class="form-row align-items-center">

                    <div class="col-auto cls-proj">
                        <label for="id_proj">المشروع</label>
                        <select name="id_proj" id="id_proj" class="custom-select custom-select-sm">
                            @foreach($projects as $item)
                                <option value="{{$item['id']}}">{{$item['name']}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto">
                        <label for="kabala">رقم الوصل <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='kabala' id="kabala" type="number">
                    </div>

                    <div class="col-auto">
                        <label for="nameclient">اسم المتبرع <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='nameclient' id="nameclient" type="text" list="list-nameclient">
                        <datalist id="list-nameclient">
                            <option>فاعل خير</option>
                        </datalist>
                    </div>

                    <div class="col-auto">
                        <label for="amount">المبلغ <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='amount' id="amount" type="number">
                    </div>
                    <div class="col-auto">
                        <label for="id_incom">نوع التبرع <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <select name="id_incom" id="id_incom" class="custom-select custom-select-sm">
                        </select>
                    </div>

                    <div class="col-auto">
                        <label for="id_curn">العملة <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <select name="id_curn" id="id_curn" class="custom-select custom-select-sm">
                            @foreach($currency as $item)
                                <option value="{{$item['curn_id']}}">{{$item['symbol']}} - {{$item['name']}} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto">
                        <label for="id_titletwo">طريقة الدفع <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <select name="id_titletwo" id="id_titletwo" class="custom-select custom-select-sm">
                            @foreach($title_two as $item)
                                <option value="{{$item['ttwo_id']}}">{{$item['ttwo_text']}}</option>
                            @endforeach
                        </select>
                    </div>






                    <div class="col-auto">
                        <label for="kabladat">تاريخ الوصل <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name="kabladat" id="kabladat" type="date">
                    </div>

                    <div class="col-auto">
                        <label for="nameovid">اسم المستقبل <span style="color: #ff0000;font-weight: bold;">*</span></label>
                        <input class="form-control mb-2" name='nameovid' id="nameovid" type="text">
                    </div>

                    <div class="col-auto">
                        <label for="phone">هاتف المتبرع</label>
                        <input class="form-control mb-2" name="phone" id="phone" type="text" maxlength="10">
                    </div>

                    <div class="col-auto">
                        <label for="son">ابن الجمعية</label>
                        <input class="form-control mb-2" name="son" id="son" type="checkbox">
                    </div>


                    <div class="col-auto">
                        <label for="note">ملاحظة</label>
                        <input class="form-control mb-6" name='note' id="note" type="text">
                    </div>

                </div>

                <div class="form-row align-items-center">


                    <div class="col-auto">
                        {{--
                        <button class="btn btn-primary mb-2" type="button" name="btn_save" id="btn_save">حفظ</button>
                        <button class="btn btn-secondary mb-2" type="button" name="btn_cancel"   id="btn_cancel">الغاء</button>
                        --}}

                        <!-- Example split danger button -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" id="btn_save">حفظ</button>
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="javascript:void('0')" id="btn_save_again">حفط + تكرار</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" id="btn_cancel" >الغاء</a>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id_line" id="id_line" value="0">
            </form>

            <div>

                <div class="col-xl-6" id="listkabala">
                    <p class="bg-warning">تبرعات على نفس الوصل</p>
                <div class="table-responsive table-bordered ">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>تاريخ الوصل</th>
                            <th>اسم متبرع</th>
                            <th>مبلغ</th>
                            <th>نوع التبرع</th>
                            <th>طريقة التبرع</th>
                        </tr>
                        </thead>
                        <tbody id="listkabalabody">
                        </tbody>
                    </table>
                </div>
                </div>
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
                            @if($flgZaka!=1)
                            <button class="mb-2 btn btn-outline-info" type="button" onclick="selectAll()">اختر الكل</button>
                            <button class="mb-2 btn btn-outline-info" type="button" onclick="unSelectAll()">الغاء الاختيار</button>
                            <button class="mb-2 btn btn-outline-info" type="button" id="exportdataline">تصدير بيانات -
                                <span class="bg-danger-dark" style="padding: 5px;"><span id="sumlineexport" >0</span><!--₪--></span>
                            </button>
                            @endif
                            <!-- <button class="mb-2 btn btn-success" type="button" id="sadkatfter">صدقة فطر</button> -->
                        </div>
                    </div>


                </div>
                <div class="card-body">
                    <table class="table table-striped my-4 w-100 hover" id="datatable1">
                        <thead>
                        <tr>
                            <th>تاريخ</th>
                            <th>مشروع</th>
                            <th>رقم الوصل</th>
                            <th>تاريخ الوصل</th>
                            <th>اسم المتبرع</th>
                            <th>مبلغ</th>
                            <th>نوع التبرع</th>
                            <th>طريقة الدفع</th>
                            <th>هاتف المتبرع</th>
                            <th>ابن الجمعية</th>
                            <th>اسم المستقبل</th>
                            <th>ملاحظه</th>
                            <th>פעולה</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($usbincome as $item)
                            @include('layout.includes.usbincomeentrep',['rowData' => $item])
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
    @include( "scripts.usb.incomeentrep" )

    {{--
    @include('layout.includes.linedetailedit')

    @stack('linedetailedit-script')
    --}}
@endsection





