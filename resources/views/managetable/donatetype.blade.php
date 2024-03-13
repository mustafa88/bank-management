@extends('layout.mainangle')


@section('page-head')
    <style>

    </style>
@endsection

@section('page-content')


    @if (Session::has('success'))
        <div class="row">
            <div class="alert alert-success" role="alert"><strong>{{ Session::get('success') }}</strong></div>
        </div>
    @endif

    @if($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    @endif

    <div class="col-6">
        <form method="post"  enctype="multipart/form-data" action="{{route('donateType.export')}}"  >
            @csrf

            <div class="card card-default">
                <div class="card-header">יצוא קובץ - הורד קובץ</div>
                <div class="card-body">
                    <div class="form-row align-items-center">
                        <div class="col-auto">
                            <button class="btn btn-primary mb-2" type="submit" name="btn_savecsv" >تنزيل ملف - انواع التبرع</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="card card-default col-6 ">
        <div class="card-header">
            <div class="card-title">اضافة نوع جديد</div>

        </div>
        <div class="card-body ">
            <form method="post" action="{{route('donateType.store')}}">
                @csrf
                <div class="form-row align-items-center">

                    <div class="col-auto">
                        وصف نوع التبرع
                        <input type="text" name="name" placeholder="نوع جديد"
                               class="form-control mb-2">
                    </div>

                    <div class="col-auto">
                        السعر المقدر
                        <input type="number" name="price" placeholder="السعر"
                               class="form-control mb-2">
                    </div>
                    <div class="col-auto">
                        <input type="submit" name="save" value="حفظ"
                               class="btn btn-success mb-2">
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card card-default col-6">
        <div class="card-header">
            <div class="card-title">انواع التبرعات العينية</div>

        </div>
        <div class="card-body">

            <table class="table table-striped my-4 w-100 hover" id="datatable1">
                <thead>
                <tr>
                    <th>وصف</th>
                    <th>سعر &#8362;</th>
                </tr>
                </thead>
                <tbody>
                @foreach($donatetype as $item)
                    <tr>
                        <td>{{$item['name']}}</td>
                        <td><input type="number" value="{{$item['price']}}" name="price{{$item['id']}}"
                                   data-dontetypeid="{{$item['id']}}" class="col-3 priceitem" ></td>
                    </tr>
                @endforeach

                </tbody>
            </table>

        </div>
    </div>


@endsection


@section('page-script')
    {{--  load file js from folder public --}}

    @include( "scripts.managetable.donatetype" )
@endsection




