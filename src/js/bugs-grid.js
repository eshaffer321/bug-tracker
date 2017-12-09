document.addEventListener("DOMContentLoaded", function () {
    let columnDefs = [
        {headerName: "id", field: 'id', hide: true},
        {headerName: "Product", field: "product"},
        {headerName: "Assigned Engineer", field: "engineer"},
        {headerName: "Description", field: "description", editable: true},
        {headerName: "Status", field: 'status', editable: true},
        {headerName: "Date Added", field: "created"}
    ];

    let gridOptions = {
        columnDefs: columnDefs,
        enableFilter: true,
        enableSorting: true,
        enableCellChangeFlash: true,
        animateRows: true,
        sortingOrder: ['desc', 'asc', null],
        singleClickEdit: true,
        onCellValueChanged: function (data) {
            updateBug(data);
        },
        onGridReady: function () {
            gridOptions.api.sizeColumnsToFit();
            gridOptions.api.showLoadingOverlay()
        }
    };

    let eGridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(eGridDiv, gridOptions);

    jsonLoad(function(data) {
        gridOptions.api.setRowData(data);
    })
});

function updateBug(data){
    console.log(data.data);
    $.ajax({
        url: 'bugs/update',
        type: 'post',
        data: {data: data.data},
        success: function(data){
            swal({
                title: 'Success',
                type: 'success',
                text: data
            });
        },
        error: function(data){
            swal({
                title: 'Error',
                type: 'error',
                text: data.responseText
            })
        }
    });
}

function jsonLoad(callback) {
    $.ajax({
        type: "post",
        url: "/bugs/all",
        success: function(data) {
            callback(data)
        },
        error: function(data,text,code) {
            console.log(data);
            swal({
                title: "Error",
                type: "error",
                text: data['responseText'],
            });
        }
    });
}

