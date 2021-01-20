@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')

    <section class="food-reserve">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text cursor-pointer" id="date">choose week</span>
                            </div>
                            <input type="text" id="inputDate" class="form-control" name="week-date"
                                   placeholder="DateTimePicker Range Selector With Multiple Months" aria-label="date"
                                   aria-describedby="date">
                        </div>
                    </div>
                </div>

                @if(isset($expire) )

                    <div class="col-md-6">
                        <div class="alert alert-warning expire-alert " role="alert">
                            @if($expired)
                                <span class="text-danger expired "> <b>EXPIRED</b> </span>
                            @endif
                            <span
                                class="   @if($expired) {{'line-through'}}  @endif     "> expire date: {{$expire->expire}}</span>
                        </div>
                    </div>
                @endif
                <div class="col-md-6">
                    <select class="custom-select" onchange="location = this.value;">
                        <option disabled selected> history</option>
                        @foreach($frw as $v)
                            <option
                                {{(request()->route()->parameter('week_date')==str_replace('/','|',$v->plan_date_range ))?'selected':''  }}
                                value="{{route('FoodReservation.FoodReserve.index',str_replace('/','|',$v->plan_date_range ) )}}">
                                {{$v->plan_date_range}}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <b class="text-primary"> weekly food reservation </b>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ ( request()->route()->parameter('week_date')==\App\Http\Controllers\Controller::lastWeek() )?'active':''  }}"
                                       id="last-tab"
                                       href="{{ Route('FoodReservation.FoodReserve.index',\App\Http\Controllers\Controller::lastWeek()  ) }}"
                                       aria-controls="last" aria-selected="true">last week</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ ( request()->route()->parameter('week_date')==\App\Http\Controllers\Controller::thisWeek() )?'active':''  }} "
                                       id="this-tab"
                                       href="{{ Route('FoodReservation.FoodReserve.index',\App\Http\Controllers\Controller::thisWeek()  ) }}"
                                       aria-controls="this" aria-selected="true">this week</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ ( request()->route()->parameter('week_date')==\App\Http\Controllers\Controller::nextWeek() )?'active':''  }} "
                                       id="next-tab"
                                       href="{{ Route('FoodReservation.FoodReserve.index',\App\Http\Controllers\Controller::nextWeek()  ) }}"
                                       aria-controls="next" aria-selected="true">next week</a>
                                </li>
                            </ul>

                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="last" role="tabpanel"
                                     aria-labelledby="last-tab">
                                    <b>date: </b> {{ request()->route()->parameter('week_date') }}
                                </div>

                            </div>


                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">DAY</th>
                                    <th scope="col">FOOD</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($planed) && $planed )
                                    @php
                                        $fd=clone $firstDay; $ls=clone $lastDay ; $diff=  $firstDay->diffDays($lastDay);
                                    @endphp
                                    <form method="post" id="formPlan"
                                          action="{{route('FoodReservation.FoodReserve.store')}}">
                                        @for($i=0 ; $i<=$diff ;$i++  )

                                            <tr>

                                                <td>{{($i==0)?$fd->format('Y-m-d'):$fd->addDay()->format('Y-m-d')   }}
                                                    {{  $fd->formatWord('l')  }}
                                                </td>
                                                <td>


                                                    {{csrf_field()}}
                                                    <select class="custom-select" id="" {{($expired)?'disabled':''  }}
                                                    style="width:100%"
                                                            name="{{$fd->format('Y-m-d')}}">
                                                        <option value="0">choose</option>
                                                        @foreach(getFoodDay($fd->format('Y-m-d')) as $food_id)

                                                            @if($food_id!=0)

                                                                <option value="{{$food_id}}"

                                                                    {{ (       getFoodReserved($fd->format('Y-m-d'))==$food_id                         )?'selected':''  }}

                                                                >
                                                                    {{FoodName($food_id)}}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>


                                                </td>
                                            </tr>
                                        @endfor
                                    </form>
                                @else
                                    <td class="text-danger">there is no food plan!</td>
                                    <td></td>

                                @endif
                                </tbody>
                            </table>

                            @if(isset($planed) && $planed)
                                <input type="submit" form="formPlan" class="btn btn-primary submit-btn"
                                       @if($expired) {{'disabled'}} @endif  value="submit">

                            @endif


                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        var today = new Date();
        var fd =
            {!! isset($firstDay)?json_encode($firstDay):json_encode('') !!}

        var ld =
            {!! isset($lastDay)?json_encode($lastDay):json_encode('') !!}

        var firstDayDate = new Date(Object.values(fd)[0]);
        var lastDayDate = new Date(Object.values(ld)[0]);


        $('#date').MdPersianDateTimePicker({
            targetTextSelector: '#inputDate',
            selectedDate: today,
            englishNumber: true,
            selectedRangeDate: [firstDayDate, lastDayDate],
            rangeSelector: true
        });
    </script>
@endsection
