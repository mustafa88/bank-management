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
    <div>
        <h3>معطيات حساب بنك {{$bank['enterprise']['name']}} @if(!empty($bank['projects']['name'])) مشروع {{$bank['projects']['name']}} @endif</h3>
    </div>


        <form method="post" action="{{route('notypeline.storetypeline',$bank['id_bank'])}}" id="formselct">
        @csrf

            @if (Session::has('success'))
                <div class="card card-default">
                    <div class="card-header"></div>
                    <div class="card-body">
                            <div class="row">
                                <div class="alert alert-success" role="alert">
                                    <strong>{{ Session::get('success') }}</strong>
                                </div>
                            </div>
                    </div>
                </div>
            @endif
    <div>

        <!-- DATATABLE DEMO 1-->
        <div class="card card-default">
            <div class="card-header">
                <div class="card-title">שורות ללא סוג תנועה</div>

                <div class="form-row align-items-center">

                    <div class="col-auto">
                        <label for="showTitleTwo">סוג תנועה להצגה</label>
                        <select class="form-control custom-select custom-select-sm  " name="showLineBankTitleTwo" id="showLineBankTitleTwo"  >
                            <option value="0">הכל</option>
                            @foreach($title as $item)
                                <optgroup label="{{$item['tone_text']}}">
                                    @if(isset($item['title_two']))
                                        @foreach ($item['title_two'] as $item2 )
                                            <option value="{{$item2['ttwo_id']}}"
                                           @if(request('showLineBankTitleTwo')==$item2['ttwo_id']) selected @endif> {{$item2['ttwo_text']}}</option>
                                        @endforeach
                                    @endif
                                </optgroup>
                            @endforeach
                        </select>
                    </div>

                </div>

                <div class="text-sm">
                    <input type="submit" name="savetype" value="جفظ" class="mb-1 btn btn-success" >
                </div>





            </div>
            <div class="card-body">
                <table class="table table-striped my-4 w-100 hover" id="datatable1">
                    <thead>
                    <tr>
                        <th>תאריך תנועה</th>
                        <th>תיאור</th>
                        <th>אסמכתא</th>
                        <th>חובה</th>
                        <th>זכות</th>
                        <th>סוג תנועה</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($banksline as $item)
                        @include('layout.includes.linedetail_displayrowl_notype',['rowBanksLine' => $item])
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

    @include( "scripts.managebank.linebanksnotype" )


@endsection





