document.addEventListener("DOMContentLoaded", function () {
    let columnDefs = [
        {headerName: "Product Name", field: "name"},
        {headerName: "Price", field: "price"},
        {headerName: "Available", field: "status"},
        {headerName: "Date Created", field: "timestamp"}
    ];
    columnDefs.editable = true;

    let gridOptions = {
        columnDefs: columnDefs,
        enableFilter: true,
        enableSorting: true,
        enableCellChangeFlash: true,
        animateRows: true,
        sortingOrder: ['desc', 'asc', null],
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

    $('#unavailable').on('click', function(){
        $.ajax({
            url: 'products/unavailable',
            type: 'post',
            success: function(data){
                gridOptions.api.setRowData(data)
            },
            error: function(error){
                swal({
                    title: 'Error',
                    type: 'error',
                    text: error.responseText
                })
            }
        });
    });

    $('#available').on('click', function(){
        $.ajax({
            url: 'products/available',
            type: 'post',
            success: function(data){
                gridOptions.api.setRowData(data)
            },
            error: function(error){
                swal({
                    title: 'Error',
                    type: 'error',
                    text: error.responseText
                })
            }
        });
    });

    $('#name-submit').on('click', function() {
        let name = $('#name-input').val();
        $.ajax({
            url: 'products/name',
            type: 'post',
            data: {name: name},
            success: function(data) {
                gridOptions.api.setRowData(data)
            },
            error: function(response) {
                swal({
                    title: "Error",
                    type: 'error',
                    text: response.responseText
                })
            }
        })
    });

    $('#price-submit').on('click', function() {
        let price = $('#price-input').val();
        $.ajax({
            url: 'products/price',
            type: "post",
            data: {price:price},
            success: function(data) {
                gridOptions.api.setRowData(data);
            },
            error: function (data) {
                swal({
                    title: 'Error',
                    type: 'error',
                    text: data.responseText
                })
            }
        });

    });
});

function jsonLoad(callback) {
    $.ajax({
        type: "POST",
        url: "products/all",
        success: function (data) {
            callback(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });


}
