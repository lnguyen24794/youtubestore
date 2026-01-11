jQuery(document).ready(function ($) {
    $('#youtubestore-manual-sync').on('click', function (e) {
        e.preventDefault();
        var $btn = $(this);
        var $status = $('#youtubestore-sync-status');

        $btn.prop('disabled', true).text('Syncing...');
        $status.html('<span style="color: blue;">Starting sync...</span>');

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'youtubestore_sync'
            },
            success: function (response) {
                if (response.success) {
                    $status.html('<span style="color: green;">' + response.data + '</span>');
                } else {
                    $status.html('<span style="color: red;">Error: ' + response.data + '</span>');
                }
            },
            error: function () {
                $status.html('<span style="color: red;">Server Error.</span>');
            },
            complete: function () {
                $btn.prop('disabled', false).text('Sync Now');
            }
        });
    });
});
