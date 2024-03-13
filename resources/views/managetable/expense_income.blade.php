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
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">جدول المصروفات</h3>
            </div>
            <div class="card-body">

            @if($errors->any())
                {!! implode('', $errors->all('<div>:message</div>')) !!}
            @endif
                    <div class="row">
                <ol>
                    @foreach ($expense as $item )
                        <li>{{ $item['name']}}</li>
                    @endforeach
                    <li>
                        <form method="post" action="{{route('table.expense.store')}}">
                            @csrf
                            <div class="form-row align-items-center">

                                <div class="col-auto">
                                    <input type="text" name="name" placeholder="نوع مصروف جديد"
                                           class="form-control mb-2">
                                </div>
                                <div class="col-auto">
                                    <input type="submit" name="save" value="حفظ"
                                           class="btn btn-success mb-2">
                                </div>

                            </div>
                        </form>
                    </li>

                    </li>

                </ol>
                    </div>
            </div>
        </div>

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">جدول المدخولات</h3>
            </div>
            <div class="card-body">


                @if($errors->any())
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                @endif
                <div class="row">
                    <ol>
                        @foreach ($income as $item )
                            <li>{{ $item['name']}}</li>
                        @endforeach
                        <li>
                            <form method="post" action="{{route('table.income.store')}}">
                                @csrf
                                <div class="form-row align-items-center">

                                    <div class="col-auto">
                                        <input type="text" name="name" placeholder="نوع مدخول جديد"
                                               class="form-control mb-2">
                                    </div>
                                    <div class="col-auto">
                                        <input type="submit" name="save" value="حفظ"
                                               class="btn btn-success mb-2">
                                    </div>

                                </div>
                            </form>
                        </li>

                        </li>

                    </ol>
                </div>
            </div>
        </div>

@endsection


@section('page-script')
    {{--  load file js from folder public --}}
@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}



