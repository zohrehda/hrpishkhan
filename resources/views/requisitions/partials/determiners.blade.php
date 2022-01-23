<label >
Please select the ones who need to approve your request in order.
</label>

@if(isset($requisition))
    <div class="row form-receivers-part">
        <div class="col-12">

        </div>
        @foreach($requisition->approver_determiners as $k=>$determiner)
            <div class="col-md-6 alert alert-dismissible pr-0 fade show" role="alert">
                    <label>Approver {{$k+1}}</label>
                    <select name="determiners[{{$loop->index}}]"
                            class="form-space form-control select2 approver"
                           @cannot('update_determiners',$requisition) disabled @endcannot

                    >
                        <option selected value="{{$determiner->email}}">{{$determiner->email}}</option>
                    </select>

                    @can('update_determiners',$requisition)
                        <button type="button" class="close p-0 " data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>

                    @endcan
                </div>

        @endforeach
    </div>
    @can('update_determiners',$requisition)
        <br>
        <div class="row">
            <div class="col-12">
                <button type="button" id="add_receiver" class="btn btn-sm btn-success">Add
                </button>
            </div>
        </div>
    @endcan
@else
    <div class="row form-receivers-part">
    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <button type="button" id="add_receiver" class="btn btn-sm btn-success">Add
            </button>
        </div>
    </div>


@endif


