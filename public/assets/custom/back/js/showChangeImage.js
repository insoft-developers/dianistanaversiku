function settingImageData(idSuffix,sizeFile=2) {
    showImgZoom(idSuffix);
    chooseImg(idSuffix);
    changeImg(idSuffix,sizeFile);
    removeImg(idSuffix);
}

function showImgZoom(idSuffix) {
    $("#showImg_"+idSuffix).click(function() {
        Swal.fire({
            imageUrl: $(this).children().attr("src"),
        });
    })
}

function chooseImg(idSuffix) {
    $("#choose_image_"+idSuffix).click(function() {
        $("#file_image_"+idSuffix).click();
    })
}

function changeImg(idSuffix,sizeFile) {
    $("#file_image_"+idSuffix).change(function() {
        readFileImg(document.getElementById("file_image_"+idSuffix),idSuffix,sizeFile);
        $("#is_remove_"+idSuffix).val(0);
    });
}

function removeImg(idSuffix) {
    $("#remove_image_"+idSuffix).click(function() {
        $("#showImg_"+idSuffix).children().attr("src",assetImg_thumbnail);
        $("#file_image_"+idSuffix).val("");
        $("#is_remove_"+idSuffix).val(1);
    })
}

function readFileImg(input,idSuffix,sizeFile=2) {
    var filePath = input.value;

    // console.log(filePath);

    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
    if (!allowedExtensions.exec(filePath)) {
        Swal.fire({
            icon: 'error',
            html: '<u>Gambar: </u> <small style="color:red;">Jenis file yang anda pilih tidak diperbolehkan. <br> Pastikan hanya file yang extension <br> .jpg | .jpeg | .png | .gif</small>',
        });
        input.value = '';
        return false;
    } else if (input.files[0].size > (1048576 * sizeFile)) {
        Swal.fire({
            icon: 'error',
            html: '<u>Gambar: </u> <small style="color:red;">Maksimal ukuran file gambar hanya '+sizeFile+' mb.</small>',
        });
        input.value = '';
        return false;
    } else {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $("#showImg_"+idSuffix).children().attr("src",e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
}