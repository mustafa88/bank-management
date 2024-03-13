@extends('layout.mainangle')


@section('page-head')
    <style>

    </style>
@endsection

@section('page-content')


        <div class="card">

            <div class="card-header">
                <h3 class="card-title">{{$projects['enterprise']['name']}} => {{$projects['name']}}</h3>
            </div>

            <div class="card-body">

                @if (Session::has('success'))
                    <div class="row">
                        <div class="alert alert-success" role="alert"><strong>{{ Session::get('success') }}</strong></div>
                    </div>
                @endif

                    <div class="row">
                <ol>
                    @foreach ($campaigns as $item )
                        <li>{{ $item['name_camp']}}
                            <form method="post" action="{{route('table.campaigns.delete',$projects['id'])}}">
                                @csrf
                                @method('delete')

                                <input class="mb-1 btn btn-outline-danger" type="submit" value="حذف">
                                <input type="hidden" name="id_campn" value="{{ $item['id']}}">
                            </form>
                        </li>
                    @endforeach
                    <li>
                        <form method="post" action="{{route('table.campaigns.store',$projects['id'])}}">
                            @csrf
                            <div class="form-row align-items-center">

                                <div class="col-auto">
                                    <input type="text" name="name_camp" placeholder="حملة جديدة"
                                           class="form-control mb-2">
                                </div>
                                <div class="col-auto">
                                    <input type="submit" name="save" value="حفظ الحملة"
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

    @include( "scripts.managetable.campaigns" )
@endsection





