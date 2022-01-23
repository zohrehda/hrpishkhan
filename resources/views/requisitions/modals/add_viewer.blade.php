<div class="modal fade AddViewer" id="AddViewer-{{$requisition->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="form-viewer">
            @csrf
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <label>select viewer</label>
                                <select class="form-space form-control approver" name="users[]" multiple>

                                    @foreach($requisition->viewers??[] as $viewer)
                                        <option value="{{$viewer->id}}" selected>{{$viewer->email}}</option>
                                    @endforeach

                                </select>
                                <input type="hidden" name="requisition_id" value="{{$requisition->id??null}}">

                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="save-draft-requisition" class="btn btn-primary">Save</button>
                </div>
            </div>

        </form>
    </div>
</div>
