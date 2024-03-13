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
            --}}
            <div class="card-body">

                @if (Session::has('success'))
                    <div class="row">
                        <div class="alert alert-success" role="alert"><strong>{{ Session::get('success') }}</strong></div>
                    </div>
                @endif

                    <div class="row">
                <ol>
                    @foreach ($titleone as $item )
                        <li>{{ $item['tone_text']}}</li>
                        <ol>
                            @if(isset($item['title_two']))
                                @foreach ($item['title_two'] as $item2 )
                                    <li>{{$item2['ttwo_text']}}</li>
                                @endforeach
                            @endif
                            <li>

                                <form method="post" action="{{route('table.titletwo.store')}}">
                                    @csrf
                                    <div class="form-row align-items-center">
                                        <div class="col-auto">
                                            <input type="text" name="ttwo_text" class="form-control mb-2"
                                                   placeholder="תת כותרת חדשה">
                                        </div>
                                        <div class="col-auto">
                                            <input type="submit" name="save" value="שמירה" class="btn btn-primary mb-2">
                                        </div>
                                    </div>
                                    <input type="hidden" name="ttwo_one_id" value="{{$item['tone_id']}}">
                                </form>
                            </li>
                        </ol>
                    @endforeach
                    <li>
                        <form method="post" action="{{route('table.title.store')}}">
                            @csrf
                            <div class="form-row align-items-center">

                                <div class="col-auto">
                                    <input type="text" name="tone_text" placeholder="כותרת חדשה"
                                           class="form-control mb-2">
                                </div>
                                <div class="col-auto">
                                    <input type="submit" name="save" value="פתיחת כותרת חדשה"
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

@include( "scripts.managetable.titletable" )



