@extends('layout.mainangle')


@section('page-head')
    <style>

    </style>
@endsection

@section('page-content')

    @if($errors->any())
        {!! implode('', $errors->all('<div class="alert alert-danger" role="alert">:message</div>')) !!}
    @endif
    @if (Session::has('success'))
        <div>
                <div class="row">
                    <div class="alert alert-success" role="alert"><strong>{{ Session::get('success') }}</strong></div>
                </div>
        </div>
    @endif

    <div class="col-6">
        <form method="post" enctype="multipart/form-data" action="{{route('banks.storeFileCsv')}}" id="myform">
            @csrf
        <div class="card card-default">
            <div class="card-header">اختيار بنك</div>
            <div class="card-body">
                <div class="table-responsive table-bordered">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم البنك</th>
                            <th>رقم الفرع</th>
                            <th>رقم الحساب</th>
                            <th>مؤسسة</th>
                            <th>مشروع</th>
                            <th>تعديل</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bank as $item)
                            <tr>
                                <td>{{$item['id_bank']}}</td>
                                <td>{{ $item['banknumber'] }}</td>
                                <td>{{ $item['bankbranch'] }}</td>
                                <td>{{ $item['bankaccount'] }}</td>
                                <td>{{ $item['enterprise']['name'] }} </td>
                                <td>{{ $item['projects']['name'] ?? '' }}</td>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input allradio" id="bank{{$item['id_bank']}}" type="radio"
                                               name="numberbank" value="{{$item['id_bank']}}" data-enterp="{{ $item['enterprise']['id'] }}" >
                                        <label class="form-check-label" for="bank{{$item['id_bank']}}">اختيار</label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

        <div class="card card-default">
            <div class="card-header">צרף קובץ</div>
            <div class="card-body">


                    <div class="form-row align-items-center">

                        <div class="col-auto">
                            <label for="idselect_enter">עמותה</label>
                            <select class="form-control custom-select custom-select-sm " name="enterp" id="enterp">
                                <option value="0">בחר עמותה</option>
                                @foreach($enterprise as $item)
                                    <option value="{{$item['id']}}" >{{$item['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                                    <label >בחר קובץ</label>
                                        <input type="file" name="filecsv" id="filecsv" accept=".csv"
                                               class="form-control filestyle" data-classbutton="btn btn-secondary"
                                               data-classinput="form-control inline"
                                               data-icon="&lt;span class='fa fa-upload mr-2'&gt;&lt;/span&gt;">



                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary mb-2" type="submit" name="btn_savecsv" >حفظ</button>
                        </div>
                    </div>

            </div>
        </div>
        </form>
    </div>


@endsection


@section('page-script')
    {{--  load file js from folder public --}}
    <!-- FILESTYLE-->
    <script src="{{ asset('angle/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js') }}"></script><!-- TAGS INPUT-->


    @include( "scripts.managebank.loadcsvtobank" )
@endsection





