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

        <div class="card-header">
            <h3 class="card-title">موارد</h3>
        </div>
        <div class="card-body">


            @if (Session::has('success'))
                <div class="row">
                    <p class="alert1 alert-success" role="alert"><strong>{{ Session::get('success') }}</strong></p>
                </div>
            @endif
            <div class="row">


                @if($errors->any())
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                @endif

                <ol>
                    @foreach ($expense as $item )
                        <form method="post" action="{{route('table.income.delete',$item['id'])}}">
                            <li>
                                {{ $item['name']}}
                                {{--
                                @csrf
                                @method('delete')
                                <span style="padding-right: 10px;"><button class="mb-1 btn-xs btn btn-outline-danger" type="submit">حذف</button></span>
                                --}}
                            </li>
                        </form>
                    @endforeach
                    <li>
                        <form method="post" action="{{route('table.expense.store')}}">
                            @csrf
                            <div class="form-row align-items-center">

                                <div class="col-auto">
                                    <input type="text" name="name" placeholder="اسم المورد"
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
        {{--
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
                                       href="{{route('table.expense.edit',$item2['id'])}}">تعديل</a>
                                    </li>

                                @if(isset($item2['expense']))
                                    <ol style="list-style-type: disc;">
                                        @foreach ($item2['expense'] as $item3 )
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
        --}}
    </div>
    </div>

@endsection


@section('page-script')
    {{--  load file js from folder public --}}
@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}



