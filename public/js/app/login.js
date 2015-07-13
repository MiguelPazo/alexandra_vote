$(document).ready(function () {
    $('#formLogin').submit(function (e) {
        e.preventDefault();

        var send = true;

        $(this).find('input').each(function (i, e) {
            if ($(e).val() == '') {
                send = false;
            }
        });

        if (send) {
            var data = $(this).serialize();
            var url = $(this).attr('action');

            $.post(url, data, function (response) {
                if (response.success) {
                    location.href = response.url;
                } else {
                    alert(response.message)
                }
            });
        } else {
            alert('Debe ingresar su codigo UCE!');
        }
    });
});