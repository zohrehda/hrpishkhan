function dropZone2(options) {
    const mainForm = document.querySelector('#form');

    let defaultOptions = {
        container: '.container',
        inputClassName: 'input-drop'
        //    inputName: 'file',
    }
    let validImgType = ['image/jpeg', 'image/png']
    options = {...defaultOptions, ...options}
    // console.log()
    let attachments = options.attachments
    let _this = this
    let fileList = []
    let removed = []
    this.init = function () {

        let dropZoneOuter = document.createElement('div')
        dropZoneOuter.setAttribute('class', 'dropzone-outer')

        let dropZone = document.createElement('div')
        dropZone.setAttribute('class', 'dropzone')

        let input = document.createElement('input')
        input.setAttribute('name', options.inputName)
        input.setAttribute('type', 'file')
        input.setAttribute('multiple', 'multiple')
        input.setAttribute('class', options.inputClassName)


        document.querySelector(options.container).innerHTML = ""
        dropZoneOuter.appendChild(input)
        dropZoneOuter.appendChild(dropZone)

        document.querySelector(options.container).appendChild(dropZoneOuter)
        if (attachments.length > 0) {
            _this.getImageBlob(attachments)
        }

        dropZone.addEventListener('click', function (event) {
            if (event.target !== this)
                return;
            input.click()
        })

        input.addEventListener('change', function () {
            _this.inputChanged(this)
        })

    }

    this.inputChanged = function (elm) {

        let files = elm.files
        for (let i = 0; i < files.length; i++) {
            let reader = new FileReader();
            fileList.push(elm.files[i]);
            reader.readAsDataURL(elm.files[i]);
            let name = elm.files[i].name
            let type = elm.files[i].type
            let size = elm.files[i].size
            if (size>5990859){
                alert('maximum file size is 6MB')
                return ;
            }
            reader.onload = function (e) {
                let data = e.target.result;
                _this.createImageBlock({isNew: true, src: data, name: name, size: size, type: type})
                document.querySelectorAll("[data-toggle=remove]").forEach(function (item) {
                    item.addEventListener('click', function () {
                        _this.remove(this, elm)
                    })
                })

            };
            elm.files = _this.createFileList(fileList)
        }
    }

    this.createImageBlock = function (data) {
        let isNew = data.isNew ? '1' : '0'
        let size = _this.humanFileSize(data.size)

        let img = document.createElement('img')
        if (validImgType.includes(data.type)) {
            img.setAttribute('src', data.src)
        } else {
            img.setAttribute('src', 'http://hrpishkhan.local/file.png')
        }

        let imgBlock = document.createElement('div')
        imgBlock.setAttribute('class', 'img-block')
        imgBlock.setAttribute('data-file', data.name)
        imgBlock.setAttribute('data-new', isNew)
        if (isNew === '0') {
            imgBlock.setAttribute('data-attachment-id', data.id)
        }
        imgBlock.addEventListener('mouseenter', function () {
            //   imgBlock.firstChild.
        })
        let cover = document.createElement('div')
        let coverOuter = document.createElement('div')
        cover.setAttribute('class', 'cover')
        cover.innerHTML = name + "<br>" + size
        coverOuter.appendChild(cover)
        coverOuter.setAttribute('class', 'cover-outer')
        imgBlock.appendChild(coverOuter)
        let caption = document.createElement('div')
        caption.setAttribute('class', 'caption')
        caption.setAttribute('data-toggle', 'remove')
        caption.innerText = 'remove'
        imgBlock.appendChild(img)
        imgBlock.appendChild(caption)

        document.querySelector('.dropzone').appendChild(imgBlock)


    }

    this.getImageBlob = function (attachments) {

        attachments.forEach(function (attachment) {
            fetch(attachment.url)
                .then(response => response.blob())
                .then(data => {
                    _this.createImageBlock({
                        isNew: false,
                        src: attachment.url,
                        name: 'ff',
                        size: data.size,
                        type: data.type,
                        id: attachment.id
                    })
                    document.querySelectorAll("[data-toggle=remove]").forEach(function (item) {
                        item.addEventListener('click', function () {
                            _this.remove(this)
                        })
                    })
                })
        })
    }

    this.remove = function (elm, input = null) {
        let isNew = elm.parentElement.getAttribute('data-new');
        name = elm.parentElement.getAttribute('data-file')
        elm.parentElement.remove()

        if (isNew === '0') {
            let id = elm.parentElement.getAttribute('data-attachment-id');
            removed.push(id)
            let input = document.createElement('input')
            input.setAttribute('type', 'hidden')
            input.setAttribute('name', 'removed_attachments[]')
            input.value = id
            mainForm.appendChild(input)
            return;
        }

        let filtered = Object.values(input.files).filter(function (value) {
            return (value.name !== name);
        });
        fileList = filtered
        input.files = _this.createFileList(filtered)

    }

    this.createFileList = function (fileList) {

        let list = new DataTransfer();
        fileList.forEach(function (value) {
            list.items.add(value)
        })
        return list.files
    }

    this.humanFileSize = function (bytes, si = false, dp = 1) {
        const thresh = si ? 1000 : 1024;

        if (Math.abs(bytes) < thresh) {
            return bytes + ' B';
        }

        const units = si
            ? ['kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']
            : ['KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        let u = -1;
        const r = 10 ** dp;

        do {
            bytes /= thresh;
            ++u;
        } while (Math.round(Math.abs(bytes) * r) / r >= thresh && u < units.length - 1);


        return bytes.toFixed(dp) + ' ' + units[u];
    }

    this.init()

}
