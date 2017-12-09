$(document).ready(function () {
    $.ajax({
        type: "POST",
        url: "users/engineer",
        contentType: "application/json",
        success: function (data) {
            var options = "<option>...</option>";
            for (var i = 0; i < data.length; i++) {
                options = options + createOption(data[i].id, data[i].username);
            }
            $('#engineers').append(options);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });

    $.ajax({
        type: "POST",
        url: "users/reporters",
        success: function (data) {
            var options = "<option>...</option>";
            for (var i = 0; i < data.length; i++) {
                options = options + createOption(data[i]['id'], data[i]['username'])
            }
            $('#reporters').append(options);
        }
    });

    $.ajax({
        type: "POST",
        url: "products/all",
        success: function (data) {
            let options = "<option>...</option>";
            for (let i = 0; i < data.length; i++) {
                options = options + createOption(data[i]['id'], data[i]['name'])
            }
            $('#products').append(options);
        }
    });

    function createOption(value, data) {
        return "<option value='" + value + "'>" + data + "</option>";
    }

    $('#submit').on('click', function () {
        let eng_id = $('#engineers').val();
        let rep_id = $('#reporters').val();
        let product = $('#products').val();
        let text = $('#message').val();
        swal({
            title: 'Creating a new bug!',
            text: 'Please wait...',
            timer: 1100,
            onOpen: () => {
                swal.showLoading()
            }
        }).then((result) => {
            $.ajax({
                type: "POST",
                url: "/bugs/create",
                data: {
                    eng_id: eng_id,
                    rep_id: rep_id,
                    product_id: product,
                    desc: text,
                },
                success: function(data) {
                    swal({
                        title: "Success",
                        type: "success",
                        text: data,
                    }, function() {
                        window.location.reload();
                    });
                    // window.location.reload();
                },
                error: function() {
                    swal({
                        title: "Error",
                        type: "error",
                        text: "Please contact your administrator"
                    });
                }
            });
        });
    });
});