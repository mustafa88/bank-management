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
                    @foreach ($enterprise as $item )
                        <li>{{ $item['name']}}</li>
                        <ol>
                            @if(isset($item['project']))
                                @foreach ($item['project'] as $item2 )
                                    <li>{{$item2['name']}}</li>
                                @endforeach
                            @endif
                            <li>

                                <form method="post" action="{{route('table.project.store')}}">
                                    @csrf
                                    <div class="form-row align-items-center">
                                        <div class="col-auto">
                                            <input type="text" name="name" class="form-control mb-2"
                                                   placeholder="مشروع جديد للمؤسسة">
                                        </div>
                                        <div class="col-auto">
                                            <input type="submit" name="save" value="حفظ" class="btn btn-primary mb-2">
                                        </div>
                                    </div>
                                    <input type="hidden" name="id_entrp" value="{{$item['id']}}">
                                </form>
                            </li>
                        </ol>
                    @endforeach
                    <li>
                        <form method="post" action="{{route('table.enterprise.store')}}">
                            @csrf
                            <div class="form-row align-items-center">

                                <div class="col-auto">
                                    <input type="text" name="name" placeholder="مؤسسة حديدة"
                                           class="form-control mb-2">
                                </div>
                                <div class="col-auto">
                                    <input type="submit" name="save" value="حفظ المؤسسة"
                                           class="btn btn-success mb-2">
                                </div>

                            </div>
                        </form>
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



