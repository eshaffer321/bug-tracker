$( document ).ready(function (){
    $('#user-report').on('click', function() {
        $('#results').empty();
        swal({
            title: 'Generating Report!',
            text: 'Please wait...',
            timer: 1000,
            onOpen: () => {
                swal.showLoading()
            }
        }).then((result) => {
            $.ajax({
                url: "report/user",
                type: "post",
                success: function(data) {
                    console.log(data);
                    let eng = "<h2>Engineer with most bugs: " + data.eng_most_bugs.username + "</h2>";
                    let info = "<h2>Who has " + data.eng_most_bugs.total + " bugs assgined</h2>";
                    let eng_count = "<h2>Amount of Enigineers: " + data.eng_count + "</h2>";
                    let rep_count = "<h2>Amount of Reporters: " + data.reporter_count +"</h2>";
                    $('#results').append(eng + info + eng_count + rep_count);

                },
                error: function(e) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: e.responseText
                    })
                }
            });
        })
    });

    $('#product-report').on('click', function() {
        $('#results').empty();
        swal({
            title: 'Generating Report!',
            text: 'Please wait...',
            timer: 1000,
            onOpen: () => {
                swal.showLoading()
            }
        }).then((result) => {
            $.ajax({
                url: "report/product",
                type: "post",
                success: function(data) {
                    console.log(data);
                    let first = "<h2>Average Price: " + data.average_price.average + "</h2>";
                    let second = "<h2>Total products:  " + data.total_products.total + "</h2>";
                    let third = "<h2>Total products available: " + data.total_available.total + "</h2>";
                    let four = "<h2>Total products unavailable: " + data.total_unavailable.total + "</h2>";
                    $('#results').append(first + second + third + four);
                },
                error: function(e) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: e.responseText
                    })
                }
            });
        });
    });

    $('#bug-report').on('click', function() {
        $('#results').empty();
        swal({
            title: 'Generating Report!',
            text: 'Please wait...',
            timer: 1000,
            onOpen: () => {
                swal.showLoading()
            }
        }).then((result) => {
            $.ajax({
                url: "report/bugs",
                type: "post",
                success: function(data) {
                    console.log(data);
                    let bugTotal = "<h2>Total Bugs: " + data.bug_total.total+ "</h2>";
                    let bugOpens = "<h2>Total Open Bugs: " + data.open_bugs.total + "</h2>";
                    let bugClosed = "<h2>Total Closed Bugs: " + data.closed_bugs.total + "</h2>";
                    $('#results').append(bugTotal + bugOpens + bugClosed);
                },
                error: function(e) {
                    swal({
                        title: 'Error',
                        type: 'error',
                        text: e.responseText
                    })
                }
            });
        });
    });
});