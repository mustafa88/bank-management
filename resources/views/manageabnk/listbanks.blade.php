@extends('layout.mainangle')


@section('page-head')
    <style>

    </style>
@endsection

@section('page-content')

    @if($errors->any())
        {!! implode('', $errors->all('<div>:message</div>')) !!}
    @endif
    <div class="card card-default">
        <div class="card-header">ادخال/تعديل معلومات حساب البنك</div>
        <div class="card-body">
            <form method="post" action="{{route('banks.store',$bankedt['id_bank'] ?? '0')}}">
                @csrf
                <div class="form-row align-items-center">
                    <div class="col-auto">
                        <label  for="banknumber">رقم البنك</label>
                        <input class="form-control mb-2" name="banknumber" id="banknumber" type="number" value="{{ $bankedt['banknumber'] ?? '' }}">
                    </div>

                    <div class="col-auto">
                        <label  for="bankbranch">رقم الفرع</label>
                        <input class="form-control mb-2" name='bankbranch' id="bankbranch" type="number" value="{{ $bankedt['bankbranch'] ?? '' }}">
                    </div>
                    <div class="col-auto">
                        <label  for="bankaccount">رقم الحساب</label>
                        <input class="form-control mb-2" name='bankaccount' id="bankaccount" type="number" value="{{ $bankedt['bankaccount'] ?? '' }}">
                    </div>

                    <div class="col-auto">


                        <label  for="id_enterproj">مؤسسة/مشروع</label>
                        <select class="custom-select custom-select-sm" name="id_enterproj" id="id_enterproj">
                            <option value="0">اختار</option>
                            @foreach($enterprise as $item)
                                <option value="{{$item['id']}}*0"
                                        @if(isset($bankedt['id_enter'])  and $bankedt['id_enter'].'*0'==$item['id'].'*0'  )
                                        selected
                                        @endif
                                        style="font-weight: bold;"><b>{{$item['name']}}</b></option>
                                @if(isset($item['project']))
                                    @foreach($item['project'] as $item2)
                                        <option value="{{$item['id']}}*{{$item2['id']}}"
                                                @if(isset($bankedt['id_enter'])  and $bankedt['id_enter'].'*'.$bankedt['id_proj']==$item['id'].'*'.$item2['id']  )
                                                selected
                                            @endif
                                        >&nbsp;&nbsp;&nbsp;&nbsp;{{$item['name']}} => {{$item2['name']}}</option>
                                    @endforeach
                                @endif

                            @endforeach
                        </select>
                    </div>

                    <div class="col-auto"><button class="btn btn-primary mb-2" type="submit">حفظ</button></div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="card card-default">
            <div class="card-header">Bordered Table</div>
            <div class="card-body">
                <div class="table-responsive table-bordered">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم البنك</th>
                            <th>رقم الفرع</th>
                            <th>رقم الحساب</th>
                            <th>مؤسسة</th>
                            <th>مشروع</th>
                            <th>تعديل</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bank as $item)
                            <tr>
                                <td>{{$item['id_bank']}}</td>
                                <td>{{ $item['banknumber'] }}</td>
                                <td>{{ $item['bankbranch'] }}</td>
                                <td>{{ $item['bankaccount'] }}</td>
                                <td>{{ $item['enterprise']['name'] }}</td>
                                <td>{{ $item['projects']['name'] ?? '' }}</td>
                                <td>
                                    <a class="mb-1 btn-xs btn btn-outline-primary" href="{{route('banks.show',$item['id_bank'])}}" >تعديل</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>


@endsection


@section('page-script')
    {{--  load file js from folder public --}}
@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}




