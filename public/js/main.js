$(document).ready(function () {

    var draftForm = $('#form-draft'),
        saveDraft = draftForm.find('#save-draft-requisition'),
        draftUpdate = draftForm.find('input[name="draft_update"]'),
        draftDelete = $("#draft_delete"),
        DraftImportModal = $('#DraftImportModal'),
        DraftNameModal = $('#DraftNameModal');

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
                //    $("#form").find('textarea[name="' + index + '"]').text('item');
                //   console.log()

                $.each(draft, function (index, item) {

                    if (index == 'interviewers') {
                        array = Object.values(item);
                        i = 0
                        append = '';
                        $.each(array, function (index, item) {
                            i++;
                            tmp = $("#tmp_interviewers_form").html();
                            tmp = tmp.replaceAll('__name', 'interviewers[' + i + '][]');

                            tmp = tmp.replaceAll('__data-form-num', i);
                            tmp = tmp.replaceAll('__value1', item[0]);
                            tmp = tmp.replaceAll('__value2', item[1]);
                            //$(tmp).find('input').val('dd');

                            append += tmp;
                        });
                        $("#interviewer_form_rows").html(append)
                    }

                    $("#form").find('input[type="text"][name="' + index + '"]').val(item);
                    $("#form").find('input[type="number"][name="' + index + '"]').val(item);
                    $("#form").find('textarea[name="' + index + '"]').html(item).val(item);

                    $("#form").find('select[name="' + index + '"]').val(item);
                    $("#form").find('input[type="radio"][name="' + index + '"][value="' + item + '"]').prop('checked', true);

                });

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
    });

});
