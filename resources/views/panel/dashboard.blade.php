@extends('layouts.panel')

@section('title', 'Dashboard')

@section('content')
    <h2>Pending</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $pending , 'card_class' => 'bg-red'])
    <h2>In progress</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $in_progress, 'card_class' => 'bg-primary'])
    <h2>Accepted</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $accepted, 'card_class' => 'bg-green'])
    <h2>Assignments</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $assignment, 'card_class' => 'bg-warning'])
    <h2>Closed</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $closed, 'card_class' => 'bg-dark'])

    <h2>Hold</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $holding, 'card_class' => 'bg-orange'])

    <h2>rejected</h2>
    <hr>
    @include('requisitions.index', ['requisitions' => $rejected, 'card_class' => 'bg-secondary'])



    

@endsection
