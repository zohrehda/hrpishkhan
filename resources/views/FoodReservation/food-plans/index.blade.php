@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')


    <section class="food-plan">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text cursor-pointer" id="date">choose week</span>
                            </div>
                            <input type="text" id="inputDate" class="form-control" name="week-date"
                                   placeholder="DateTimePicker Range Selector With Multiple Months" aria-label="date"
                                   aria-describedby="date">
                        </div>
                    </div>
                </div>

                @if(isset($firstDay ,$lastDay))

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="input-group" id="input-expire">
                                <div class="input-group-prepend">
                                    <span class="input-group-text cursor-pointer" id="expire">expire date</span>
                                </div>
                                <input type="text" id="inputExpire" class="form-control" form="formPlan" name="expire"
                                       placeholder="DateTimePicker Range Selector With Multiple Months"
                                       aria-label="expire"
                                       aria-describedby="expire" {{($plan_status=='expired')?'disabled':''  }}>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="col-md-6">
                    <select class="custom-select" onchange="location = this.value;">
                        <option disabled selected> history</option>
                        @foreach($plan_list as $v)
                            <option
                                {{(request()->route()->parameter('week_date')==str_replace('/','|',$v->plan_date_range ))?'selected':''  }}
                                value="{{route('FoodReservation.foodPlans.index',str_replace('/','|',$v->plan_date_range ) )}}">
                                {{$v->plan_date_range}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <br>
            <div class="row">
                <div class="col-md-12">
                    <div class="alert  alert-primary">
                        <div class="row">
                            <div class="col-md-6 col-6 status-bar ">
                                <span class="pr-1">status: </span>
                                @if($plan_status=='expired')

                                    <span class=" text-warning"><b>expired</b> </span>
                                @elseif($plan_status=='created')

                                    <span class=" text-success"><b>created</b> </span>
                                @else
                                    <span class=" text-secondary"><b>empty</b> </span>


                                @endif
                            </div>
                            @if($plan_status=='created')
                                <div class="col-md-6 col-6">
                                    <a class="btn btn-danger btn-link text-light"
                                       href="{{route('FoodReservation.foodPlans.delete',[$firstDay ,$lastDay])}}">
                                        delete food plan
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <br>

            @if(isset($firstDay ,$lastDay))
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <b class="text-primary"> weekly food plan</b>
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col">DAY</th>
                                        <th scope="col">FOODS</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $fd=clone $firstDay; $ls=clone $lastDay ; $diff=  $firstDay->diffDays($lastDay);
                                    @endphp
                                    <form method="post" id="formPlan"
                                          action="{{route('FoodReservation.foodPlans.store')}}">
                                        {{csrf_field()}}
                                        @for($i=0 ; $i<=$diff ;$i++  )
                                            <tr>
                                                <td>{{($i==0)?$fd->format('Y-m-d'):$fd->addDay()->format('Y-m-d')   }}
                                                    {{  $fd->formatWord('l')  }}
                                                </td>
                                                <td>
                                                    <select class="custom-select food-select" id="" multiple="multiple"
                                                            {{($plan_status=='expired')?'disabled':''  }}     style="width: 100%"
                                                            name="{{$fd->format('Y-m-d')}}[]">
                                                        <option value="0">بدون غذا</option>
                                                        @foreach($foods as $food)
                                                            <option value="{{$food->id}}"
                                                                {{      (   in_array($food->id, getFoodDay($fd->format('Y-m-d')))   )?"selected":'' }}

                                                            >{{$food->title}}</option>

                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endfor
                                    </form>
                                    </tbody>
                                </table>
                                <input type="submit" form="formPlan" class="btn btn-primary submit-btn" value="submit"
                                    {{($plan_status=='expired')?'disabled':''  }} >
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            @endif
        </div>
    </section>



    <script type="text/javascript">

        var today = new Date()

        var ld =
            {!! isset($lastDay)?json_encode($lastDay):json_encode('') !!}

        var fd =
            {!! isset($firstDay)?json_encode($firstDay):json_encode('') !!}

        var firstDayDate = new Date(Object.values(fd)[0]);

        var lastDayDate = new Date(Object.values(ld)[0]);
        var ex = new Date(firstDayDate);
        ex.setDate(ex.getDate() - 3);
        var expire =
            {!! isset($expire)?json_encode($expire):json_encode(false) !!}
        var expireDate = (expire != false) ? new Date(Object.values(expire)[0]) : ex

        $('#date').MdPersianDateTimePicker({
            targetTextSelector: '#inputDate',
            selectedDate: today,
            selectedRangeDate: [firstDayDate, lastDayDate],
            englishNumber: true,
            rangeSelector: true,
        });

        $('#expire').MdPersianDateTimePicker({
            targetTextSelector: '#inputExpire',
            englishNumber: true,

            selectedDate: expireDate,

        });


    </script>
    <script type="text/javascript">
        $('.food-select').select2({
            placeholder: "choose foods",
            allowClear: true,
        });
    </script>







@endsection
