@extends('layout.mainangle')


@section('page-head')
    <style>

    </style>
@endsection

@section('page-content')



    <div class="row">
        <div class="">

            <div class="card card-default">
                <div class="card-body">
                    <form>
                        <fieldset>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label">السنة</label> {{Request::get('year')}}
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



            @foreach($resultProgram as $item)
                <div class="card card-default">
                    <div class="card-header"></div>
                    <div class="card-body">
                        <div class="table-responsive table-bordered">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th colspan="13">.
                                        {{$item['city']['city_name']}} =====
                                        [יתרת פתיחה שנה={{number_format($item['ytraopen'],2)}}] =====
                                        [יתרת סגירת שנה={{number_format($item['ytraclose'],2)}}]
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
                                        <td @if(is_numeric($item3))class=""@endif >@if(is_numeric($item3)){{number_format($item3,2)}} @else {{$item3}}@endif </td>
                                    @endforeach
                                </tr>

                                <tr>
                                    @foreach($item['arrProg'][2] as $key3 =>$item3)
                                        <td @if(is_numeric($item3))class=""@endif >@if(is_numeric($item3)){{number_format($item3,2)}} @else {{$item3}}@endif </td>
                                    @endforeach
                                </tr>

                                <tr>
                                    @foreach($item['arrProg'][3] as $key3 =>$item3)
                                        <td @if(is_numeric($item3))
                                                class="@if($item3<0) text-danger @else text-success @endif"
                                            @endif >@if(is_numeric($item3)){{number_format($item3,2)}} @else {{$item3}}@endif </td>
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
@include('scripts.dashboard.balance-project')

@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}



