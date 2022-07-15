@extends('layouts.panel')

@section('title', 'Create Requisition')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <form action="{{ route('requisitions.store') }}" method="POST" id="form" enctype="multipart/form-data">
                    @csrf
                    @include('requisitions.form',['form_sections_items'=>$form_sections_items])
                </form>
            </div>
        </div>
    </div>

    @include('requisitions.templates')
    @include('requisitions.modals.draft')
    @include('requisitions.modals.terms')


    @if(config('app.required_terms'))
        <script>
            var termAccepted =  @json(session('termAccepted') ) ;

            if (termAccepted == 0) {
                $('#firstTermsModel').modal('show');
            }
        </script>
    @endif

@stop
