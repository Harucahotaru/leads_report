$(document).ready(function () {
    let subButton = $('#subButton');

    subButton.on('click', function () {
        $('#loading').removeClass('d-none')
        $('#successBox').html('')
        $('#errorBox').html('')
        $.ajax({
            url: '/router.php?controller=LeadsReport&action=createReport',
            method: 'post',
            dataType: 'html',
            data:
                {
                    partners: $('#partnerInput').val(),
                    quantity: $('#leads_quantity').val(),
                    date: {
                        up: $('#leads_date_s').val(),
                        to: $('#leads_date_l').val(),
                    },
                },
            success: function (response) {
                $('#loading').addClass('d-none')
                response = jQuery.parseJSON(response);
                if (response['error'] !== '') {
                    $('#errorBox').html(response['error'])
                } else {
                    let filePath = response['file'];
                    let message = response['message'];
                    $('#successBox').html(`<a href=${filePath}>Скачать отчет(${message})</a>`)
                }
            }
        });
    })
});