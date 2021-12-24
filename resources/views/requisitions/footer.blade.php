<div class="modal fade" id="firstTermsModel" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Terms and Conditions</h5>

            </div>
            <div class="modal-body">

                @include('requisitions.terms')

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">Accept</button>

            </div>
        </div>
    </div>
</div>

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

<div class="modal fade" id="AddViewer" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="form-viewer">
            @csrf
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <label>select viewer</label>
                                <select class="form-space form-control approver" name="users[]" multiple>

                                    @foreach($requisition->viewers??[] as $viewer)
                                        <option value="{{$viewer->id}}" selected>{{$viewer->name}}</option>
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


<template id="tmp_interviewers_form">
    <div class="alert interviewer-row  alert-dismissible fade show p-0" role="alert">
        <div class="form-row" data-form-num="__data-form-num">
            <div class="form-group col-md-6">
                <label for="inputEmail4 " class="optional">Name</label>
                <input type="text" name="__name" value="__value1" class="form-control" id="interviewer_name">
            </div>
            <div class="form-group col-md-6">
                <label for="interviewer_skype_id" class="optional">Skype ID</label>
                <input type="text" value="__value2" class="form-control" name="__name" id="interviewer_skype_id">
            </div>
        </div>

        <button type="button" class="close p-0 " data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<template id="tmp_competency_form">
    <div class="alert competency-row alert-dismissible fade show p-0" role="alert">
        <div class="card-header d-none" style="border:none ; background-color: transparent">
            <button type="button" class="close p-0" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="form-row  justify-content-between" data-row-num="__data-row-num">
            <div class="form-group col-9">
                <input type="text" class="form-control" value="" placeholder="text" name="__name">
            </div>
            <div class="form-group col-3 d-flex align-items-center">
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="__radio_id1" name="__name" value="1" class="custom-control-input">
                    <label class="custom-control-label" for="__radio_id1">Essential</label>
                </div>
                <div class="custom-control custom-radio custom-control-inline">
                    <input type="radio" id="__radio_id2" name="__name" value="0" class="custom-control-input">
                    <label class="custom-control-label" for="__radio_id2">Desirable</label>
                </div>
            </div>
        </div>
    </div>
</template>

<template id="tmp_determiners_form">

        <div class="col-md-6 alert alert-dismissible fade show pr-0" role="alert">
            <label>Approver <span>__approver_index</span></label>
            <select id="" name="determiners[]"
                    class="form-space form-control select2 approver">
                <option selected disabled>Empty</option>
            </select>
            <button type="button" class="close p-0 " data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>

    </div>
</template>
