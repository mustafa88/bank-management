@extends('layout.mainangle')


@section('page-head')
    <style>

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
                <ol>
                    @foreach ($city as $item )
                        <li>{{ $item['city_name']}}</li>
                    @endforeach
                    <li>
                        <form method="post" action="{{route('table.city.store')}}">
                            @csrf
                            <div class="form-row align-items-center">

                                <div class="col-auto">
                                    <input type="text" name="city_name" placeholder="بلد حديدة"
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




