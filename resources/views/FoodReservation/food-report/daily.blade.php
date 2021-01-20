@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')
    <section class="food-report-daily">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="input-group" id="input-expire">
                            <div class="input-group-prepend">
                                <span class="input-group-text cursor-pointer" id="date">day</span>
                            </div>
                            <input type="text" id="inputDate" class="form-control" name="date"
                                   placeholder="DateTimePicker Range Selector With Multiple Months" aria-label="date"
                                   aria-describedby="date">
                        </div>
                    </div>
                </div>
            </div>
            @if(count($daily_foods_count)>0 )
                <div class="row info-bar">
                    <div class="col-md-12">
                        <div class="alert alert-primary">
                            <div class="row">

                                @foreach($daily_foods_count as$k=> $v)
                                    <div class="col-md-3">
                                        <span>{{$k}}:</span>
                                        <span class="text-danger">{{ $v }} </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <br>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <b class="text-primary">daily report</b>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">USER</th>
                                    <th scope="col">FOOD</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($daily) && count($daily)>0 )
                                    @foreach($daily as $k=>$v)
                                        <tr>
                                            <td>{{$k}}</td>
                                            <td>{{$v}}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td class="text-danger">there is no reserved food!</td>
                                        <td></td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                            @if(isset($daily) && count($daily)>0 )
                                <button class="btn btn-primary  "><a
                                        href="{{route('FoodReservation.food-report.createPDF')}}">download pdf</a>
                                </button>
                            @endif
                        </div>
                    </div>
                    <br>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        var date =
        {!! isset($date)?json_encode($date):json_encode(false) !!}
        if (date == '') {
            var date = new Date();
        } else {
            var date = new Date(Object.values(date)[0]);
        }
        $('#date').MdPersianDateTimePicker({
            targetTextSelector: '#inputDate',
            englishNumber: true,
            selectedDate: date,
        });
    </script>
@endsection
