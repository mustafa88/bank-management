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
        <div  >
        {{$project['enterprise']['name']}} => {{$project['name']}}
        </div>
        <fieldset>
            <form method="post" action="{{route('table.connect_projects_expense.edit',$project['id'])}}">
                @csrf
            <div class="col-12">
        @foreach($expense as $item)
            <label class="c-checkbox">
                <input type="checkbox" name="selctexpense[]" value="{{$item['id']}}"
                       @if (in_array($item['id'], array_column($project['expense'], 'id')) ) checked @endif            >
                <span class="fa fa-check"></span>{{$item['name']}}
            </label>
        @endforeach
            </div>
            <div class="col-12">
                <input type="submit" name="save" class="mb-1 btn btn-primary" value="حفظ">
            </div>
            </form>
        </fieldset>




    </div>
    </div>

@endsection


@section('page-script')
    {{--  load file js from folder public --}}
@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}



