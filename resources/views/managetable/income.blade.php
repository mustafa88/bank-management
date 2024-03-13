@extends('layout.mainangle')


@section('page-head')
    <style>
        .listol li {
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('page-content')


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
        <div class="row">
            <ol class="listol">
                @foreach ($enterprise as $item )
                    <li>{{ $item['name']}}</li>
                    <ol>
                        @if(isset($item['project']))
                            @foreach ($item['project'] as $item2 )
                                <li>{{$item2['name']}}
                                    <a class="btn btn-oval btn-primary btn-xs" style="color: white;"
                                       href="{{route('table.income.edit',$item2['id'])}}">تعديل</a>
                                    </li>

                                @if(isset($item2['income']))
                                    <ol style="list-style-type: disc;">
                                        @foreach ($item2['income'] as $item3 )
                                            <li>{{$item3['name']}}</li>
                                        @endforeach
                                    </ol>
                                @endif

                            @endforeach
                        @endif

                    </ol>
                @endforeach

            </ol>
        </div>
    </div>
    </div>

@endsection


@section('page-script')
    {{--  load file js from folder public --}}
@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}



