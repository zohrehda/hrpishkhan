$(document).ready(function () {

    function getLevelOptions() {
        return $.ajax({
            'url': '/panel/setting/levels',
            'type': 'get',
            'dataType': 'json',
            'async': false,
            success: function (response) {
                return response;
            }
        }).responseJSON;
    }

    function getSetting() {
        return $.ajax({
            'url': '/panel/setting',
            'type': 'get',
            'dataType': 'json',
            'async': false,
            success: function (response) {
                return response;
            }
        }).responseJSON;
    }


    var draftForm = $('#form-draft'),
        viewerForm = $('#form-viewer'),
        saveDraft = draftForm.find('#save-draft-requisition'),
        draftUpdate = draftForm.find('input[name="draft_update"]'),
        draftDelete = $("#draft_delete"),
        DraftImportModal = $('#DraftImportModal'),
        DraftNameModal = $('#DraftNameModal'),
        departmentInput = $("select[name='department']"),
        isNewInput = $("input[name='is_new']"),
        getLevels = getLevelOptions(),
        shiftCheckbox = $("#shift_checkbox"),
        setting = getSetting(),
        formItemsSetting = setting['form_items'];


    function appendDrafts() {
        $.ajax({
            url: '/panel/requisitions/draft',
            success: function (response) {
                drafts = response.drafts;
                userId = response.user_id;
                append = '';
                if (drafts.length == 0) {
                    append += 'There is no draft';
                }
                $.each(drafts, function (index, item) {
                    badge = '';
                    delete_append = ''
                    if (item.public == 1) {
                        badge = ' <span class="badge badge-pill badge-info">public</span>';
                    }
                    if (item.user_id == userId) {
                        delete_append = '<span class="btn btn-sm btn-danger" id="draft_delete" data-draft-id="' + item.id + '"' +
                            '>delete</span> ';
                    }
                    append += ' <li class="list-group-item d-flex justify-content-between align-items-center">' +
                        '<div>' +
                        '<span>' + item.name + '</span> ' +
                        badge +

                        '</div>' +

                        '<div>' +
                        delete_append +
                        '<span class="btn btn-sm btn-success" id="draft_import" data-draft-id="' + item.id + '"' +
                        '>import</span>' +
                        '</div>' +
                        '</li>';
                });
                $("#DraftImportModal").find('.list-group').html(append);
            }
        });
    }


    /***** customise level select option depending on selected department *****/

    $('#department').on('change', function (event) {

        department = $('#department').val();
        departments_level = getLevels['departments_level'][department];
        levels = getLevels['levels'];

        if (department){

            if (typeof departments_level == "undefined") {
                departments_level = getLevels['departments_level']['ect'];
            }

            $('select#level').empty();

            $.each(departments_level, function (key, value) {
                html = "<option value=" + value + ">" + levels[value] + "</option>";
                $('select#level').append(html);
            });

        }else {
            html = "<option>Empty</option>";
            $('select#level').append(html);
        }
    });


    /***** shift *****/
    $(shiftCheckbox).on('change', function () {

        if ($(shiftCheckbox).is(':checked')) {
            $("#shift_select").prop('disabled', false);
            $("input[name='shift']").val('');
            console.log(  $("input[name='shift']").val())
          //    alert('f') ;
        } else {
          //  alert('aa') ;
            $("#shift_select").prop('disabled', true);
            $("#shift_select").val('empty');
            $("input[name='shift']").val(0);
        }
    });
    $("#shift_select").on('change', function () {
        $("input[name='shift']").val($("#shift_select").val());
    });
    $(shiftCheckbox).trigger('change');

    /***** store requisition draft *****/
    draftForm.on('submit', function (event) {
        event.preventDefault();

        var draftName = draftForm.find('input[name="draft_name"]').prop('disabled', false);

        checkBoxForm = '';
        draftForm.find("input[type='checkbox']:not(:checked)").map(function (index, item) {
            checkBoxForm += '&' + $(item).attr('name') + '=0';
        });

        $.ajax({
            url: '/panel/requisitions/draft',
            type: 'post',
            dataType: 'json',
            data: $('#form,#form-draft').serialize() + checkBoxForm,
            success: function (response) {

                if (response.success) {
                    //alert('ff') ;
                    $('#DraftNameModal').modal('hide');

                }
            }

        });

    });

    /***** store requisition viewers *****/

    viewerForm.on('submit', function (event) {
        event.preventDefault();

        $.ajax({
            url: '/panel/requisitions/viewers',
            type: 'post',
            dataType: 'json',
            data: $('#form-viewer').serialize(),
            complete: function (response) {

                if (response) {
                    //alert('ff') ;
                    $('#AddViewer').modal('hide');

                }
            }

        });

    });

    /***** set input draft name current draft *****/
    draftUpdate.on('change', function () {
        draftName = draftForm.find('input[name="draft_name"]');

        if (this.checked) {
            draftName.val($(this).attr('data-draft-name')).attr('disabled', 'disabled');
        } else {
            draftName.prop('disabled', false).val('');
        }

    });

    /***** delete draft *****/
    DraftImportModal.on('click', '#draft_delete', function () {

        draftId = $(this).attr('data-draft-id');

        $.ajax({
            url: '/panel/requisitions/draft/' + draftId + '/destroy',
            type: 'get',
            dataType: 'json',
            success: function (response) {
                appendDrafts();
            }
        });

    });

    /***** display all drafts *****/
    DraftImportModal.on('show.bs.modal', function (e) {
        appendDrafts();
    });

    /***** empty input modals *****/
    $('.modal').on('show.bs.modal', function (e) {
        $(this).find('input[type="checkbox"]').prop('checked', false);
        $(this).find('input[type="text"]').val('');
    });


    /***** import draft *****/
    DraftImportModal.on('click', '#draft_import', function () {

        draftId = $(this).attr('data-draft-id');

        $.ajax({
            url: '/panel/requisitions/draft/' + draftId,
            type: 'get',
            dataType: 'json',
            success: function (response) {
                draft = JSON.parse(response.drafts.draft);
                userId = response.user_id;

                //level
                $.each(draft, function (index, item) {

                    if (index == 'interviewers') {
                        interviewer_html(item);
                    }
                    if (index == 'competency') {
                        competency_html(item);
                    }


                    $("#form").find('input[type="text"][name="' + index + '"]').val(item);
                    $("#form").find('input[type="number"][name="' + index + '"]').val(item);
                    $("#form").find('textarea[name="' + index + '"]').html(item).val(item);
                    $("#form").find('select[name="' + index + '"]').val(item);
                    $("#form").find('input[type="radio"][name="' + index + '"][value="' + item + '"]').prop('checked', true);
                    $("#form").find('input[type="checkbox"][name="' + index + '"][value="' + item + '"]').prop('checked', true);
                    isNewInput.trigger('change');
                    departmentInput.trigger('change');

                    if (index=='department'){
                        console.log(draft.level) ;
                        console.log($("#form").find('select[name="level"]').val()) ;
                        $("#form").find('select[name="level"]').val(draft.level);
                        $("#form").find('select[name="vertical"]').val(draft.vertical);
                        console.log($("#form").find('select[name="level"]').val()) ;
                    }
                });
                $(shiftCheckbox).trigger('change');

                DraftNameModal.find('#draft_update').attr('data-draft-name', response.drafts.name);
                if (userId == response.drafts.user_id) {
                    DraftNameModal.find('#draft_update').parent('.custom-control').removeClass('d-none');
                } else {
                    DraftNameModal.find('#draft_update').parent('.custom-control').addClass('d-none');

                }
                if ($("#form-radio-hiring_type [name='hiring_type']:checked").val() == 0) {
                    $('#form-input-replacement').removeClass('d-none')
                } else {
                    $('#form-input-replacement').addClass('d-none')
                }
            }

        });

        $('#DraftImportModal').modal('hide');

    });


    /***** add interviewer form row *****/
    $("#add_interviewer").on('click', function () {
        interviewer_html();
    });


    /***** get staff using ajax *****/
    function initializeSelect2(elem) {
        elem.select2({
            ajax: {
                url: "/panel/requisitions/staff",
                dataType: 'json',
                templateResult: function (item) {
                    return format(item, false);
                },
                matcher: matchStart,
                /*  delay: 250,
                  placeholder: 'Search in users',
                  minimumInputLength: 1,*/
            }
        });
    }

    initializeSelect2($('.approver'));

    /***** select user form *****/
    function insertUsersForm(area, select_name, label, option_label) {
        selected_id = $(area).attr('data-selected-id');
        selected_email = $(area).attr('data-selected-email');
        var i = 1;
        //    $('.form-receivers-part').empty();
        // var select_name = "user";
        label = '';
        if (label) {
            label = ' <label for="determiners" class="optional">' + label + '</label>';
        }
        option_inner = 'Empty';
        if (option_label) {
            option_inner = option_label
        }

        selected_option = ' <option selected disabled>' + option_inner + '</option>'
        if (selected_id) {
            selected_option = '  <option selected value="' + selected_id + '"  >' + selected_email + '</option>';
        }

        $(area).append(' <div class="">' +
            label +
            '<select id="" name="' + select_name + '" required="true" class="form-space select22 custom-select select2"  ></select>'
            + '</div>');
        $('select[name="' + select_name + '" ]').append(selected_option)
        i++;
        // $('.select22').prop('required', true);
        $('.select22').select2({
            ajax: {
                url: "/panel/requisitions/staff",
                dataType: 'json',
                templateResult: function (item) {

                    return format(item, false);
                },
                matcher: matchStart,
                /*  delay: 250,
                  placeholder: 'Search in users',
                  minimumInputLength: 1,*/


                // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
            }
        });


    }

    insertUsersForm('.select-user', 'user_id', null, 'select user');

    /***** disable & enable replacement input depending on value of is_new input *****/
    isNewInput.on('change', function () {
        var radio_val = isNewInput.filter(':checked').val();
        //  console.log(radio_val);
        if (radio_val == 0) {

            $("input[name='replacement']").prop('disabled', false);
        } else {
            $("input[name='replacement']").prop('disabled', true);
        }
    });
    isNewInput.trigger('change');


    /***** disable & enable vertical input depending on value of department input *****/
    departmentInput.on('change', function () {
        value = $(departmentInput).val();

        departmentsRequiresVertical = Object.values(formItemsSetting.vertical.required_if)[0];
        if (departmentsRequiresVertical.indexOf(value) !== -1) {
            $("*[name='vertical']").prop('disabled', false);
        } else {
            $("*[name='vertical']").prop('disabled', true);
            $("*[name='vertical']").val('empty');
        }
    });
    departmentInput.trigger('change');


    /***** add receiver select input *****/
    i = 0;
    $("#add_receiver").on('click', function () {
        i++;

        tmp = $("#tmp_determiners_form").html();
        tmp = tmp.replaceAll('__approver_index', i);

        $(".form-receivers-part").append(tmp);
        initializeSelect2($('.approver'));
    });

    /***** add competency input *****/
    $("#add_competency").on('click', function () {
        competency_html();
    });

    /***** disable submit button after click *****/
    $("#submit-requisition").click(function (e) {
        e.preventDefault();
        $(this).prop('disabled', true);
        $('#form').submit();
    });

});

function competency_html(competency = null) {

    if (!competency) {
        i = $("#competency_form_row").find('.form-row').last().attr('data-row-num');

        if (!i) {
            i = 0;
        }

        i++;

        tmp = $("#tmp_competency_form").html();
        tmp = tmp.replaceAll('__name', 'competency[' + i + '][]');
        tmp = tmp.replaceAll('__data-row-num', i);
        tmp = tmp.replaceAll('__radio_id1', 'radio1' + i);
        tmp = tmp.replaceAll('__radio_id2', 'radio2' + i);
        $("#competency_form_row").append(tmp);
        if (i > 5) {
            $("#competency_form_row").find('.competency-row').eq(i - 1).find('.card-header').removeClass('d-none');
        }

        //$("#competency_form_row").find('.competency-row').eq(i - 1).find('input[type="text"]').val('dd');


    } else {
        $("#competency_form_row").empty();
        $.each(competency, function (key, item) {
            tmp = $("#tmp_competency_form").html();
            tmp = tmp.replaceAll('__name', 'competency[' + key + '][]');
            tmp = tmp.replaceAll('__data-row-num', key);
            tmp = tmp.replaceAll('__radio_id1', 'radio1' + key);
            tmp = tmp.replaceAll('__radio_id2', 'radio2' + key);

            $("#competency_form_row").append(tmp);
            $("#competency_form_row").find('.competency-row').eq(key - 1).find('input[type="radio"][value="' + item[1] + '"]').prop('checked', true);
            $("#competency_form_row").find('.competency-row').eq(key - 1).find('input[type="text"]').val(item[0]);
            if (key != 1) {
                $("#competency_form_row").find('.competency-row').eq(key - 1).find('.card-header').removeClass('d-none');
            }
        });
    }
}

function interviewer_html(interviewer = null) {
    if (!interviewer) {
        i = $("#interviewer_form_rows").find('.form-row').last().attr('data-form-num');
        if (!i) {
            i = 0;
        }
        i++;
        tmp = $("#tmp_interviewers_form").html();
        tmp = tmp.replaceAll('__name', 'interviewers[' + i + '][]');
        tmp = tmp.replaceAll('__data-form-num', i);
        tmp = tmp.replaceAll('__value1', '');
        tmp = tmp.replaceAll('__value2', '');
        $("#interviewer_form_rows").append(tmp);


    } else {

        $("#interviewer_form_rows").empty();
        $.each(interviewer, function (key, item) {
            tmp = $("#tmp_interviewers_form").html();
            tmp = tmp.replaceAll('__name', 'interviewers[' + key + '][]');

            tmp = tmp.replaceAll('__data-form-num', key);

            tmp = tmp.replaceAll('__value1', (item[0]!=null)?item[0]:'' );
            tmp = tmp.replaceAll('__value2', (item[1]!=null)?item[1]:'');
            //$(tmp).find('input').val('dd');
            $("#interviewer_form_rows").append(tmp);
            //  append += tmp;
        });

    }

}
