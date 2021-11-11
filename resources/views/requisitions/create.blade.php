@extends('layouts.panel')

@section('title', 'Create Requisition')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <form action="{{ Route('requisitions.store') }}" method="POST" id="form">
                    @csrf
                    @include('requisitions.form',['form_sections_items'=>$form_sections_items ,'form'=>'create'])
                </form>
            </div>
        </div>
    </div>

    @include('requisitions.footer')

    @if(config('app.required_terms'))

        <script>
            var termAccepted =  @json(session('termAccepted') ) ;
            console.log(termAccepted);
            if (termAccepted == 0) {
                $('#firstTermsModel').modal('show');

            }

            $("#submit-requisition").click(function (e) {
                e.preventDefault();

                $(this).prop('disabled', true);

                $('#form').submit();


            })
        </script>
    @endif


@stop



