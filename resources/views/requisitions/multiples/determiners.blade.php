@if(isset($requisition))
    <div class="row form-receivers-part">
        @foreach($requisition->approver_determiners as $k=>$determiner)
            <div class="col-md-6">
                <div class="alert alert-dismissible fade show p-0" role="alert">
                    <label>Approver {{$k+1}}</label>
                    <select name="determiners[{{$loop->index}}]"
                            class="form-space form-control select2 approver"
                           @cannot('update_determiners',$requisition) disabled @endcannot

                    >
                        <option selected value="{{$determiner->id}}">{{$determiner->email}}</option>
                    </select>

                    @can('update_determiners',$requisition)
                        <button type="button" class="close p-0 " data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    @endcan
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="row form-receivers-part">
        <div class="col-md-6">
            <div class="alert alert-dismissible fade show p-0" role="alert">
                <label>Approver 1</label>
                <select id="" name="determiners[]"
                        class="form-space form-control select2 approver">
                    <option selected disabled>Empty</option>
                </select>
                <button type="button" class="close p-0 " data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>

    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <button type="button" id="add_receiver" class="btn btn-sm btn-success">Add
            </button>
        </div>
    </div>

@endif

@can('add_determiners',$requisition??null)
    <br>
    <div class="row">
        <div class="col-12">
            <button type="button" id="add_receiver" class="btn btn-sm btn-success">Add
            </button>
        </div>
    </div>
@endcan
