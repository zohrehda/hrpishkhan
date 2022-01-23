<div class="modal fade" id="DraftNameModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-draft">
            <div class="modal-content">

                <div class="modal-body">


                    <div class="custom-control custom-checkbox d-none ">
                        <input type="checkbox" class="custom-control-input" name="draft_update" value="1"
                               id="draft_update" data-draft-name="">
                        <label class="custom-control-label" for="draft_update">update</label>
                    </div>

                    @if(auth()->user()->id==App\User::hr_admin()->id)
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" name="draft_public" value="1"
                                   id="draft-public">
                            <label class="custom-control-label" for="draft-public">public</label>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label optional">Name the Draft:</label>
                        <input type="text" class="form-control " id="draft-name" name="draft_name" required>
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

<div class="modal fade" id="DraftImportModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-body">
                <ul class="list-group">
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
