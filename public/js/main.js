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
        viewerForm = $('.form-viewer'),
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

    const departmentInputElm = $("select[name='department']");
    const levelInputElm = $("select[name='level']");

    function appendLevel() {
        let department = departmentInputElm.val();
        let departments_level = getLevels['departments_level'][department];
        let levels = getLevels['levels'];

        let html = ''
        if (department) {
            if (typeof departments_level == "undefined") {
                departments_level = getLevels['departments_level']['ect'];
            }

            let old = levelInputElm.attr('data-old');

            if (old.length > 0) {
                html += "<option value=" + old + " selected>" + levels[old] + "</option>";
            } else {
                html += "<option value='' selected>Empty</option>";
            }
            levelInputElm.empty()
            $.each(departments_level, function (key, value) {
                html += "<option value=" + value + ">" + levels[value] + "</option>";
            });

        } else {
            html += "<option value=''>Empty</option>";
        }
        levelInputElm.html(html)
    }


    /***** disable & enable vertical input depending on value of department input *****/
    departmentInput.on('change', function () {
         value = $(departmentInput).val();

        departmentsRequiresvertical = Object.values(formItemsSetting.vertical.required_if)[0];
        if (departmentsRequiresvertical.indexOf(value) !== -1) {
            $("*[name='vertical']").prop('disabled', false);
        } else {
            $("*[name='vertical']").prop('disabled', true);
            $("*[name='vertical']").val('empty');
        }
    });
    departmentInput.trigger('change');

    /***** get staff using ajax *****/
    function initializeSelect2(elem) {
        elem.select2({
            ajax: {
                url: "/panel/users",
                dataType: 'json',
                templateResult: function (item) {
                    return format(item, false);
                },
                matcher: matchStart,
            }
        });
    }

    initializeSelect2($('.select-user'));
    initializeSelect2($('.approver'));


    /***** customise level select option depending on selected department *****/

    departmentInputElm.on('change', function (event) {

        appendLevel()
        levelInputElm.attr('data-old', '')
    });

    appendLevel()

    // departmentInputElm.trigger('change')

    /***** handle approver section depending on department and is new *****/

    function DisplayApprover() {
        is_new = $("input[name='is_new']");
        var radio_val = is_new.filter(':checked').val();
        department = $("select[name='department']").val();

        if (radio_val == 0 && department == 'tech') {
            return false;
        }
        return true;
    }

    $('#department , input[name="is_new"]').on('change', function (event) {
        display_status = DisplayApprover();
        if (display_status) {
            $("#determiners").show();
        } else {
            $("#determiners").hide();
        }
    });

    /***** shift *****/
    $(shiftCheckbox).on('change', function () {

        if ($(shiftCheckbox).is(':checked')) {
            $("#shift_select").prop('disabled', false);
            $("input[name='shift']").val(null);
            $("#shift_select").trigger('change');

        } else {
            //   alert('ff')
            $("#shift_select").prop('disabled', true);
            $("#shift_select").val('empty');
            $("input[name='shift']").val(null);
        }
    });
    $("#shift_select").on('change', function () {
        $("input[name='shift']").val($("#shift_select").val());
    });
    $(shiftCheckbox).trigger('change');


    /***** empty input modals *****/
    $('.modal').on('show.bs.modal', function (e) {
     //   $(this).find('input[type="checkbox"]').prop('checked', false);
       // $(this).find('input[type="text"]').val('');

    });


    /***** add interviewer form row *****/
    $("#add_interviewer").on('click', function () {
        interviewer_html();
    });


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


    /***** add receiver select input *****/
    //   j = 0;

    $("#add_receiver").on('click', function () {

        // j++;
        last_index = $(".form-receivers-part").children().last().index();
        j = (last_index) ? 1 : last_index + 2;

        tmp = $("#tmp_determiners_form").html();
        tmp = tmp.replaceAll('__approver_index', '');

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

    $("button[type='submit']").click(function (e) {
        //        e.preventDefault();
        //  $(this).prop('disabled', true);

        $(this).css({
            "opacity": "0.5",
            "cursor": "not-allowed",
            "pointer-events": "none"


        })
        //$(this).parents('form').submit() ;

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

            tmp = tmp.replaceAll('__value1', (item[0] != null) ? item[0] : '');
            tmp = tmp.replaceAll('__value2', (item[1] != null) ? item[1] : '');
            //$(tmp).find('input').val('dd');
            $("#interviewer_form_rows").append(tmp);
            //  append += tmp;
        });

    }

}
