@extends('layout.mainangle')



@section('page-head')
    <!-- Datatables-->
    <link rel="stylesheet" href="{{ asset('angle/vendor/datatables.net-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('angle/vendor/datatables.net-keytable-bs/css/keyTable.bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('angle/vendor/datatables.net-responsive-bs/css/responsive.bootstrap.css') }}">
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
                    <span>הוספה/עריכה תנועה בנק</span>
                </a>
            </h4>

        </div>
        <div class="card-body collapse show" id="addline">
            <form method="post" name="myform" id="myform" action="#">
                @csrf
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label for="datedont">تاريخ التبرع</label>
                        <input class="form-control mb-2" name="datedont" id="datedont" type="date" >
                    </div>


                    <div class="col-auto">
                        <label for="id_typedont">نوع التبرع</label>
                        <select name="id_typedont" id="id_typedont" class="custom-select custom-select-sm">
                            <option value="0">اختر قيمة</option>
                            @foreach($donatetype as $key1 => $item)
                                <option value="{{$item['id']}}" data-price="{{$item['price']}}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto">
                        <label for="amount">سعر الوحده</label>
                        <input class="form-control mb-2" name='price' id="price" type="number" >
                    </div>

                    <div class="col-auto">
                        <label for="amount">الكمية</label>
                        <input class="form-control mb-2" name='quantity' id="quantity" type="number" >
                    </div>

                    <div class="col-auto">
                        <label for="amount">قيمة التبرع</label>
                        <input class="form-control mb-2" name='amount' id="amount" type="number" >
                    </div>

                    <div class="col-auto">
                        <label for="note">وصف</label>
                        <input class="form-control mb-2" name='description' id="description" type="text">
                    </div>


                    <div class="col-auto">
                        <label for="note">اسم المتبرع</label>
                        <input class="form-control mb-2" name='namedont' id="namedont" type="text">
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
                        <div class="alert alert-success" role="alert"><strong>{{ Session::get('success') }}</strong></div>
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
                <div class="card-title">שורות - תרומה בשווה</div>

                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label  for="fromdate">מתאריך</label>
                        <input type="date"  name="fromdate" id="fromdate" value="{{session()->get('showLineFromDate')}}" class="form-control" >
                    </div>
                    <div class="col-auto">
                        <label for="todate" >עד תאריך</label>
                        <input type="date"  name="todate" id="todate" value="{{session()->get('showLineToDate')}}" class="form-control" >
                    </div>
                    <div class="col-auto">
                        <button class="mb-2 btn btn-success" type="button" id="showbydate">عرض</button>
                    </div>
                </div>


            </div>
            <div class="card-body">
                <table class="table table-striped my-4 w-100 hover" id="datatable1">
                    <thead>
                    <tr>
                        <th>תאריך תרומה</th>
                        {{--<th>ארגון</th>
                        <th>פרויקט</th>
                        <th>עיר</th>--}}
                        <th>סוג תרומה</th>
                        <th>מחיר</th>
                        <th>כמות</th>
                        <th>שווה תרומה</th>
                        <th>תיאור</th>
                        <th>שם תורם</th>
                        {{--<th>ת עדכון</th>--}}
                        <th>פעולה</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($donateworth as $item)
                        @include('layout.includes.linedonate',['rowDonate' => $item])
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </form>
@endsection



@section('page-script')

    <!-- Datatables-->
    <script src="{{ asset('angle/vendor/datatables.net/js/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/dataTables.buttons.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons-bs/js/buttons.bootstrap.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.colVis.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.flash.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.html5.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-buttons/js/buttons.print.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-keytable/js/dataTables.keyTable.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-responsive/js/dataTables.responsive.js') }}"></script>
    <script src="{{ asset('angle/vendor/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
    <script src="{{ asset('angle/vendor/jszip/dist/jszip.js') }}"></script>
    <script src="{{ asset('angle/vendor/pdfmake/build/pdfmake.js') }}"></script>
    <script src="{{ asset('angle/vendor/pdfmake/build/vfs_fonts.js') }}"></script>
    <!-- FILESTYLE-->
    <script src="{{ asset('angle/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js') }}"></script><!-- TAGS INPUT-->
    <script src="{{ asset('angle/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script><!-- SWEET ALERT-->

        @include( "scripts.managebank.donate" )

        {{--
        @include('layout.includes.linedetailedit')

        @stack('linedetailedit-script')
        --}}
@endsection





