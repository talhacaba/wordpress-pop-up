jQuery(document).ready(function($) {
    var custom_uploader;
    var uploadButton = $('#mpu_upload_button');
    var removeButton = $('#mpu_remove_button');
    var imageIdField = $('#mpu_gorsel_id');
    var previewImage = $('#mpu_preview_image');

    uploadButton.on('click', function(e) {
        e.preventDefault();
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
        custom_uploader = wp.media({
            title: 'Pop-up Görseli Seç',
            button: {
                text: 'Bu Görseli Kullan'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            imageIdField.val(attachment.id);
            previewImage.attr('src', attachment.url).show();
            removeButton.show();
            $('#mpu-preview-image').attr('src', attachment.url).show();
        });
        custom_uploader.open();
    });

    removeButton.on('click', function(e) {
        e.preventDefault();
        imageIdField.val('');
        previewImage.attr('src', '').hide();
        $(this).hide();
        $('#mpu-preview-image').attr('src', '').hide();
    });

    $('#mpu_baslik').on('keyup', function() {
        $('#mpu-preview-baslik').text($(this).val());
    });
    $('#mpu_metin').on('keyup', function() {
        $('#mpu-preview-metin').text($(this).val());
    });
});
