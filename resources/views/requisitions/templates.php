
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
