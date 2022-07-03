<div class="text-left">
    <h5><b>Assignment</b></h5>
    <form action="{{ Route('requisitions.determine', $requisition->id) }}" method="POST">
        @csrf
        @if(auth()->user()->is_hr_admin())
            <div class="mr-1">
                <div class="custom-control custom-radio custom-control-inline custom-switch">
                    <input type="radio" id="assign-type-{{$requisition->id}}" name="assign_type" value="assign"
                           class="custom-control-input" @if($requisition->assignment_type()=='assign') checked @endif>
                    <label class="custom-control-label" for="assign-type-{{$requisition->id}}">assign to assign</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline custom-switch">
                    <input type="radio" required id="do-type-{{$requisition->id}}" name="assign_type" value="do"
                           class="custom-control-input" @if($requisition->assignment_type()=='do') checked @endif>
                    <label class="custom-control-label" for="do-type-{{$requisition->id}}">assign to do</label>
                </div>
            </div>
        @else
            <input type="hidden" name="assign_type" value="do">
        @endif
        <select name="user_id" required class="form-space select-user custom-select select2">
            @if($requisition->assigned_to_user())
                <option selected
                        value="{{ $requisition->assigned_to_user()->email  }}">{{$requisition->assigned_to_user()->email}}</option>
            @endif
        </select>

        <div class="text-center">
            <button name="progress_result" class="btn btn-sm btn-primary" value="{{ASSIGN_ACTION}}">
                assign
            </button>
        </div>
    </form>
</div>
