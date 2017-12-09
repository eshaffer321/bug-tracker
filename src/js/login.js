$( document ).ready(function() {
    $('#signup').on('click', function() {
        let username = $('#signup-username').val();
        let password = $('#signup-password').val();
        let role = $('#role-select').find(":selected").text();

        $.ajax({
            type: "POST",
            url: "signup",
            data: {
                username: username,
                password: password,
                role: role
            },
            success: function (data) {
                swal({
                    title: 'Success!',
                    type: 'success',
                    text: data
                }).then(function() {
                    swal({
                        title: 'Logging in!',
                        text: 'Please wait...',
                        timer: 1000,
                        onOpen: () => {
                            swal.showLoading()
                        }
                    }).then((result) => {
                        logInUser(username, password);
                    })
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                swal({
                    title: "Error",
                    type: 'error',
                    text: XMLHttpRequest.responseText
                });
            }
        });
    });

    $('#login').on('click', function() {
        let username = $('#login-username').val();
        let password = $('#login-password').val();
        $.ajax({
            type: "POST",
            url: "login",
            data: {
                username: username,
                password: password
            },
            success: function (data) {
                swal({
                    title: 'Success!',
                    type: 'success',
                    text: data
                }).then(function() {
                    window.location.reload();
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                swal({
                    title: "Error",
                    type: 'error',
                    text: XMLHttpRequest.responseText
                });
            }
        });
    });

    function logInUser(username, password){
        $.ajax({
            type: "POST",
            url: "login",
            data: {
                username: username,
                password: password
            },
            success: function (data) {
                window.location.reload();
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                swal({
                    title: "Error",
                    type: 'error',
                    text: XMLHttpRequest.responseText
                });
            }
        });
    }

    $('#logout').on('click', function() {
        $.ajax({
            type: "GET",
            url: "logout",
            success: function (data) {
                swal({
                    title: 'Success!',
                    type: 'success',
                    text: data
                }).then(function(){
                    window.location.reload();
                });
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                swal({
                    title: "Error",
                    type: 'error',
                    text: XMLHttpRequest.responseText
                });
            }
        });
    });
});