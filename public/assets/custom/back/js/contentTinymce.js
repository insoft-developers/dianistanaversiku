var editor_config = {
    path_absolute: "/",
    selector: 'textarea#content_banner',
    height: 600,
    relative_urls: false,
    forced_root_block : '',
    nonbreaking_force_tab: true,
    // statusbar: false,
    elementpath: false,
    branding: false,
    plugins: [
        'advlist autolink link image lists charmap print preview hr pagebreak',
        'searchreplace wordcount visualblocks code fullscreen insertdatetime media nonbreaking',
        'table emoticons paste help'
    ],
    toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat| insertfile image link media | emoticons pagebreak charmap | fullscreen preview print ',
    menu: {
        file: {
            title: 'File',
            items: 'preview | print'
        },
        view: {
            title: 'View',
            items: 'visualblocks | preview | fullscreen'
        }
    },
    menubar: 'file edit view insert format table',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:12pt }',
    file_picker_callback: function (callback, value, meta) {
        var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
        var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

        // console.log(callback);
        // console.log(value);
        // console.log(meta);

        if (meta.fieldname == "src" || meta.fieldname == "url") {
            var cmsURL = editor_config.path_absolute + 'filemanager?editor=' + meta.fieldname;
            if (meta.filetype == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url: cmsURL,
                title: 'Filemanager',
                width: x * 0.8,
                height: y * 0.8,
                resizable: "yes",
                close_previous: "no",
                onMessage: (api, message) => {
                    callback(message.content);
                }
            });
        } else if (meta.fieldname == "source" || meta.filetype == "media") {
            alert("Please input link or paste link media video or youtube in form Source.!")
        }
    },
    setup: function (e) {
        e.on('change', function () {
            tinymce.triggerSave();
        })
    }  
};

tinymce.init(editor_config);



