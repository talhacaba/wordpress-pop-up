jQuery(document).ready(function($) {
    var popupModal = $('#mpu-modal');

    if (popupModal.length) {
        setTimeout(function() {
            popupModal.addClass('active');
        }, 1500);

        $('#mpu-close').on('click', function() {
            popupModal.removeClass('active');
            
            var date = new Date();
            date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000));
            document.cookie = "mpu_seen=true; expires=" + date.toUTCString() + "; path=/";
        });
    }
});
