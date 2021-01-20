@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')
    <section class="foods">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <label for="food-title " class="text-primary"><b>add new food</b></label>
                </div>
                <div class="col-md-6">
                    <form method="post">
                        {{csrf_field()}}
                        <div class="form-group input-group-lg">
                            <input type="text" class="form-control food-input" id="food-title" name="food-title"
                                   value="{{old('food-title', ( isset($food)?$food->title:'')   )}}">
                        </div>
                        <button type="submit" class="btn btn-primary food-sub" disabled>Submit</button>
                    </form>
                </div>
                <div class="col-md-6 ">
                    <ul class="list-group">
                        @if(isset($foods) && count($foods)>0 )
                            @foreach($foods as $k=>$v)
                                <li class="list-group-item d-flex justify-content-between list-group-item-action {{(isset($food )&& $v->id==$food->id   )  ?'list-group-item-primary':'' }} ">
                                <span class="badge badge-pill">
                                 <a href="{{route('FoodReservation.foods.delete',$v->id)}}" data-toggle="tooltip"
                                    data-placement="top" title="delete"><img src="{{asset('/svg/trash.svg')}}"></a>
                                  <a href="{{route('FoodReservation.foods.edit',$v->id)}}" data-toggle="tooltip"
                                     data-placement="top" title="edit"><img src="{{asset('/svg/edit.svg')}}"></a>
                                </span>
                                    {{$v->title}}
                                </li>
                            @endforeach
                        @else
                            <div class="alert alert-warning text-left">
                                there is no food!
                            </div>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </section>

@endsection
