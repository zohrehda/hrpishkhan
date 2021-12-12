@extends('layouts.panel')

@section('title', 'Edit Requisition')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <form id="form" action="{{ Route('requisitions.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{$requisition->id}}">

                    @include('requisitions.form',['form_sections_items'=>$form_sections_items ,'form'=>'edit'])

                </form>
            </div>
        </div>
    </div>
    @include('requisitions.footer',['requisition'=>$requisition])

@stop
