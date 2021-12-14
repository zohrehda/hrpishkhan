@if($requisition->assignments->count())
    <div class="alert alert-warning  ">
        {!! $requisition->prettyAssignments() !!}
    </div>
@endif

@can('assign_assign', $requisition)
    <div class="text-left">
        <h5><b>Assignment</b></h5>
        <form
            action="{{ Route('requisitions.determine', $requisition->id) }}"
            method="POST" class="">
            @csrf

            <div class="mr-1">
                <div
                    class="custom-control custom-radio custom-control-inline custom-switch">
                    <input type="radio" id="customRadioInline1"
                           name="assign_type"
                           @if($requisition->assignment_type()=='assign') checked
                           @endif
                           value="assign" class="custom-control-input">
                    <label class="custom-control-label"
                           for="customRadioInline1">assign
                        to assign</label>
                </div>
                <div
                    class="custom-control custom-radio custom-control-inline custom-switch">
                    <input type="radio" required id="customRadioInline2"
                           name="assign_type" value="do"
                           @if($requisition->assignment_type()=='do') checked
                           @endif
                           class="custom-control-input">
                    <label class="custom-control-label"
                           for="customRadioInline2">assign
                        to do</label>
                </div>
            </div>

            <br>
            <div class="select-user"
                 data-selected-id="{{$requisition->assigned_to_user()->id??null}}"
                 data-selected-email="{{$requisition->assigned_to_user()->email??null}}"
            >
            </div>
            <br>
            <div class=" text-cene">
                <button
                    name="progress_result" value="{{\App\Requisition::ASSIGN_STATUS}}"
                    class="btn btn-sm btn-primary">assign
                </button>
            </div>

        </form>

    </div>

@endcan

@can('assign_do', $requisition)
    <div class="text-left">
        <h5><b>Assignment</b></h5>
        <form
            action="{{ Route('requisitions.determine', $requisition->id) }}"
            method="POST" class="">
            @csrf
            <input type="hidden" name="assign_type" value="do">
            <div class="select-user"

                 data-selected-id="{{$requisition->assigned_to_user()->id??null}}"
                 data-selected-email="{{$requisition->assigned_to_user()->email??null}}"
            >

            </div>
            <br>
            <div class="text-center">
                <button
                    name="progress_result" value="{{\App\Requisition::ASSIGN_STATUS}}"
                    class="btn btn-sm btn-primary">assign
                </button>
            </div>

        </form>

    </div>

@endcan
