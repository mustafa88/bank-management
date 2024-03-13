@extends('layout.mainangle')


@section('page-head')
    <style>
        .listol li {
            margin-bottom: 10px;
        }
    </style>
@endsection

@section('page-content')




    <div class="card card-default">
        <div class="card-header">انشاء تقرير بنكي</div>
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
            <form>
                <div class="form-row align-items-center">

                    <div class="col-auto">
                        <label for="bankid">בנק</label>
                        <select name="bankid" id="bankid" class="custom-select custom-select-sm">
                            <option value="0">בחר</option>
                            @foreach($banks as $item)
                                <option value="{{$item['id_bank']}}" @if(request()->bankid ==$item['id_bank']) selected @endif>
                                    {{$item['id_bank']}} - {{$item['enterprise']['name']}}
                                        @if(isset($item['projects']['name']))
                                        - {{$item['projects']['name']}}
                                        @endif
                                </option>
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

                    <div class="col-auto"><button class="btn btn-primary mb-2" type="submit">Submit</button></div>

                </div>
            </form>
        </div>
    </div>

    @if(isset($r1))
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">


                    <div class="table-responsive table-bordered">
                        @include('layout.includes.displaytable',
                        ['tableBody' => $r1,
                         'tableKeyBody' => array('enterprise.name','count_row','amountmandatory','amountright','total_neto'),
                         'tableHead' => array('עמותה','מס שורות','חובה','זכות','נטו')])
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($r7))
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">
                    <div class="table-responsive table-bordered">
                        @include('layout.includes.displaytable',
                       ['tableBody' => $r7,
                        'tableHead' => array('עמותה','פרויקט','חובה','זכות','נטו')])
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(isset($r8))
        <div class="row">
            <div class="card card-default">
                <div class="card-header">Bordered Table</div>
                <div class="card-body">
                    <div class="table-responsive table-bordered">
                        @include('layout.includes.displaytable',
                      ['tableBody' => $r8,
                       'tableHead' => array('עמותה','פרויקט','עיר','חובה','זכות','נטו')])
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card" role="tabpanel">
        <!-- Nav tabs-->
        <ul class="nav nav-tabs nav-fill" role="tablist">
            <li class="nav-item" role="presentation"><a class="nav-link active" href="#moneyin" aria-controls="moneyin" role="tab" data-toggle="tab" aria-selected="true">הכנסה</a></li>
            <li class="nav-item" role="presentation"><a class="nav-link" href="#moneyout" aria-controls="moneyout" role="tab" data-toggle="tab" aria-selected="false">הוצאה</a></li>
        </ul><!-- Tab panes-->
        <div class="tab-content p-0">
            <div class="tab-pane active" id="moneyin" role="tabpanel">
                <!-- START moneyin-->
                @if(isset($r2_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r2_in,
                        'tableKeyBody' => array('enterprise.name','month_year','amountright'),
                       'tableHead' => array('עמותה','חודש','זכות')])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r3_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r3_in,
                        'tableKeyBody' => array('enterprise.name','titletwo.ttwo_text','amountright'),
                       'tableHead' => array('עמותה','סוג תנועה','זכות')])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r4_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r4_in,
                        'tableKeyBody' => array('enterprise.name','titletwo.ttwo_text','month_year','amountright'),
                       'tableHead' => array('עמותה','סוג תנועה','חודש','זכות')])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r5_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r5_in,
                        'tableKeyBody' => array('enterp','ttwo_text','proj','amountright'),
                       'tableHead' => array('עמותה','סוג תנועה','פרויקט','זכות')])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r6_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r6_in,
                        'tableKeyBody' => array('enterp','ttwo_text','proj','city_name','amountright'),
                       'tableHead' => array('עמותה','סוג תנועה','פרויקט','עיר','זכות')])


                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r9_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r9_in,
                        'tableKeyBody' => array('enterp','ttwo_text','proj','city_name','incomename','amountright'),
                       'tableHead' => array('עמותה','סוג תנועה','פרויקט','עיר','סוג','זכות')])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r10_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r10_in,
                        'tableKeyBody' => array('enterp','ttwo_text','proj','city_name','incomename','month_year','amountright'),
                       'tableHead' => array('עמותה','סוג תנועה','פרויקט','עיר','סוג','חודש','זכות')])
                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                @if(isset($r11_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r11_in,
                        'tableKeyBody' => array('enterp','proj','name_camp','amountright'),
                       'tableHead' => array('עמותה','פרויקט','קמפיין','זכות')])


                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r12_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r12_in,
                        'tableKeyBody' => array('enterp','proj','month_year','name_camp','amountright'),
                       'tableHead' => array('עמותה','פרויקט','חודש','קמפיין','זכות')])


                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r13_in))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r13_out,
                        'tableKeyBody' => array('enterp','proj','city_name','name_camp','amountright'),
                       'tableHead' => array('עמותה','פרויקט','עיר','קמפיין','זכות')])

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="tab-pane" id="moneyout" role="tabpanel">
                <!-- START moneyout-->
                @if(isset($r2_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>עמותה</th>
                                            <th>חודש</th>
                                            <th>חובה</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($r2_out as $item)
                                            <tr>
                                                <td>{{ $item['enterprise']['name'] }}</td>
                                                <td>{{ $item['month_year'] }}</td>
                                                <td>{{ number_format($item['amountmandatory'],2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r3_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>עמותה</th>
                                            <th>סוג תנועה</th>
                                            <th>חובה</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($r3_out as $item)
                                            <tr>
                                                <td>{{ $item['enterprise']['name'] }}</td>
                                                <td>{{ $item['titletwo']['ttwo_text'] }}</td>
                                                <td>{{ number_format($item['amountmandatory'],2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r4_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>עמותה</th>
                                            <th>סוג תנועה</th>
                                            <th>חודש</th>
                                            <th>חובה</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($r4_out as $item)
                                            <tr>
                                                <td>{{ $item['enterprise']['name'] }}</td>
                                                <td>{{ $item['titletwo']['ttwo_text'] }}</td>
                                                <td>{{ $item['month_year'] }}</td>
                                                <td>{{ number_format($item['amountmandatory'],2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r5_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    <table class="table">
                                        <thead>
                                        <tr>
                                            <th>עמותה</th>
                                            <th>סוג תנועה</th>
                                            <th>פרויקט</th>
                                            <th>חובה</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($r5_out as $item)
                                            <tr>
                                                <td>{{ $item['enterp'] }}</td>
                                                <td>{{ $item['ttwo_text'] }}</td>
                                                <td>{{ $item['proj'] }}</td>
                                                <td>{{ number_format($item['amountmandatory'],2) }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r6_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r6_out,
                        'tableKeyBody' => array('enterp','ttwo_text','proj','city_name','amountmandatory'),
                       'tableHead' => array('עמותה','סוג תנועה','פרויקט','עיר','חובה')])


                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r9_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r9_out,
                        'tableKeyBody' => array('enterp','ttwo_text','proj','city_name','expensename','amountmandatory'),
                       'tableHead' => array('עמותה','סוג תנועה','פרויקט','עיר','ספק','חובה')])


                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r10_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r10_out,
                        'tableKeyBody' => array('enterp','ttwo_text','proj','city_name','expensename','month_year','amountmandatory'),
                       'tableHead' => array('עמותה','סוג תנועה','פרויקט','עיר','ספק','חודש','חובה')])


                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r11_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r11_out,
                        'tableKeyBody' => array('enterp','proj','name_camp','amountmandatory'),
                       'tableHead' => array('עמותה','פרויקט','קמפיין','חובה')])

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if(isset($r12_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r12_out,
                        'tableKeyBody' => array('enterp','proj','month_year','name_camp','amountmandatory'),
                       'tableHead' => array('עמותה','פרויקט','חודש','קמפיין','חובה')])


                                </div>
                            </div>
                        </div>
                    </div>
                @endif


                @if(isset($r13_out))
                    <div class="row">
                        <div class="card card-default">
                            <div class="card-header">Bordered Table</div>
                            <div class="card-body">
                                <div class="table-responsive table-bordered">
                                    @include('layout.includes.displaytable',
                      ['tableBody' => $r13_out,
                        'tableKeyBody' => array('enterp','proj','city_name','name_camp','amountmandatory'),
                       'tableHead' => array('עמותה','פרויקט','עיר','קמפיין','חובה')])


                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>




@endsection


@section('page-script')
    {{--  load file js from folder public --}}
@endsection

{{-- @include( "scripts.managetable.enterprise" ) --}}



