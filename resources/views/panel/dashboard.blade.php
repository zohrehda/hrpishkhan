@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')
    <h2>Pending</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $pending , 'card_class' => 'bg-warning'])
    <h2>In progress</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $in_progress, 'card_class' => 'bg-primary'])
    <h2>Accepted</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $accepted, 'card_class' => 'bg-success'])
@endsection
