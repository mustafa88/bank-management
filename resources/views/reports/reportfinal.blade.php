@extends('layout.mainangle')


@section('page-head')
    <style>

    </style>
@endsection

@section('page-content')




    <div class="card card-default">
        <div class="card-header">انشاء تقرير حسب المؤسسات</div>
        <div class="card-body">
            <div class="row">
                @if($errors->any())
                    {!! implode('', $errors->all('<div>:message</div>')) !!}
                @endif
                @if (Session::has('success'))
                    <div class="row">
                        <div class="alert alert-success" role="alert"><strong>{{ Session::get('success') }}</strong>
                        </div>
                    </div>
                @endif
            </div>
            <form >
                <div class="form-row align-items-center">

                    <div class="col-auto">

                        <label for="enterp">مؤسسة / مشروع</label>
                        <select name="enterp" id="enterp" class="custom-select custom-select-sm">
                            <option value="0">בחר</option>
                            @foreach($enterprise as $key1 => $item)
                                <optgroup label="{{$item['name']}}">
                                    @foreach($item['project'] as $key2 => $item2)
                                        <option value="{{$item['id']}}*{{$item2['id']}}"
                                                @if(request()->enterp ==($item['id']."*".$item2['id']) )selected @endif>*{{$item2['name']}}</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>


                    </div>



                    <div class="col-auto">
                        <label for="fdate">מתאריך</label>
                        <input type="date"  name="fdate" id="fdate" value="{{request()->fdate}}" class="form-control mb-2" >
                    </div>

                    <div class="col-auto">
                        <label for="tdate">עד תאריך</label>
                        <input type="date"  name="tdate" id="tdate" value="{{request()->tdate}}" class="form-control mb-2" >
                    </div>

                    <div class="col-auto">
                        <input type="submit" name="showData" value="عرض" class="btn btn-primary mb-2">
                    </div>

                </div>
            </form>
        </div>
    </div>


    @if(isset($array_t1) and count($array_t1)>1)
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">
                    <div class="table-responsive table-bordered">
                        <table class="table">
                        @foreach($array_t1 as $key1=>$item1)
                                <tr>
                            @foreach($item1 as $key2=>$item2)
                                    <td>{{$item2}}</td>
                            @endforeach
                                </tr>
                        @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    @endif


    @if(isset($array_t2) and count($array_t2)>1)
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">
                    <div class="table-responsive table-bordered">
                        <table class="table">
                            @foreach($array_t2 as $key1=>$item1)
                                <tr>
                                    @foreach($item1 as $key2=>$item2)
                                        <td>{{$item2}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    @endif


    @if(isset($array_t3) and count($array_t3)>1)
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">
                    <div class="table-responsive table-bordered">
                        <table class="table">
                            @foreach($array_t3 as $key1=>$item1)
                                <tr>
                                    @foreach($item1 as $key2=>$item2)
                                        <td>{{$item2}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($array_t4) and count($array_t4)>1)
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">
                    <div class="table-responsive table-bordered">
                        <table class="table">
                            @foreach($array_t4 as $key1=>$item1)
                                <tr>
                                    @foreach($item1 as $key2=>$item2)
                                        <td>{{$item2}}</td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </table>

                    </div>
                </div>
            </div>
        </div>
    @endif


    @if(isset($array_t5) and count($array_t5)>1)
        @foreach($array_t5 as $array_t5key=>$array_t5iem)
            <div class="row">
                <div class="card card-default">
                    <div class="card-header">Bordered Table</div>
                    <div class="card-body">
                        <div class="table-responsive table-bordered">
                            <table class="table">
                                @foreach($array_t5iem as $key1=>$item1)
                                    <tr>
                                        @foreach($item1 as $key2=>$item2)
                                            <td>{{$item2}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endif


{{--
    @if(isset($r1) and count($r1)>0)
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">
                    <div class="table-responsive table-bordered">
                        @include('layout.includes.displaytable',
                        ['tableBody' => $r1,
                         'tableHead' => array('עמותה','חובה','זכות','נטו')])
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($r2) and count($r2)>0)
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">
                    <div class="table-responsive table-bordered">
                        @include('layout.includes.displaytable',
                        ['tableBody' => $r2,
                         'tableHead' => array('עמותה','פרויקט','חובה','זכות','נטו')])
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($r3) and count($r3)>0)
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">
                    <div class="table-responsive table-bordered">
                        @include('layout.includes.displaytable',
                        ['tableBody' => $r3,
                         'tableHead' => array('עמותה','פרויקט','עיר','חובה','זכות','נטו')])

                    </div>
                </div>
            </div>
        </div>
    @endif

--}}

@endsection


@section('page-script')
    {{--  load file js from folder public --}}
@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}



