function myDraftsHandler(options) {
    const container = document.querySelector('#draft');
    const mainForm = document.querySelector('#form');
    let defaultOptions = {}
    options = {...defaultOptions, ...options}
    let _this = this
    _this.user = options.user
    _this.current = null

    this.init = async function () {

        container.parentElement.closest('.modal-dialog').style.maxWidth = '850px'
        container.parentElement.closest('.modal-body').style.padding = '1.5rem'
        container.appendChild(await createNewDraftElm());
        container.appendChild(createNewCategory())
        container.appendChild(createListElm())
        const createDraftFormElm = document.querySelector('#create_draft')
        const createCategoryFormElm = document.querySelector('#create_category')
        const updateCheckBoxElm = document.querySelector('#update')
        const selectCatElm = document.querySelector('select#draft_cat_id')

        createDraftFormElm.addEventListener('submit', createDraft)
        createCategoryFormElm.addEventListener('submit', createCategory)
        updateCheckBoxElm.addEventListener('change', clickUpdate)
        const updateCatCheckBoxElm = document.querySelector('#cat_update')
        updateCatCheckBoxElm.addEventListener('change', clickCatUpdate)

        $(function () {
            $('.modal').on('show.bs.modal', function (e) {
                toggleCategoryEdit(false)
                toggleDraftEdit(false)
                document.querySelector('.feedback').innerHTML = ''
            });
        })

        function createListElm() {
            let ulHtml = `<ul class="list-group list-group-flush"></ul>`
            let outerUlElm = createElementFromHTML(ulHtml)
            outerUlElm.classList.add('outer-ul')
            fetch('/api/drafts?user_id=' + _this.user.id)
                .then(response => response.json())
                .then(data => {
                    data.forEach(function (cat) {
                        let outerLiElm = createCategoryLi(cat)
                        outerUlElm.appendChild(outerLiElm)
                        let innerUlElm = outerLiElm.querySelector('.inner-ul')
                        cat.drafts.forEach(function (draft) {
                            let innerLiElm = createDraftLiElm(draft)
                            innerUlElm.appendChild(innerLiElm)
                        })
                    })
                })
            return outerUlElm;
        }

        function createCategoryLi(cat) {

            let ulHtml = `  <ul class="list-group list-group-flush inner-ul " ></ul>`

            let outerLiElm = document.createElement('li')
            outerLiElm.style.borderColor = '#007bff'
            outerLiElm.setAttribute('class', 'list-group-item outer-li');
            let title = `<div class="li-title d-flex align-items-center justify-content-between">
                          <h5 class="d-inline-block">${cat.name || 'none category'}</h5>
                         </div>`

            let actions = `<div class="cat-actions d-none">
                           <button class="cat-edit btn-sm btn btn-info">edit</button>
                           <button class="cat-delete  btn-sm btn  btn-danger">delete</button>
                          </div>`

            outerLiElm.appendChild(createElementFromHTML(title))

            if (cat.id !== null && +cat.user_id === _this.user.id) {

                outerLiElm.querySelector('.li-title').appendChild(createElementFromHTML(actions))
                outerLiElm.querySelector('.cat-edit').addEventListener('click', editCategory)
                outerLiElm.querySelector('.cat-delete').addEventListener('click', deleteCategory)

            }

            let innerUlElm = createElementFromHTML(ulHtml)
            //  innerUlElm.setAttribute('data-cat-id', cat.id)

            outerLiElm.setAttribute('data-cat-id', cat.id)
            outerLiElm.setAttribute('data-cat', JSON.stringify(cat))
            outerLiElm.setAttribute('data-cat-name', cat.name)
            outerLiElm.appendChild(innerUlElm);

            return outerLiElm;

        }

        function createDraftLiElm(draft) {

            if (document.querySelector('[data-draft-id="' + draft.id + '"]')) {
                document.querySelector('[data-draft-id="' + draft.id + '"]').remove()
            }

            let innerLiElm = document.createElement('li')
            innerLiElm.setAttribute('class', 'list-group-item d-flex inner-li draft-li justify-content-between align-items-center')
            innerLiElm.setAttribute('data-draft-id', draft.id)
            innerLiElm.setAttribute('data-draft', JSON.stringify(draft))

            innerLiElm.innerHTML = ` <span>${draft.name} </span>
                                          <div class="d-none draft-actions">
                                          <span class="btn btn-sm btn-success draft-import  ">import</span>
                                          </div>`


            if (+draft.user_id === _this.user.id) {
                let deleteAction = `<span class="btn btn-sm btn-danger  draft-delete ml-1 ">delete</span>`;
                let editAction = `<span class="btn btn-sm btn-warning  draft-edit ml-1 ">edit</span>`;

                innerLiElm.querySelector('.draft-actions').appendChild(createElementFromHTML(deleteAction))
                innerLiElm.querySelector('.draft-actions').appendChild(createElementFromHTML(editAction))

                innerLiElm.querySelector('.draft-delete').addEventListener('click', deleteDraft)
                innerLiElm.querySelector('.draft-edit').addEventListener('click', editDraft)

            }

            if (draft.public === '1') {
                let badge = ` <span class="badge badge-info">public</span>`
                innerLiElm.querySelector('span').appendChild(createElementFromHTML(badge))
            }

            innerLiElm.querySelector('.draft-import').addEventListener('click', importDraft)

            return innerLiElm;
        }

        async function createSelectCat() {

            let response = await fetch('/api/drafts/categories?user_id=' + _this.user.id)
            let categories = await response.json()
            let options = '<option value="" >select category</option>'
            categories.forEach(function (value) {
                options += `<option value="${value.id}" >${value.name}</option>`
            })
            let container = document.createElement('div')
            container.setAttribute('class', 'form-group col-auto select_container')
            let select = document.createElement('select')
            select.setAttribute('name', 'draft_cat_id')
            select.setAttribute('class', 'form-control')
            select.setAttribute('id', 'draft_cat_id')
            select.innerHTML = options
            container.appendChild(select)
            return container;
        }

        function canPublicDraft() {
            return _this.user.is_hr_admin
        }

        function canUpdateDraft(draft) {

        }

        function createNewCategory() {

            let draftNameHtml = `  <div class="form-group  col-auto">
                  <input type="text" class="form-control" id="cat_name" name="cat_name" placeholder="Category Name">
                </div>`

            let update = createElementFromHTML(createUpdateCheckboxHtml('cat_update'))
            update.classList.add('d-none')

            let input = document.createElement('input')
            input.setAttribute('type', 'hidden')
            input.setAttribute('name', 'cat_id')
            input.disabled = true

            let feedBack = ` <div class="feedback" id="draft_validation" ></div>`


            let button = `<div class="form-group col-auto">
                <button type="submit" class="btn btn-primary ">save</button>
                   </div>`

            let formElm = document.createElement('form')
            formElm.setAttribute('class', 'form-row align-items-center')
            formElm.setAttribute('id', 'create_category')
            formElm.appendChild(createElementFromHTML(draftNameHtml))
            formElm.appendChild(update)
            formElm.appendChild(createElementFromHTML(button))
            formElm.appendChild(input)
            formElm.appendChild(createElementFromHTML(feedBack))
            return formElm
        }

        async function createNewDraftElm() {

            let selectNode = await createSelectCat()

            let draftNameHtml = `  <div class="form-group  col-auto">
                  <input type="text" class="form-control" id="draft_name" name="draft_name" placeholder="Draft Name">

                </div>`
            let publicCheckBoxHtml = ` <div class="form-group col-auto d-none">
                <div class="form-check">
                 <input class="form-check-input" name="public_draft" type="checkbox" id="public">
                 <label class="form-check-label" for="public">public</label>
                </div>
                </div>`
            let includesMainForm = ` <div class="form-group col-auto ">
                <div class="form-check">
                 <input class="form-check-input"  name="includes_main_form"   type="checkbox" id="includes_main_form" checked>
                 <label class="form-check-label" for="includes_main_form" >includes main form</label>
                </div>
                </div>`

            let updateCheckBoxHtml = `  <div class="form-group col-auto d-none">
                <div class="form-check ">
                 <input class="form-check-input" type="checkbox" name="update" id="update">
                 <label class="form-check-label" for="update">update</label>
                </div>
                </div>`;
            let button = `<div class="form-group col-auto">
                <button type="submit" class="btn btn-primary ">save</button>
                   </div>`
            let feedBack = ` <div class="feedback" id="draft_validation" ></div>`

            let input = document.createElement('input')
            input.setAttribute('type', 'hidden')
            input.setAttribute('name', 'draft_id')
            input.disabled = true


            let formElm = document.createElement('form')
            formElm.setAttribute('class', 'form-row align-items-center')
            formElm.setAttribute('id', 'create_draft')
            formElm.appendChild(createElementFromHTML(draftNameHtml))
            formElm.appendChild(selectNode)
            formElm.appendChild(input)
            formElm.appendChild(createElementFromHTML(updateCheckBoxHtml))
            formElm.appendChild(createElementFromHTML(includesMainForm))

            let publicElm = createElementFromHTML(publicCheckBoxHtml)

            if (canPublicDraft()) {
                publicElm.classList.remove('d-none')

            }
            formElm.appendChild(publicElm)

            formElm.appendChild(createElementFromHTML(button))
            formElm.appendChild(createElementFromHTML(feedBack))

            let newDraftElm = document.createElement('div')
            newDraftElm.appendChild(formElm)

            return newDraftElm
        }

        function createCategory(event) {

            let _event = event
            event.preventDefault()
            let data = new FormData(event.target)
            data.append('user_id', _this.user.id)
            let updated = data.get('cat_update')

            fetch('/api/drafts/categories', {
                method: "post",
                body: data
            }).then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        createCategoryFormElm.querySelector('.feedback').style.display = 'block'
                        createCategoryFormElm.querySelector('.feedback').style.color = 'red'
                        createCategoryFormElm.querySelector('.feedback').innerHTML = data.errors[0]
                        return;
                    }

                    createCategoryFormElm.querySelector('.feedback').style.display = 'block'
                    createCategoryFormElm.querySelector('.feedback').style.color = 'green'
                    createCategoryFormElm.querySelector('.feedback').innerHTML = updated ? 'category updated successfully.' : 'new category created successfully.'

                    setTimeout(function () {
                        createCategoryFormElm.querySelector('.feedback').innerHTML = ''

                    }, 5000)

                    toggleCategoryEdit(null)
                    createCategoryFormElm.querySelector("[name='cat_id']").disabled = true
                    if (updated) {
                        selectCatElm.querySelector('option[value="' + data.id + '"]').innerHTML = data.name
                        document.querySelector('.outer-li[data-cat-id="' + data.id + '"] h5').innerHTML = data.name
                        document.querySelector('.outer-li[data-cat-id="' + data.id + '"]').setAttribute('data-cat-name', data.name)
                    } else {
                        let newOption = new Option(data.name, data.id);
                        selectCatElm.add(newOption)

                        if (document.querySelector('.outer-ul .outer-li[data-cat-id="null"]')) {
                            document.querySelector('.outer-ul .outer-li[data-cat-id="null"]').before(createCategoryLi(data))
                        } else {
                            document.querySelector('.outer-ul').appendChild(createCategoryLi(data))

                        }
                    }

                    _event.target.querySelectorAll('input[name="cat_name"]').forEach(function (input) {
                        input.value = ''
                    })

                })

        }

        function createDraft(event) {
            event.preventDefault()
            let data = new FormData(event.target)
            data.append('user_id', _this.user.id)

            let updated = data.get('update')
            let _event = event
            if (data.get('includes_main_form')) {
                let drafts = new FormData(mainForm)
                for (let pair of data.entries()) {
                    drafts.append(pair[0], pair[1]);
                }
                data = drafts
            }

            fetch('/api/drafts', {
                method: "post",
                body: data,
            }).then(response => response.json())
                .then(data => {

                    if (data.errors) {
                        createDraftFormElm.querySelector('.feedback').style.display = 'block'
                        createDraftFormElm.querySelector('.feedback').style.color = 'red'
                        createDraftFormElm.querySelector('.feedback').innerHTML = data.errors[0]
                        return;
                    }
                    createDraftFormElm.querySelector('.feedback').style.display = 'block'
                    createDraftFormElm.querySelector('.feedback').style.color = 'green'
                    createDraftFormElm.querySelector('.feedback').innerHTML = updated ? 'draft updated successfully.' : 'new draft created successfully.'

                    setTimeout(function () {
                        createDraftFormElm.querySelector('.feedback').innerHTML = ''

                    }, 5000)

                    toggleDraftEdit(false)

                    document.querySelector('li[data-cat-id="' + data.cat_id + '"] ul').appendChild(createDraftLiElm(data))
                    _event.target.querySelectorAll('[name="draft_name"] , [name="draft_cat_id"] ').forEach(function (input) {
                        input.value = ''
                    })
                    _event.target.querySelector('input[name="public_draft"]').checked = null;
                })
        }

        function deleteCategory(event) {

            if (!confirm('Are you sure to delete this category ?')) {
                return;
            }

            let form = new FormData();
            let data = {};
            let liElm = event.target.parentElement.parentElement.parentElement
            if (liElm.querySelector('.inner-li')) {

                if (confirm('Also the templates of this category?' + "\n" + 'If you click on ok, all the templates in this category will be deleted, otherwise, they will be transferred to the none category list')) {
                    form.append('ff', 's')
                    data.includes_drafts = 'on'
                }
            }


            let cat_id = liElm.getAttribute('data-cat-id')
            fetch('/api/drafts/categories/' + cat_id, {
                method: 'delete',
                body: JSON.stringify(data),
                //  body: form,
                headers: {'Content-type': 'application/json; charset=UTF-8'}
                //headers: {'Content-type': 'multipart/form-data'}
            }).then(response => response.json())
                .then(data => {
                    liElm.remove()
                    let innerUl = document.querySelector('.outer-li[data-cat-id="' + null + '"] .inner-ul')
                    data.forEach(function (draft) {
                        toggleCategoryEdit(null)
                        let li = createDraftLiElm(draft)
                        innerUl.appendChild(li);
                    })

                    selectCatElm.querySelector('option[value="' + cat_id + '"]').remove()
                })
        }

        function deleteDraft(event) {

            if (!confirm('are you sure to delete this template ?')) {
                return;
            }
            let liElm = event.target.parentElement.parentElement
            let draft_id = event.target.parentElement.parentElement.getAttribute('data-draft-id')
            fetch('/api/drafts/' + draft_id, {
                method: 'delete',
            }).then(response => {
                liElm.remove()
                toggleDraftEdit(false)
                if (_this.current && _this.current.id === draft_id) {
                    _this.current = null

                }
            })

        }

        function editCategory(event) {

            let cat = event.target.parentElement.closest('.outer-li').getAttribute('data-cat')
            toggleCategoryEdit(cat)

        }

        function editDraft(event) {
            let draft = event.target.parentElement.closest('.inner-li').getAttribute('data-draft')
            toggleDraftEdit(draft)
        }

        function toggleDraftEdit(draft = null) {
            updateCheckBoxElm.checked = !!draft
            if (draft) {
                updateCheckBoxElm.parentElement.closest('.form-group').classList.remove('d-none')

            } else {
                updateCheckBoxElm.parentElement.closest('.form-group').classList.add('d-none')
            }
            setCreateDraftInputs(draft)
        }

        function toggleCategoryEdit(cat = null) {
            updateCatCheckBoxElm.checked = !!cat
            if (cat) {
                updateCatCheckBoxElm.parentElement.closest('.form-group').classList.remove('d-none')

            } else {
                updateCatCheckBoxElm.parentElement.closest('.form-group').classList.add('d-none')
            }
            setCreateCategoryInputs(cat)
        }

        function createUpdateCheckboxHtml(id) {

            return `<div class="form-group col-auto">
                <div class="form-check ">
                 <input class="form-check-input" type="checkbox" name=${id} id=${id}>
                 <label class="form-check-label" for="${id}">update</label>
                </div>
                </div>`;
        }

//        console.log(document.querySelector('select[id="vertical"]').selectedOptions )

        function importDraft(event) {
            let liElm = event.target.parentElement.parentElement
            if (document.querySelector('.my-draft-highlight-li')) {
                document.querySelector('.my-draft-highlight-li').classList.remove('my-draft-highlight-li')
            }

            let draft_id = liElm.getAttribute('data-draft-id')

            liElm.classList.add('my-draft-highlight-li');


            fetch('/api/drafts/' + draft_id)
                .then(response => response.json())
                .then(data => {

                    let draft = JSON.parse(data.draft)

                    _this.current = data;
                    if (+data.user_id === _this.user.id) {
                        toggleDraftEdit(JSON.stringify(data))
                    }
                    if (!draft) {
                        return;
                    }

                    for (let index in draft) {
                        let value = draft[index]

                        if (index === 'interviewers') {
                            interviewer_html(value);
                        }
                        if (index === 'competency') {
                            competency_html(value);
                        }

                        let input = mainForm.querySelector('input[type="number"][name=' + index + '], input[type="text"][name=' + index + '] ,select[name=' + index + '] ')
                        if (input) {
                            input.value = value
                        }
                        if (index === 'vertical') {

                            $('select[id="vertical"]').val(value)
                            $('select[id="vertical"]').trigger('change')
                        }


                        let textarea = mainForm.querySelector('textarea[name=' + index + ']')
                        if (textarea) {
                            textarea.innerHTML = value
                        }
                        let checkbox = mainForm.querySelector('input[type="radio"][name=' + index + '][value="' + value + '"], input[type="checkbox"][name=' + index + '][value="' + value + '"]')
                        if (checkbox) {
                            checkbox.checked = true
                        }
                    }

                    triggerEvent(mainForm.querySelector('#shift_checkbox'), 'change')
                    let departmentELm = mainForm.querySelector('#department')
                    triggerEvent(departmentELm, 'change')
                    triggerEvent(mainForm.querySelector("input[name='is_new']"), 'change')
                    mainForm.querySelector('#level').value = draft.level ?? ''
                    //   mainForm.querySelector('#vertical').value = draft.vertical ?? ''

                })
        }

        function clickUpdate(event) {
            setCreateDraftInputs(false)
        }

        function setCreateDraftInputs(draft) {
            draft = JSON.parse(draft)
            document.querySelector('[name="draft_id"]').disabled = (!draft)
            document.querySelector('[name="draft_id"]').value = (draft) ? draft.id : ''
            document.querySelector('input[name="draft_name"]').value = (draft) ? draft.name : ''
            document.querySelector('select[name="draft_cat_id"]').value = (draft) ? (draft.cat_id || '') : ''
            document.querySelector('input[name="public_draft"]').checked = (draft) ? (draft.public === '1') : false
            //   document.querySelector('input[name="includes_main_form"]').checked = false

        }

        function setCreateCategoryInputs(cat) {
            cat = JSON.parse(cat)

            document.querySelector('[name="cat_id"]').disabled = (!cat)
            document.querySelector('[name="cat_id"]').value = (cat) ? cat.id : ''
            document.querySelector('input[name="cat_name"]').value = (cat) ? cat.name : ''
        }

        function clickCatUpdate(event) {
            setCreateCategoryInputs(false)
        }
    }

    _this.init()
}

function createElementFromHTML(htmlString) {
    var div = document.createElement('div');
    div.innerHTML = htmlString.trim();
    return div.firstChild;
}

function triggerEvent(el, type) {
    // IE9+ and other modern browsers
    if ('createEvent' in document) {
        var e = document.createEvent('HTMLEvents');
        e.initEvent(type, false, true);
        el.dispatchEvent(e);
    } else {
        // IE8
        var e = document.createEventObject();
        e.eventType = type;

        el.fireEvent('on' + e.eventType, e);
    }
}

