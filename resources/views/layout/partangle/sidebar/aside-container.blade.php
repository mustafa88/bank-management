<aside class="aside-container">
    <!-- START Sidebar (left)-->
    <div class="aside-inner">
        <nav class="sidebar" data-sidebar-anyclick-close="">
            <!-- START sidebar nav-->
            <ul class="sidebar-nav">
                <!-- START user info-->
                <li class="has-user-block">
                    <div class="collapse" id="user-block">
                        <div class="item user-block">
                            <!-- User picture-->
                            <div class="user-block-picture">
                                <div class="user-block-status"><img class="img-thumbnail rounded-circle" src="{{ asset('angle/img/user/00.jpg') }}" alt="Avatar" width="60" height="60">
                                    <div class="circle bg-success circle-lg"></div>
                                </div>
                            </div><!-- Name and Job-->
                            <div class="user-block-info"><span class="user-block-name">{{ Auth::user()->name }}</span>
                                <span class="user-block-role">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a class="nav-link" href="{{route('logout')}}"
                                           onclick="event.preventDefault();
                                           this.closest('form').submit();" title="تسحيل الخروج"><em class="icon-logout"></em> تسجيل الخروج</a>
                                    </form>
                                </span>
                            </div>
                        </div>
                    </div>
                </li><!-- END user info-->
                <!-- Iterates over all sidebar items-->
                <li class="nav-heading "><span data-localize="sidebar.heading.HEADER">القائمة الرئيسية</span></li>

                <li class=" active"><a href="{{ route('home') }}" title="דף ראשי">
                        <em class="fas fa-home"></em><span>דף ראשי</span>
                    </a></li>


                <li class=" "><a href="{{ route('export_import') }}" title="יבוא ויצאו קבצים">
                        <em class="fas icon-layers"></em>
                        <span>יבוא ויצאו קבצים</span>
                    </a>
                </li>

                @foreach($shareMenu as $key_menu1 => $item_menu1)

                    @if(in_array($item_menu1['id_menu'],  $shareMenuUser) or Auth::user()->role=='adminXXXXXX')

                   <li class=" "><a href="#{{$item_menu1['nameurl']}}" title="{{$item_menu1['name']}}" data-toggle="collapse"><em class="icon-grid"></em><span >{{$item_menu1['name']}} </span></a>
                        <ul class="sidebar-nav sidebar-subnav collapse" id="{{$item_menu1['nameurl']}}">
                            <li class="sidebar-subnav-header">{{$item_menu1['name']}}</li>


                            @if(count($item_menu1['program'])>0)
                                @foreach($item_menu1['program'] as $key_prog1 => $item_prog1)
                                    {{--
                                    @if(in_array($item_prog1['id_program'],  $shareProgramUser) or Auth::user()->role=='adminXXXXXX')
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

                                        <li class="@if(Route::currentRouteName()==$item_prog1['routename'])active @endif ">


                                            <a href="{{route($item_prog1['routename'],$arrParamRout)}}" title="{{$item_prog1['description']}}">
                                                <span>{{$item_prog1['description']}}</span>
                                            </a>
                                        </li>
                                    @endif
                                    --}}


                                        @if(in_array($item_prog1['id_program'],  $shareProgramUser) or Auth::user()->role=='adminXXXXXX')
                                            <li
                                                class="
                                                                @if(Route::currentRouteName()==$item_prog1['routename'] and
                                                                    request()->route()->parameters==json_decode($item_prog1['param'], true) )
                                                                    active
                                                                @endif ">

                                                <a href="{{route($item_prog1['routename'],json_decode($item_prog1['param'], true) )}}" title="{{$item_prog1['description']}}">
                                                                    <span>{{$item_prog1['description']}}



                                                                    </span>
                                                </a>
                                            </li>
                                        @endif





                                @endforeach
                            @endif

                            @if(count($item_menu1['submenu'])>0)
                                @foreach($item_menu1['submenu'] as $key_menu2 => $item_menu2)

                                    @if(in_array($item_menu2['id_menu'],  $shareMenuUser) or Auth::user()->role=='adminXXXXXX')
                                        <li class=" "><a href="#{{$item_menu2['nameurl']}}" title="{{$item_menu2['name']}}" data-toggle="collapse"><em class="fas fa-angle-left"></em><span >{{$item_menu2['name']}}</span></a>
                                            <ul class="sidebar-nav sidebar-subnav collapse" id="{{$item_menu2['nameurl']}}">
                                                <li class="sidebar-subnav-header">{{$item_menu2['name']}}</li>


                                                @if(count($item_menu2['program'])>0)
                                                    @foreach($item_menu2['program'] as $key_prog2 => $item_prog2)
                                                        @if(in_array($item_prog2['id_program'],  $shareProgramUser) or Auth::user()->role=='adminXXXXXX')
                                                            <li
                                                                class="
                                                                @if(Route::currentRouteName()==$item_prog2['routename'] and
                                                                    request()->route()->parameters==json_decode($item_prog2['param'], true) )
                                                                    active
                                                                @endif ">

                                                                <a href="{{route($item_prog2['routename'],json_decode($item_prog2['param'], true) )}}" title="{{$item_prog2['description']}}">
                                                                    <span>{{$item_prog2['description']}}



                                                                    </span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                @endif

                                                {{--SUB MENU LEVEL 3 - NOT ACTIVE לא פעיל לא משתמשים
                                                @if(count($item_menu2['submenu'])>0)
                                                    @foreach($item_menu2['submenu'] as $key_menu3 => $item_menu3)
                                                        <li class=" "><a href="#{{$item_menu3['nameurl']}}" title="{{$item_menu3['name']}}" data-toggle="collapse"><em class="fas fa-angle-double-left"></em><span >{{$item_menu3['name']}}</span></a>
                                                            <ul class="sidebar-nav sidebar-subnav collapse" id="{{$item_menu3['nameurl']}}">
                                                                <li class="sidebar-subnav-header">{{$item_menu3['name']}}</li>
                                                                --}}{{-- <li class=" "><a href="{{route('register')}}" title="تسجيل مستخدم"><span>1تسجيل مستخدم جديد</span></a>
                                                                 </li>--}}{{--
                                                            </ul>
                                                        </li>
                                                    @endforeach
                                                @endif--}}



                                            </ul>
                                        </li>
                                    @endif
                                @endforeach
                            @endif


                        </ul>
                    </li>


                    @endif

                @endforeach




                    {{--<li class=" "><a href="#manageusers" title="اداره مستخدمين" data-toggle="collapse"><em class="icon-grid"></em><span >اداره مستخدمين</span></a>
                        <ul class="sidebar-nav sidebar-subnav collapse" id="manageusers">
                            <li class="sidebar-subnav-header">اداره مستخدمين</li>
                            <li class=" "><a href="{{route('register')}}" title="تسجيل مستخدم"><span>تسجيل مستخدم جديد</span></a></li>
                        </ul>
                    </li>--}}


                    {{--<li class=" "><a href="#dashboard" title="Tables" data-toggle="collapse"><em class="icon-grid"></em><span>dashboard</span></a>
                        <ul class="sidebar-nav sidebar-subnav collapse" id="dashboard">
                            <li class="sidebar-subnav-header">dashboard</li>
                            <li class=" "><a href="{{route('dashboard.banklines')}}" title="שורות בנק"><span>שורות בנק</span></a></li>

                            --}}{{--@foreach($share_enterprise as $key1 => $item)

                                <li class=" "><a href="#dashbalance_{{$key1}}" title="יתרה {{$item['name']}}" data-toggle="collapse"><em class="fas fa-angle-left"></em><span>יתרה {{$item['name']}}</span></a>
                                    <ul class="sidebar-nav sidebar-subnav collapse" id="dashbalance_{{$key1}}">
                                        <li class="sidebar-subnav-header">יתרה {{$item['name']}}</li>
                                        @foreach($item['project'] as $key2 => $item2)
                                            <li class=" ">
                                                <a href="{{route('dashboard.balance', $item2['id'] . '?year=') . now()->format('Y')}}" title="{{$item2['name']}}"><span>{{$item2['name']}}</span></a>
                                            </li>
                                        @endforeach

                                    </ul>
                                </li>

                            @endforeach--}}{{--


                        </ul>
                    </li>--}}




                    {{--<li class=" "><a href="#tablesmanage" title="Tables" data-toggle="collapse"><em class="icon-grid"></em><span >הגדרות מערכת</span></a>
                        <ul class="sidebar-nav sidebar-subnav collapse" id="tablesmanage">
                            <li class="sidebar-subnav-header">הגדרות מערכת</li>
                            <li class=" "><a href="{{route('banks.show')}}" title="قائمة البنوك"><span>قائمة البنوك</span></a></li>
                            <li class=" "><a href="{{route('table.title.show')}}" title="טבלת כותרת ראשית"><span>כותרת ראשית</span></a></li>
                            <li class=" "><a href="{{route('table.enterprise.show')}}" title="מבנה עמונה"><span>جدول الجمعيات</span></a></li>
                            <li class=" "><a href="{{route('table.city.show')}}" title="جدول البلدان"><span>جدول البلدان</span></a></li>
                            <li class=" "><a href="{{route('table.expense_income.show')}}" title="جدول مصروفات ومدخولات"><span>جدول مصروفات ومدخولات</span></a></li>
                            <li class=" "><a href="{{route('donateType.show')}}" title="جدول التبرعات العينية"><span>جدول التبرعات العينية</span></a></li>
                        </ul>
                    </li>--}}


                {{--<li class=" "><a href="#reports" title="Tables" data-toggle="collapse"><em class="icon-grid"></em><span >דוחות</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="reports">
                        <li class="sidebar-subnav-header">דוחות</li>
                        <li class=" "><a href="{{route('reports.structure')}}" title="هيكل الجمعية"><span>هيكل الجمعية</span></a></li>
                        <li class=" "><a href="{{route('reports.banksearch.new')}}" title="تقارير بنكية"><span>تقارير بنكية</span></a></li>
                        <li class=" "><a href="{{route('reports.projsearch')}}" title="تقارير مشاريع"><span>تقارير مشاريع</span></a></li>
                        <li class=" "><a href="{{route('reports.finalall')}}" title="تقرير شامل"><span>تقرير شامل</span></a></li>
                        <li class=" "><a href="{{route('usb_report.show')}}" title="تقرير مدخولات / مصروغات"><span>تقرير مدخولات / مصروغات</span></a></li>
                        <li class=" "><a href="{{route('adahi_report.show')}}" title="تقرير مشروع الاضاحي"><span>تقرير مشروع الاضاحي</span></a></li>
                        <li class=" "><a href="#" title="تقارير تبرعات عينية"><span>تقارير تبرعات عينية</span></a></li>
                    </ul>
                </li>--}}


                {{--<li class=" "><a href="#li_donate" title="Tables" data-toggle="collapse"><em class="icon-grid"></em><span >تبرعات عينية</span></a>

                <ul class="sidebar-nav sidebar-subnav collapse" id="li_donate">
                    <li class="sidebar-subnav-header">تبرعات عينية</li>

                        @foreach($share_enterprise as $key1 => $item)
                        <li class=" "><a href="#ul_donate_{{$key1}}" title="{{$item['name']}}" data-toggle="collapse"><em class="fas fa-angle-left"></em><span>{{$item['name']}}</span></a>
                            <ul class="sidebar-nav sidebar-subnav collapse" id="ul_donate_{{$key1}}">
                                <li class="sidebar-subnav-header">{{$item['name']}}</li>
                                @foreach($item['project'] as $key2 => $item2)
                                    @foreach($item2['city'] as $key3 => $item3)
                                        <li class=" ">
                                            <a href="{{route('mainDonate.show',['id_entrep'=>$item['id'],'id_proj'=>$item2['id'],'id_city'=>$item3['city_id']])}}"
                                               title="{{$item3['city_name']}}" aria-expanded="true" >
                                                <span>{{$item2['name']}} => {{$item3['city_name']}}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </li>
                        @endforeach
                    </ul>
                </li>--}}


                {{--<li class=" "><a href="#li_usbin" title="Tables" data-toggle="collapse"><em class="icon-grid"></em><span >سجل المدخولات</span></a>

                    <ul class="sidebar-nav sidebar-subnav collapse" id="li_usbin">
                        <li class="sidebar-subnav-header">سجل المدخولات</li>


                        <li class=" ">
                            <a href="{{route('usb_income_entrep.show',['id_entrep'=>'1','id_city'=>'2'])}}"
                               title="عطاء الطيبة" aria-expanded="true" >
                                <span>عطاء --> الطيبة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('usb_income_entrep.show',['id_entrep'=>'1','id_city'=>'3'])}}"
                               title="عطاء قلنسوة" aria-expanded="true" >
                                <span>عطاء --> قلنسوة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('usb_income_entrep.show',['id_entrep'=>'1','id_city'=>'4'])}}"
                               title="عطاء الطيرة" aria-expanded="true" >
                                <span>عطاء --> الطيرة</span>
                            </a>
                        </li>


                        <li class=" ">
                            <a href="{{route('usb_income_entrep.show',['id_entrep'=>'2','id_city'=>'2'])}}"
                               title="الراية الطيبة" aria-expanded="true" >
                                <span>الراية --> الطيبة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('usb_income_entrep.show',['id_entrep'=>'2','id_city'=>'3'])}}"
                               title="الراية قلنسوة" aria-expanded="true" >
                                <span>الراية --> قلنسوة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('usb_income_entrep.show',['id_entrep'=>'2','id_city'=>'4'])}}"
                               title="الراية الطيرة" aria-expanded="true" >
                                <span>الراية --> الطيرة</span>
                            </a>
                        </li>

                    </ul>
                </li>--}}


                {{--<li class=" "><a href="#li_usbex" title="Tables" data-toggle="collapse"><em class="icon-grid"></em><span >سجل المصروفات</span></a>

                    <ul class="sidebar-nav sidebar-subnav collapse" id="li_usbex">
                        <li class="sidebar-subnav-header">سجل المصروفات</li>

                        <li class=" ">
                            <a href="{{route('usb_expense_entrep.show',['id_entrep'=>'1','id_city'=>'2'])}}"
                               title="الطيبة" aria-expanded="true" >
                                <span>عطاء -->  الطيبة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('usb_expense_entrep.show',['id_entrep'=>'1','id_city'=>'3'])}}"
                               title="الطيبة" aria-expanded="true" >
                                <span>عطاء -->  قلنسوة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('usb_expense_entrep.show',['id_entrep'=>'1','id_city'=>'4'])}}"
                               title="الطيبة" aria-expanded="true" >
                                <span>عطاء -->  الطيرة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('usb_expense_entrep.show',['id_entrep'=>'2','id_city'=>'2'])}}"
                               title="الراية الطيبة" aria-expanded="true" >
                                <span>الراية --> الطيبة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('usb_expense_entrep.show',['id_entrep'=>'2','id_city'=>'3'])}}"
                               title="الراية قلنسوة" aria-expanded="true" >
                                <span>الراية --> قلنسوة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('usb_expense_entrep.show',['id_entrep'=>'2','id_city'=>'4'])}}"
                               title="الراية الطيرة" aria-expanded="true" >
                                <span>الراية --> الطيرة</span>
                            </a>
                        </li>

                    </ul>
                </li>--}}

                {{--<li class=" "><a href="#li_adahi" title="Tables" data-toggle="collapse"><em class="icon-grid"></em><span >مشروع الاضاحي</span></a>

                    <ul class="sidebar-nav sidebar-subnav collapse" id="li_adahi">
                        <li class="sidebar-subnav-header">مشروع الاضاحي</li>

                        <li class=" ">
                            <a href="{{route('adahi.show',['id_city'=>'2'])}}"
                               title="الطيبة" aria-expanded="true" >
                                <span>عطاء -->  الطيبة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('adahi.show',['id_city'=>'3'])}}"
                               title="الطيبة" aria-expanded="true" >
                                <span>عطاء -->  قلنسوة</span>
                            </a>
                        </li>

                        <li class=" ">
                            <a href="{{route('adahi.show',['id_city'=>'4'])}}"
                               title="الطيبة" aria-expanded="true" >
                                <span>عطاء -->  الطيرة</span>
                            </a>
                        </li>

                    </ul>
                </li>--}}


                {{--<li class=" "><a href="#banks" title="Tables" data-toggle="collapse"><em class="icon-grid"></em><span >בנקים</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="banks">
                        <li class="sidebar-subnav-header">בנקים</li>


                        <li class=" "><a href="{{route('banks.mainLoadCsv')}}" title="העלאת קובץ אקסל"><span>העלאת קובץ אקסל</span></a></li>


                        <li class=" "><a href="#bankmoves" title="תנועות בחשבון" data-toggle="collapse"><em class="fas fa-angle-left"></em><span>תנועות בחשבון</span></a>

                            <ul class="sidebar-nav sidebar-subnav collapse" id="bankmoves">
                                <li class="sidebar-subnav-header">תנועות בחשבון</li>

                                @foreach($share_listbank as $item)
                                    <li class=" ">
                                        <a href="{{route('linebanks.show',$item['id_bank'])}}" title="{{$item['enterprise']['name']}}">
                                            <span>{{$item['id_bank']}} - {{$item['enterprise']['name']}}
                                                @if(isset($item['projects']['name'])){{$item['projects']['name']}}@endif</span>
                                        </a>
                                    </li>
                                @endforeach

                            </ul>
                        </li>

                    </ul>
                </li> --}}



                {{--
                <li class=" "><a href="#dashboard" title="Dashboard" data-toggle="collapse">
                        <div class="float-right badge badge-success">3</div><em class="icon-speedometer"></em><span data-localize="sidebar.nav.DASHBOARD">Dashboard</span>
                    </a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="dashboard">
                        <li class="sidebar-subnav-header">Dashboard</li>
                        <li class=" "><a href="dashboard.html" title="Dashboard v1"><span>Dashboard v1</span></a></li>
                        <li class=" "><a href="dashboard_v2.html" title="Dashboard v2"><span>Dashboard v2</span></a></li>
                        <li class=" "><a href="dashboard_v3.html" title="Dashboard v3"><span>Dashboard v3</span></a></li>
                    </ul>
                </li>
                <li class=" "><a href="widgets.html" title="Widgets">
                        <div class="float-right badge badge-success">30</div><em class="icon-grid"></em><span data-localize="sidebar.nav.WIDGETS">Widgets</span>
                    </a></li>
                <li class=" "><a href="#layout" title="Layouts" data-toggle="collapse"><em class="icon-layers"></em><span>Layouts</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="layout">
                        <li class="sidebar-subnav-header">Layouts</li>
                        <li class=" "><a href="dashboard_h.html" title="Horizontal"><span>Horizontal</span></a></li>
                    </ul>
                </li>
                <li class="nav-heading "><span data-localize="sidebar.heading.COMPONENTS">Components</span></li>
                <li class=" "><a href="#elements" title="Elements" data-toggle="collapse"><em class="icon-chemistry"></em><span data-localize="sidebar.nav.element.ELEMENTS">Elements</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="elements">
                        <li class="sidebar-subnav-header">Elements</li>
                        <li class=" "><a href="buttons.html" title="Buttons"><span data-localize="sidebar.nav.element.BUTTON">Buttons</span></a></li>
                        <li class=" "><a href="notifications.html" title="Notifications"><span data-localize="sidebar.nav.element.NOTIFICATION">Notifications</span></a></li>
                        <li class=" "><a href="sweetalert.html" title="Sweet Alert"><span>Sweet Alert</span></a></li>
                        <li class=" "><a href="carousel.html" title="Carousel"><span data-localize="sidebar.nav.element.INTERACTION">Carousel</span></a></li>
                        <li class=" "><a href="spinners.html" title="Spinners"><span data-localize="sidebar.nav.element.SPINNER">Spinners</span></a></li>
                        <li class=" "><a href="dropdown-animations.html" title="Dropdown"><span data-localize="sidebar.nav.element.DROPDOWN">Dropdown</span></a></li>
                        <li class=" "><a href="nestable.html" title="Nestable"><span>Nestable</span></a></li>
                        <li class=" "><a href="sortable.html" title="Sortable"><span>Sortable</span></a></li>
                        <li class=" "><a href="cards.html" title="Cards"><span data-localize="sidebar.nav.element.CARDS">Cards</span></a></li>
                        <li class=" "><a href="portlets.html" title="Portlets"><span data-localize="sidebar.nav.element.PORTLET">Portlets</span></a></li>
                        <li class=" "><a href="grid.html" title="Grid"><span data-localize="sidebar.nav.element.GRID">Grid</span></a></li>
                        <li class=" "><a href="grid-masonry.html" title="Grid Masonry"><span data-localize="sidebar.nav.element.GRID_MASONRY">Grid Masonry</span></a></li>
                        <li class=" "><a href="typo.html" title="Typography"><span data-localize="sidebar.nav.element.TYPO">Typography</span></a></li>
                        <li class=" "><a href="icons-font.html" title="Font Icons">
                                <div class="float-right badge badge-success">+400</div><span data-localize="sidebar.nav.element.FONT_ICON">Font Icons</span>
                            </a></li>
                        <li class=" "><a href="icons-weather.html" title="Weather Icons">
                                <div class="float-right badge badge-success">+100</div><span data-localize="sidebar.nav.element.WEATHER_ICON">Weather Icons</span>
                            </a></li>
                        <li class=" "><a href="colors.html" title="Colors"><span data-localize="sidebar.nav.element.COLOR">Colors</span></a></li>
                    </ul>
                </li>
                <li class=" "><a href="#forms" title="Forms" data-toggle="collapse"><em class="icon-note"></em><span data-localize="sidebar.nav.form.FORM">Forms</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="forms">
                        <li class="sidebar-subnav-header">Forms</li>
                        <li class=" "><a href="form-standard.html" title="Standard"><span data-localize="sidebar.nav.form.STANDARD">Standard</span></a></li>
                        <li class=" "><a href="form-extended.html" title="Extended"><span data-localize="sidebar.nav.form.EXTENDED">Extended</span></a></li>
                        <li class=" "><a href="form-validation.html" title="Validation"><span data-localize="sidebar.nav.form.VALIDATION">Validation</span></a></li>
                        <li class=" "><a href="form-wizard.html" title="Wizard"><span>Wizard</span></a></li>
                        <li class=" "><a href="form-upload.html" title="Upload"><span>Upload</span></a></li>
                        <li class=" "><a href="form-xeditable.html" title="xEditable"><span>xEditable</span></a></li>
                        <li class=" "><a href="form-imagecrop.html" title="Cropper"><span>Cropper</span></a></li>
                    </ul>
                </li>
                <li class=" "><a href="#charts" title="Charts" data-toggle="collapse"><em class="icon-graph"></em><span data-localize="sidebar.nav.chart.CHART">Charts</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="charts">
                        <li class="sidebar-subnav-header">Charts</li>
                        <li class=" "><a href="chart-flot.html" title="Flot"><span data-localize="sidebar.nav.chart.FLOT">Flot</span></a></li>
                        <li class=" "><a href="chart-radial.html" title="Radial"><span data-localize="sidebar.nav.chart.RADIAL">Radial</span></a></li>
                        <li class=" "><a href="chart-js.html" title="Chart JS"><span>Chart JS</span></a></li>
                        <li class=" "><a href="chart-rickshaw.html" title="Rickshaw"><span>Rickshaw</span></a></li>
                        <li class=" "><a href="chart-morris.html" title="MorrisJS"><span>MorrisJS</span></a></li>
                        <li class=" "><a href="chart-chartist.html" title="Chartist"><span>Chartist</span></a></li>
                    </ul>
                </li>
                <li class=" "><a href="#tables" title="Tables" data-toggle="collapse"><em class="icon-grid"></em><span data-localize="sidebar.nav.table.TABLE">Tables</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="tables">
                        <li class="sidebar-subnav-header">Tables</li>
                        <li class=" "><a href="table-standard.html" title="Standard"><span data-localize="sidebar.nav.table.STANDARD">Standard</span></a></li>
                        <li class=" "><a href="table-extended.html" title="Extended"><span data-localize="sidebar.nav.table.EXTENDED">Extended</span></a></li>
                        <li class=" "><a href="table-datatable.html" title="DataTables"><span data-localize="sidebar.nav.table.DATATABLE">DataTables</span></a></li>
                        <li class=" "><a href="table-bootgrid.html" title="BootGrid"><span>BootGrid</span></a></li>
                    </ul>
                </li>
                <li class=" "><a href="#maps" title="Maps" data-toggle="collapse"><em class="icon-map"></em><span data-localize="sidebar.nav.map.MAP">Maps</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="maps">
                        <li class="sidebar-subnav-header">Maps</li>
                        <li class=" "><a href="maps-google.html" title="Google Maps"><span data-localize="sidebar.nav.map.GOOGLE">Google Maps</span></a></li>
                        <li class=" "><a href="maps-vector.html" title="Vector Maps"><span data-localize="sidebar.nav.map.VECTOR">Vector Maps</span></a></li>
                    </ul>
                </li>
                <li class="nav-heading "><span data-localize="sidebar.heading.MORE">More</span></li>
                <li class=" "><a href="#pages" title="Pages" data-toggle="collapse"><em class="icon-doc"></em><span data-localize="sidebar.nav.pages.PAGES">Pages</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="pages">
                        <li class="sidebar-subnav-header">Pages</li>
                        <li class=" "><a href="login.html" title="Login"><span data-localize="sidebar.nav.pages.LOGIN">Login</span></a></li>
                        <li class=" "><a href="register.html" title="Sign up"><span data-localize="sidebar.nav.pages.REGISTER">Sign up</span></a></li>
                        <li class=" "><a href="recover.html" title="Recover Password"><span data-localize="sidebar.nav.pages.RECOVER">Recover Password</span></a></li>
                        <li class=" "><a href="lock.html" title="Lock"><span data-localize="sidebar.nav.pages.LOCK">Lock</span></a></li>
                        <li class=" "><a href="template.html" title="Starter Template"><span data-localize="sidebar.nav.pages.STARTER">Starter Template</span></a></li>
                        <li class=" "><a href="404.html" title="404"><span>404</span></a></li>
                        <li class=" "><a href="500.html" title="500"><span>500</span></a></li>
                        <li class=" "><a href="maintenance.html" title="Maintenance"><span>Maintenance</span></a></li>
                    </ul>
                </li>
                <li class=" "><a href="#extras" title="Extras" data-toggle="collapse"><em class="icon-cup"></em><span data-localize="sidebar.nav.extra.EXTRA">Extras</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="extras">
                        <li class="sidebar-subnav-header">Extras</li>
                        <li class=" "><a href="#blog" title="Blog" data-toggle="collapse"><em class="fas fa-angle-right"></em><span>Blog</span></a>
                            <ul class="sidebar-nav sidebar-subnav collapse" id="blog">
                                <li class="sidebar-subnav-header">Blog</li>
                                <li class=" "><a href="blog.html" title="List"><span>List</span></a></li>
                                <li class=" "><a href="blog-post.html" title="Post"><span>Post</span></a></li>
                                <li class=" "><a href="blog-articles.html" title="Articles"><span>Articles</span></a></li>
                                <li class=" "><a href="blog-article-view.html" title="Article View"><span>Article View</span></a></li>
                            </ul>
                        </li>
                        <li class=" "><a href="#ecommerce" title="eCommerce" data-toggle="collapse"><em class="fas fa-angle-right"></em><span>eCommerce</span></a>
                            <ul class="sidebar-nav sidebar-subnav collapse" id="ecommerce">
                                <li class="sidebar-subnav-header">eCommerce</li>
                                <li class=" "><a href="ecommerce-orders.html" title="Orders">
                                        <div class="float-right badge badge-success">10</div><span>Orders</span>
                                    </a></li>
                                <li class=" "><a href="ecommerce-order-view.html" title="Order View"><span>Order View</span></a></li>
                                <li class=" "><a href="ecommerce-products.html" title="Products"><span>Products</span></a></li>
                                <li class=" "><a href="ecommerce-product-view.html" title="Product View"><span>Product View</span></a></li>
                                <li class=" "><a href="ecommerce-checkout.html" title="Checkout"><span>Checkout</span></a></li>
                            </ul>
                        </li>
                        <li class=" "><a href="#forum" title="Forum" data-toggle="collapse"><em class="fas fa-angle-right"></em><span>Forum</span></a>
                            <ul class="sidebar-nav sidebar-subnav collapse" id="forum">
                                <li class="sidebar-subnav-header">Forum</li>
                                <li class=" "><a href="forum-categories.html" title="Categories"><span>Categories</span></a></li>
                                <li class=" "><a href="forum-topics.html" title="Topics"><span>Topics</span></a></li>
                                <li class=" "><a href="forum-discussion.html" title="Discussion"><span>Discussion</span></a></li>
                            </ul>
                        </li>
                        <li class=" "><a href="contacts.html" title="Contacts"><span>Contacts</span></a></li>
                        <li class=" "><a href="contact-details.html" title="Contact details"><span>Contact details</span></a></li>
                        <li class=" "><a href="projects.html" title="Projects"><span>Projects</span></a></li>
                        <li class=" "><a href="project-details.html" title="Projects details"><span>Projects details</span></a></li>
                        <li class=" "><a href="team-viewer.html" title="Team viewer"><span>Team viewer</span></a></li>
                        <li class=" "><a href="social-board.html" title="Social board"><span>Social board</span></a></li>
                        <li class=" "><a href="vote-links.html" title="Vote links"><span>Vote links</span></a></li>
                        <li class=" "><a href="bug-tracker.html" title="Bug tracker"><span>Bug tracker</span></a></li>
                        <li class=" "><a href="faq.html" title="FAQ"><span>FAQ</span></a></li>
                        <li class=" "><a href="help-center.html" title="Help Center"><span>Help Center</span></a></li>
                        <li class=" "><a href="followers.html" title="Followers"><span>Followers</span></a></li>
                        <li class=" "><a href="settings.html" title="Settings"><span>Settings</span></a></li>
                        <li class=" "><a href="plans.html" title="Plans"><span>Plans</span></a></li>
                        <li class=" "><a href="file-manager.html" title="File manager"><span>File manager</span></a></li>
                        <li class=" "><a href="mailbox.html" title="Mailbox"><span data-localize="sidebar.nav.extra.MAILBOX">Mailbox</span></a></li>
                        <li class=" "><a href="timeline.html" title="Timeline"><span data-localize="sidebar.nav.extra.TIMELINE">Timeline</span></a></li>
                        <li class=" "><a href="calendar.html" title="Calendar"><span data-localize="sidebar.nav.extra.CALENDAR">Calendar</span></a></li>
                        <li class=" "><a href="invoice.html" title="Invoice"><span data-localize="sidebar.nav.extra.INVOICE">Invoice</span></a></li>
                        <li class=" "><a href="search.html" title="Search"><span data-localize="sidebar.nav.extra.SEARCH">Search</span></a></li>
                        <li class=" "><a href="todo.html" title="Todo List"><span data-localize="sidebar.nav.extra.TODO">Todo List</span></a></li>
                        <li class=" "><a href="profile.html" title="Profile"><span data-localize="sidebar.nav.extra.PROFILE">Profile</span></a></li>
                    </ul>
                </li>
                <li class=" "><a href="#multilevel" title="Multilevel" data-toggle="collapse"><em class="far fa-folder-open"></em><span>Multilevel</span></a>
                    <ul class="sidebar-nav sidebar-subnav collapse" id="multilevel">
                        <li class="sidebar-subnav-header">Multilevel</li>
                        <li class=" "><a href="#level1" title="Level 1" data-toggle="collapse"><span>Level 1</span></a>
                            <ul class="sidebar-nav sidebar-subnav collapse" id="level1">
                                <li class="sidebar-subnav-header">Level 1</li>
                                <li class=" "><a href="multilevel-1.html" title="Level1 Item"><span>Level1 Item</span></a></li>
                                <li class=" "><a href="#level2" title="Level 2" data-toggle="collapse"><span>Level 2</span></a>
                                    <ul class="sidebar-nav sidebar-subnav collapse" id="level2">
                                        <li class="sidebar-subnav-header">Level 2</li>
                                        <li class=" "><a href="#level3" title="Level 3" data-toggle="collapse"><span>Level 3</span></a>
                                            <ul class="sidebar-nav sidebar-subnav collapse" id="level3">
                                                <li class="sidebar-subnav-header">Level 3</li>
                                                <li class=" "><a href="multilevel-3.html" title="Level3 Item"><span>Level3 Item</span></a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                --}}
            </ul><!-- END sidebar nav-->
        </nav>
    </div><!-- END Sidebar (left)-->
</aside>

