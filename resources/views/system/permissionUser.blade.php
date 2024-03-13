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
        <div class="card-header">
            <h4 class="card-title">
                <a class="text-inherit" data-toggle="collapse" href="#addline" aria-expanded="true">
                    <small><em class="fa fa-plus text-primary mr-2"></em></small>
                    <span>اداره الاذونات</span>
                </a>
            </h4>

        </div>
        <div class="card-body collapse show" id="addline">
            <form method="post" name="myform" id="myform" action="#">
                @csrf
                <div class="col-md-4">
                    <select class="custom-select custom-select-lg mb-3" name="iduser" id="iduser" >
                        <option value="0" selected>اختيار مستخذم</option>
                        @foreach($users as $key => $item)
                            <option value="{{$item['id']}}">{{$item['username']}} -- {{$item['name']}} -- {{$item['description']}}</option>
                        @endforeach
                    </select>

                </div>


                <div class="form-row align-items-center">

                    <ul class="sidebar-nav1">

                    @foreach($shareMenu as $key_menu1 => $item_menu1)
                        <li class=" ">
                            <p data-toggle="collapse" class="collapsed" aria-expanded="true" >
                                <div class="form-check mb-2">
                                <input class="form-check-input allcheckbox" id="menu{{$item_menu1['id_menu']}}"
                                                                    type="checkbox"><label class="form-check-label "
                                                                                           for="menu{{$item_menu1['id_menu']}}">{{$item_menu1['name']}} - {{$item_menu1['id_menu']}}</label>
                                </div>


                            </p>
                            <ul class="sidebar-nav1 sidebar-subnav1 collapse show"  >


                                @if(count($item_menu1['program'])>0)
                                    @foreach($item_menu1['program'] as $key_prog1 => $item_prog1)
                                        <li class=" ">
                                            @php
                                                $arrParamRout = [];
                                                if($item_prog1['id_enter']!=null){
                                                    $arrParamRout[] = $item_prog1['id_enter'];
                                                }
                                                if($item_prog1['id_proj']!=null){
                                                    $arrParamRout[] = $item_prog1['id_proj'];
                                                }
                                                if($item_prog1['id_city']!=null){
                                                    $arrParamRout[] = $item_prog1['id_city'];
                                                }
                                            @endphp

                                            <p>
                                            <div class="form-check mb-2"><input class="form-check-input allcheckbox" id="prog{{$item_prog1['id_program']}}"
                                                                                type="checkbox"><label class="form-check-label"
                                                                                                       for="prog{{$item_prog1['id_program']}}">{{$item_prog1['description']}} - {{$item_prog1['id_program']}}</label>
                                            </div>
                                            </p>


                                        </li>
                                    @endforeach
                                @endif

                                @if(count($item_menu1['submenu'])>0)
                                    @foreach($item_menu1['submenu'] as $key_menu2 => $item_menu2)
                                        <li class=" ">
                                            <p data-toggle="collapse" class="collapsed"  aria-expanded="true">
                                            <div class="form-check mb-2"><input class="form-check-input allcheckbox" id="menu{{$item_menu2['id_menu']}}"
                                                                                type="checkbox"><label class="form-check-label"
                                                                                                       for="menu{{$item_menu2['id_menu']}}">{{$item_menu2['name']}} - {{$item_menu2['id_menu']}}</label>
                                            </div>
                                            </p>
                                            <ul class="sidebar-nav1 sidebar-subnav1 collapse show" >
                                                @if(count($item_menu2['program'])>0)
                                                    @foreach($item_menu2['program'] as $key_prog2 => $item_prog2)
                                                        <li class=" ">
                                                            <p>
                                                            <div class="form-check mb-2"><input class="form-check-input allcheckbox" id="prog{{$item_prog2['id_program']}}"
                                                                                                type="checkbox"><label class="form-check-label"
                                                                                                                       for="prog{{$item_prog2['id_program']}}">{{$item_prog2['description']}} - {{$item_prog2['id_program']}}</label>
                                                            </div>

                                                            </p>
                                                        </li>
                                                    @endforeach
                                                @endif

                                            </ul>
                                        </li>
                                    @endforeach
                                @endif


                            </ul>
                        </li>
                    @endforeach



                    </ul>
                </div>

                <input type="hidden" name="id_line" id="id_line" value="0">
            </form>

            <div>

            </div>

        </div>
    </div>


@endsection



@section('page-script')

    <script src="{{ asset('angle/vendor/sweetalert2/dist/sweetalert2.all.min.js') }}"></script><!-- SWEET ALERT-->
    @include( "scripts.system.permissionUser" )

@endsection





