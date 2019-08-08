$(document).ready(function() {
    collegeChart();
    officeChart();
    loadInternSummary();
    $('.table-colleges, .table-offices').DataTable({
        pagingType : "simple",
        responsive : true,
        iDisplayLength : 5,
        bDestroy : true,
        "language": {
            "info": "<small>Showing _START_ to _END_ events of _TOTAL_ events</small>",
            "infoFiltered": "",
            "infoEmpty": "<small>No entries to show</small>"
        }
    });
});

$('#home_row_office').on('click', '.call_modal_print_office', function(){
    var office_id = $(this).data('office_id')
    $('#modal_print_office_id').val(office_id)
    $('#modal_print_office').modal('show')
})

$('#home_row_college').on('click', '.call_modal_print_colleges', function(){
    var school_id = $(this).data('school_id')
    $('#modal_print_school_id').val(school_id)
    $('#modal_print_college').modal('show')
})

function loadInternSummary(){
  $('#tableHomeInternSummary').DataTable( {
     ajax: {
      url: controllers('HomesController'),
      method : 'POST',
      data : {load_intern_summary : 1},
      dataSrc: '',
    },
    "columns": [
      { "data": "name" },
      { "data": "school" },
      { "data": "course" },
      { "data": "office" },
      { "data": "supervisor" },
      { "data": "date_started" },
      { "data": "schedule"},
      { "data": "hours_required" },
      { "data": "earned" },
      { "data": "remaining" }
    ],
    pagingType : "simple",
    responsive : true,
    iDisplayLength : 10,
    bDestroy : true,
    "language": {
        "info": "<small>Showing _START_ to _END_ events of _TOTAL_ events</small>",
        "infoFiltered": "",
        "infoEmpty": "<small>No entries to show</small>"
    }
  });
}

function collegeChart() {
    $.ajax({
        url: controllers('HomesController'),
        method: 'POST',
        data: {
            collegeChart: 1
        },
        dataType: 'JSON',
        success: function(data) {
            var count = data.school.length;
            var background = [];
            var border = [];

            var new_color = randomColor({
                format : 'rgb'
            }); 

            var temp = new_color.substring(')', new_color.length - 1);
            background.push(temp + ", 0.2)");
            border.push(temp + ", 1)");

            var ctx = document.getElementById("collegeChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.school,
                    datasets: [{
                        label: 'Students from Colleges',
                        data: data.count,
                        backgroundColor: background,
                        borderColor: border,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !0
                            }
                        }]
                    }
                }
            })
        },
        error: function(e) {
            console.log(e)
        }
    })
}

function officeChart() {
    $.ajax({
        url: controllers('HomesController'),
        method: 'POST',
        data: {
            officeChart: 1
        },
        dataType: 'JSON',
        success: function(data) {
            var background = [];
            var border = [];

            var new_color = randomColor({
                format : 'rgb'
            }); 

            var temp = new_color.substring(')', new_color.length - 1);
            background.push(temp + ", 0.2)");
            border.push(temp + ", 1)");

            var ctx = document.getElementById("officeChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.office,
                    datasets: [{
                        label: 'Offices',
                        data: data.count,
                        backgroundColor: background,
                        borderColor: border,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: !0
                            }
                        }]
                    }
                }
            })
        },
        error: function() {
            console.log(e)
        }
    })
}