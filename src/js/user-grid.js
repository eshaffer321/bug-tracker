document.addEventListener("DOMContentLoaded", function () {
    let columnDefs = [
        {headerName: "ID", field: "id"},
        {headerName: "Username", field: "username", editable: true},
        {headerName: "Role", field: "role", editable: true},
        {headerName: "Signed In", field: "signed_in", editable: true}
    ];
    columnDefs.editable = true;

    let gridOptions = {
        columnDefs: columnDefs,
        enableFilter: true,
        enableSorting: true,
        enableCellChangeFlash: true,
        animateRows: true,
        sortingOrder: ['desc', 'asc', null],
        singleClickEdit: true,
        onCellValueChanged: function (data) {
            updateUser(data);
        },

        onGridReady: function () {
            gridOptions.api.sizeColumnsToFit();
            gridOptions.api.showLoadingOverlay();
        }
    };

    let eGridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(eGridDiv, gridOptions);

    jsonLoad(function (data) {
        gridOptions.api.setRowData(data);

    });

    $.ajax({
        url: "users/all",
        type: "post",
        success: function(data) {
            let options = "";
            for (let i = 0; i < data.length; i++) {
                options = options + createOption(data[i]['id'], data[i]['username']);
            }
            $('#user-dropdown').append(options);
        },
        error: function() {
            swal({
                title: "Error",
                type: 'error',
                text: "Something wen't wrong when getting users"
            })
        }
    });

    $('#submit').on('click', function() {
        let id = $('#user-dropdown').val();
        let username = $('#user-dropdown').find(":selected").text();
        swal({
            title: 'Are you sure?',
            text: "Delete " + username,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '/users/delete',
                    type: 'post',
                    data: {
                        id: id,
                        username: username
                    },
                    sucess: function() {},
                    error: function(data) {
                        console.log(data);
                        swal({
                            title: "Error",
                            type: "error",
                            text: data.responseText
                        });
                    }
                });
                swal({
                    title: 'Deleted!',
                    text: username + ' has been deleted.',
                    type: 'success',
                    timer: 1200

                }).then((result) => {
                    window.location.reload();
                });
            }
        })
    });

    function createOption(value, data) {
        return "<option value='" + value + "'>" + data + "</option>";
    }
});

function updateUser(data) {
    $.ajax({
        url: 'users/update',
        type: "POST",
        data: data.data,
        success: function(data) {
            swal({
                title: 'Successs',
                type: 'success',
                text: data,
            })
        },
        error: function(m) {
            swal({
                title: "Error",
                type: 'error',
                text: m,
            });
        }
    });
}

function jsonLoad(callback) {
    $.ajax({
        type: "POST",
        url: "users/all",
        success: function (data) {
            callback(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}
