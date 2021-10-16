<!doctype html>
<html>
<head>
    <title>today reserved food</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="script" href="/css/style-fr.css">
    <style>
        body {
            font-family: DejaVu Sans;
            width: 100%;
            margin: 43px 64px;
        }

        table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
            box-sizing: border-box;
        }

        table td {
            padding: .75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }

        table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #dee2e6;
            padding: .75rem;

            border-top: 1px solid #dee2e6;
        }

        th {
            text-align: inherit;
        }

        tr {
            text-align: center;
        }

        .date {
            margin: 20px;
            margin-left: 0px;
        }

        .head {
            text-align: center;
            font-size: 24px;
        }
        .info{
            direction: rtl;
            text-align: right;
        }
        .info div{
            display: inline-block;
            margin-left: 10px;
        }


    </style>
</head>

<body>
<div class="head">
    <b> Daily Food Reservation</b>
</div>
<div class="date">
    <b>date : {{ $today }}</b>
</div>
<div class="info">
    @foreach($foods_count as $k=>$v )
        <div>
            <span> {{$k}}: </span>
            <span>{{$v}}</span>
        </div>
    @endforeach
</div>
<br>

<div class="card-body">
    <table class="table table-striped">
        <thead>
        <tr>

            <th scope="col">USER</th>
            <th scope="col">FOOD</th>

        </tr>
        </thead>
        <tbody>
        @if(isset($daily))
            @foreach($daily as $k=>$v)
                <tr>
                    <td>{{$k}}</td>
                    <td>{{$v}}</td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>
</div>
</body>
</html>

