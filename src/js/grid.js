document.addEventListener("DOMContentLoaded", function () {
    var columnDefs = [
        {headerName: "Name", field: "name"},
        {headerName: "Link", field: "full_link"},
        {headerName: "Views", field: "Views"},
        {headerName: "Date Added", field: "time_created"}
    ];

    var gridOptions = {
        columnDefs: columnDefs,
        enableFilter: true,
        enableSorting: true,
        animateRows: true,
        sortingOrder: ['desc', 'asc', null],
        onGridReady: function () {
            gridOptions.api.sizeColumnsToFit();
            gridOptions.api.showLoadingOverlay()
        }
    };

    var eGridDiv = document.querySelector('#myGrid');
    new agGrid.Grid(eGridDiv, gridOptions);

    jsonLoad(function(data) {
        gridOptions.api.setRowData(data);
    })
});


function jsonLoad(callback) {
    var jsonData = {
        "action": "get_bugs"
    };
    $.ajax({
        type: "POST",
        // dataType: "json",
        url: "Controller/DashboardController.php",
        data: JSON.stringify(jsonData),
        contentType: "application/json",
        success: function (data) {
            console.log(data);
            callback(JSON.parse(data));
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(errorThrown);
        }
    });
}
