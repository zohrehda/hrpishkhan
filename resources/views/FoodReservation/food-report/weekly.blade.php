@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')

    <section class="food-report-weekly">
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
            </div>
            @if(count($report)>0 )
                <div class="row info-bar">
                    <div class="col-md-12">
                        <div class="alert alert-primary">
                            <div class="row">

                                @foreach($report as $v)
                                    <div class="col-md-3">
                                        <span>{{$v['food_title']}}</span>
                                        <span class="text-danger">{{ array_sum($v['data'])  }} </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif


            @if(isset($firstDay ,$lastDay))
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                weekly food report
                            </div>
                            <div class="card-body">


                                <canvas width="100" height="50" role="img" aria-label="chart" id="report-chart"
                                        style="font-family: 'MuseoSans' "
                                ></canvas>
                                <script>
                                    var report_array = {!! json_encode( array_values($report) ) !!} ;
                                    var labels = {!! json_encode( $day_range   ) !!} ;
                                    var labels = Object.values(labels);
                                    var data = {
                                        labels: labels,
                                        datasets: []
                                    }
                                    var ctx = document.getElementById('report-chart').getContext('2d');
                                    var myLineChart = new Chart(ctx, {
                                        type: 'bar',
                                        data: data,
                                        options: {
                                            responsive: true,
                                            legend: {
                                                position: 'top',
                                                labels: {
                                                    fontFamily: 'Yekan'
                                                },
                                            },
                                            scales: {
                                                xAxes: [{
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: 'روز',
                                                        fontFamily: 'Yekan',
                                                        fontColor: 'red',
                                                    },
                                                    display: true,
                                                    ticks: {
                                                        fontFamily: 'Yekan'
                                                    }
                                                }],
                                                yAxes: [{
                                                    scaleLabel: {
                                                        display: true,
                                                        labelString: 'تعداد رزرو',
                                                        fontFamily: 'Yekan',
                                                        fontColor: 'red',
                                                    },
                                                    display: true,
                                                    ticks: {
                                                        fontFamily: 'Yekan',
                                                        stepSize: 1,
                                                    },
                                                }]
                                            },
                                        }
                                    });

                                    report_array.forEach(function (item, index) {
                                        var random_color = '#' + Math.floor(Math.random() * 16777215).toString(16);
                                        data.datasets.push({
                                            label: item['food_title'], data: Object.values(item['data']),
                                            borderColor: random_color,
                                            backgroundColor: random_color,
                                            pointStyle: 'circle',
                                            pointRadius: 3,
                                            pointBorderColor: random_color,
                                        });
                                    })
                                    myLineChart.update();
                                </script>
                            </div>
                        </div>
                        <br>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <script type="text/javascript">

        var fd =
        {!! isset($firstDay)?json_encode($firstDay):json_encode('') !!}

        if (fd == '') {
            var today = new Date();
            var firstDay = today.getDate() + 6 - today.getDay();
            var firstDayDate = new Date(today.setDate(firstDay));
            var lastDay = firstDay + 6;
            var lastDayDate = new Date(today.setDate(lastDay));
        } else {

            var ld =
                {!! isset($lastDay)?json_encode($lastDay):json_encode('') !!}

            var firstDayDate = new Date(Object.values(fd)[0]);
            var lastDayDate = new Date(Object.values(ld)[0]);
        }
        $('#date').MdPersianDateTimePicker({
            targetTextSelector: '#inputDate',
            selectedDate: today,
            selectedRangeDate: [firstDayDate, lastDayDate],
            englishNumber: true,
            rangeSelector: true
        });
    </script>








@endsection
