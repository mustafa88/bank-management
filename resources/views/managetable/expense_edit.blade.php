@extends('layout.mainangle')


@section('page-head')
    <style>
        .listol li {
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('page-content')

    <div class="col-12">
        <div class="card">
            {{--
            <div class="card-header">
                <h3 class="card-title">Button sizing</h3>
            </div>
            <div class="card-body">
                --}}
            @if (Session::has('success'))
                <div class="row">
                    <div class="alert alert-success" role="alert"><strong>{{ Session::get('success') }}</strong></div>
                </div>
            @endif

            @if($errors->any())
                {!! implode('', $errors->all('<div>:message</div>')) !!}
            @endif
            <div>
                {{$project['enterprise']['name']}} => {{$project['name']}}
            </div>
            <fieldset>

                    <div class="col-12">

                        @if(isset($project['expense']))
                            <ol class="listol">
                                @foreach($project['expense'] as $item)
                                    <li>{{$item['name']}}</li>
                                @endforeach
                            </ol>
                        @endif

                    </div>
                    <div class="col-12">
                        <form method="post" action="{{route('table.expense.store',$project['id'])}}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label" for="name">اضافة نوع مدخول جديد</label>
                                <div class="col-md-4"><input type="text" id="name" name="name" class="form-control" autofocus></div>
                            </div>
                            <input type="submit" name="save" class="mb-1 btn btn-primary" value="حفظ">
                        </form>
                    </div>

            </fieldset>


        </div>
    </div>

@endsection


@section('page-script')
    {{--  load file js from folder public --}}
@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}



