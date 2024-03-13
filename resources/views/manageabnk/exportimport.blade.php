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
        <form method="post"  enctype="multipart/form-data" id="formexport" action="{{route('export_import.export')}}"  >
            @csrf

            <div class="card card-default">
                <div class="card-header">יצוא קובץ - הורד קובץ</div>
                <div class="card-body">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <select name="typefile" id="typefile" class="custom-select custom-select-sm">
                                <option value="0">نوع الملف</option>
                                <option value="income">مدخولات</option>
                                <option value="expense">مصروفات</option>
                                <option value="adahi">اضاحي</option>
                                <option value="donate">تبرعات عينيه</option>
                                <option value="donatetype">انواع التبرعات العيتية</option>



                            </select>
                            <button class="btn btn-primary mb-2" type="submit" name="btn_savecsv" >تنزيل ملف</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="col-6">
        <form method="post" enctype="multipart/form-data" action="{{route('export_import.import')}}" >
            @csrf

            <div class="card card-default">
            <div class="card-header">יבוא קבצי מערכת - צרף קובץ</div>
            <div class="card-body">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                                    <label >בחר קובץ</label>
                                        <input type="file" name="filedat" id="filedat" accept=".dat"
                                               class="form-control filestyle" data-classbutton="btn btn-secondary"
                                               data-classinput="form-control inline"
                                               data-icon="&lt;span class='fa fa-upload mr-2'&gt;&lt;/span&gt;">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary mb-2" type="submit" name="btn_savedat" >حفظ</button>
                        </div>
                    </div>
            </div>
        </div>
        </form>
    </div>

{{--
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
    </div>--}}


@endsection


@section('page-script')
    {{--  load file js from folder public --}}
    <!-- FILESTYLE-->
    <script src="{{ asset('angle/vendor/bootstrap-filestyle/src/bootstrap-filestyle.js') }}"></script><!-- TAGS INPUT-->


    @include( "scripts.managebank.exportimport" )
@endsection





