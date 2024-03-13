

    <form method="post" name="myformdivline" id="myformdivline" action="{{route('linedetail.storedivline',$bankslin['id_line'])}}">
        @csrf
        @method('post')
        <div class="col-xl-8">
            <div class="alert alert-info col-xl-6" role="alert" id="msginfo2">{{$msginfo}}</div>

            <div class="row">
                <div class="col-xl-4">

                    <label for="incmexpedivall">@if($bankslin['amountmandatory']=='0')
                            סוג הכנסה
                        @else
                            סוג הוצאה
                        @endif</label>
                    <select name="incmexpedivall" id="incmexpedivall"
                            class="form-control  custom-select custom-select-sm select-project inptrowdetl"   >
                        <option value="0">בחר</option>
                        @foreach($suppress_income as $item)
                            <option value="{{$item['id']}}" >{{$item['name']}} </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-xl-4 offset-md-4"><button class="btn btn-primary mb-2" type="button" name="btn_div" id="btn_div"
                                                          data-scum="@if($bankslin['amountmandatory']!=0){{$bankslin['amountmandatory']}}@else{{$bankslin['amountright']}}@endif">חלוקת סכום</button>
                    <button class="btn btn-primary mb-2" type="button" name="btn_div_save" id="btn_div_save">שמור חלוקה</button>
                </div>
            </div>




        </div>


        <div class="table-responsive   table-bordered col-xl-8">

            <table class="table">
                <thead>
                <tr>
                    <th>البلد/المشروع</th>
                    @foreach($allProject as $item)
                        <th> {{$item}} </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($allCity as $key1 => $item1)
                    <tr>
                        <th> {{$item1}} </th>

                        @foreach($allProject as $key2 => $item2)
                            <td> @if(isset($projectCity[$key2]['city'][$key1]))
                                    <div class="col-auto">
                                        <label class="sr-only" for="dcom*{{$key2}}*{{$key1}}">Name</label>
                                        <input type="number" name="dcom*{{$key2}}*{{$key1}}" id="dcom*{{$key2}}*{{$key1}}" value="0" min="0" class="form-control mb-2 allinptscom" >
                                    </div>
                                @endif
                            </td>
                        @endforeach

                    </tr>
                @endforeach

                </tbody>

            </table>
        </div>

    </form>

