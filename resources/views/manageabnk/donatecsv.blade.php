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
        <form method="post"  enctype="multipart/form-data" action="{{route('mainDonate.export')}}"  >
            @csrf

            <div class="card card-default">
                <div class="card-header">יצוא קובץ - הורד קובץ</div>
                <div class="card-body">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <button class="btn btn-primary mb-2" type="submit" name="btn_savecsv" >تنزيل ملف - تبرعات بقيمة</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-6">
        <form method="post" enctype="multipart/form-data" action="{{route('mainDonate.import')}}" >
            @csrf

            <div class="card card-default">
            <div class="card-header">יבוא קובץ תרומות - צרף קובץ</div>
            <div class="card-body">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                                    <label >בחר קובץ</label>
                                        <input type="file" name="filecsv" id="filecsv" accept=".dat"
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


    <div class="col-6">
        <form method="post" enctype="multipart/form-data" action="{{route('donateType.import')}}" >
            @csrf

            <div class="card card-default">
                <div class="card-header">יבוא קובץ סוגי תרומה - צרף קובץ</div>
                <div class="card-body">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <label >בחר קובץ</label>
                            <input type="file" name="filecsv" id="filecsv" accept=".dat"
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





