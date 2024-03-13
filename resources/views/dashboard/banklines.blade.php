@extends('layout.mainangle')


@section('page-head')
    <style>

    </style>
@endsection

@section('page-content')



    <div class="row">
        <div class="col-xl-8 offset-md-2">

            <div class="card card-default">
                <div class="card-header"> Inline form</div>
                <div class="card-body">
                    <form>
                        <fieldset>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">السنة</label>
                                <div class="col-md-10">
                                    <select class="custom-select custom-select-lg mb-3" id="selectyear">
                                        @foreach($arrYear as $item)
                                            <option value="{{$item}}" @if($item==$year) selected @endif >{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>

            @foreach($banks as $item)
                <div class="card card-default">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <div class="table-responsive table-bordered">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th colspan="13">
                                        {{$item['enterprise']['name']}}
                                        @if(isset($item['projects']['name']))
                                            {{$item['projects']['name']}}
                                        @endif
                                    </th>

                                </tr>
                                </thead>
                                <tbody>

                                <tr>
                                    @foreach($item['arrProg'][0] as $key3 => $item3)
                                        <th>{{$item3}}</th>
                                    @endforeach
                                </tr>

                                <tr>
                                    @foreach($item['arrProg'][1] as $key3 =>$item3)
                                        <td @if(is_numeric($item3))class="table-info"@endif >{{$item3}}</td>
                                    @endforeach
                                </tr>
                                <tr >
                                    @foreach($item['arrProg'][2] as  $key3 =>$item3)
                                        <td class = "
                                                    @if(is_numeric($item['arrProg'][1][$key3]))
                                                        @if($item3=='-')
                                                        table-success
                                                        @else
                                                        table-danger
                                                        @endif
                                                    @endif
                                                    ">{{$item3}}</td>
@endforeach
</tr>

</tbody>
</table>
</div>
</div>
</div>
@endforeach



</div>
</div>



@endsection


@section('page-script')
{{--  load file js from folder public - dashboard --}}
@include('scripts.dashboard.banklines')

@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}



