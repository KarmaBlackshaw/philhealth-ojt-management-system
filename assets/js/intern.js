$(document).ready(function(){
	load_table_interns();
});

function load_table_interns(){
	$('#table_intern').DataTable( {
	   ajax: {
	    url: controllers('TraineesController'),
	    method : 'POST',
	    data : {loadTraineesTable : 1},
	    dataSrc: '',
	  },
	  "columns": [
	    { "data": "formatted_name" },
	    { "data": "school" },
	    { "data": "course" },
	    { "data": "office" },
	    { "data": "date_started" },
	    { "data": "schedule" },
	    { "data": "hours_required"},
	    { "data": "status"},
	    { "data": "options" }
	  ],
	  pagingType : "simple",
	  responsive : true,
	  iDisplayLength : 25,
	  bDestroy : true,
	  "language": {
	      "info": "<small>Showing _START_ to _END_ events of _TOTAL_ events</small>",
	      "infoFiltered": "",
	      "infoEmpty": "<small>No entries to show</small>"
	  }
	});
}

$('#select_month, #select_year').change(function(){
	var month_name = $('#select_month option:selected').html();
	var month = $('#select_month').val();
	var year = $('#select_year').val();
	var trainee_id = $('#hiddenID').val();

	if(!_.isEmpty(month) && !_.isEmpty(year)){
		$.ajax({
			url : controllers('TraineesController'),
			method : 'POST',
			data : {
				loadDTRTable : 1,
				trainee_id : trainee_id,
				month : month,
				year : year
			}
		})
		.done(function(e){
			$('#internDTRMonth').text(month_name + ' ' + year);
			$('#tbodyDate').html(e);
		})
		.fail(function(e){
			console.log(e)
		})
	}
});

function load_total_hours(id){
	var id = $('#trainee_id').val();
	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {
			load_total_hours : id
		},
		dataType : 'JSON'
	})
	.done(function(e){
		$('#total_time').text(e.total)
	})
	.fail(function(e){
		console.log(e);
	})
}

// School Management
function loadTableSchool(){
	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {
			loadTableSchool : 1
		}
	})
	.done(function(e){
		$('#tbodySchool').html(e);
	})
	.fail(function(e){
		console.log(e);
	})
}

// Load modal edit school
$('#table_school').on('click', '.call_modal_edit_school', function(){
	var school_id = $(this).data('school_id');
	var modal = $('#modalUpdateSchool');
	var school_name = $('#modalUpdateSchool #school_name');
	var school_address = $('#modalUpdateSchool #school_address');

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {preview_edit_school : school_id},
		dataType : 'JSON'
	})
	.done(function(e){
		school_name.val(e[0].school)
		school_address.val(e[0].school_address)
		$('#modalUpdateSchool #school_id').val(school_id)
		modal.modal('show')
	})
})

// Edit school
$('#form_edit_school').on('submit', function(e){
	e.preventDefault();
	var data = $(this).serializeArray();
	data[data.length] = {name : 'edit_school', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		$('#modalUpdateSchool').modal('hide')
		loadTableSchool();
		notify(e.message, e.alert)
	})
	.fail(function(e){
		console.log(e)
	})
})

// Load modal remove school
$('#table_school').on('click', '.call_modal_remove_school', function(){
	var school = $(this).data('school')
	var school_id = $(this).data('school_id')
	var modal = $('#modalRemoveSchool')

	$('#modalRemoveSchool #school_name').html(school)
	$('#modalRemoveSchool #school_id').val(school_id)
	modal.modal('show')
})

// Remove School
$('#form_remove_school').on('submit', function(e){
	e.preventDefault();
	var data = $(this).serializeArray();
	data[data.length] = {name : 'remove_school', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		$('#modalRemoveSchool').modal('hide')
		loadTableSchool();
		notify(e.message, e.alert)
	})
	.fail(function(e){
		console.log(e)
	})
})

// Search School
search('#searchSchool', '#tbodySchool');

// Add School
$('#formAddSchool').on('submit', function(e){
	e.preventDefault();
	var data = $(this).serializeArray();
	data[data.length] = {name : 'add_school', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		loadTableSchool();
		notify(e.message, e.alert);
		$('#modalAddSchool').modal('hide')
	})
	.fail(function(e){
		console.log(e)
	})
})

// Add Office
$('#form_add_office').on('submit', function(e){
	e.preventDefault();
	var data = $(this).serializeArray()
	data[data.length] = {name : 'add_office', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		loadofficeTable();
		$('#modal_add_office').modal('hide')
		notify(e.message, e.alert)
	})
	.fail(function(e){
		console.log(e)
	})
})

// Load office table
function loadofficeTable(){
	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {
			loadofficeTable : 1
		},
		success : function(e){
			$('#tbodyOffice').html(e);
		},
		error : function(e){
			console.log(e);
		}
	});
}

// Call modal edit office
$('#table_office').on('click', '.call_modal_edit_office', function(){
	var office_id = $(this).data('office_id')
	var office = $(this).data('office')
	var modal = $('#modal_edit_office')

	$('#modal_edit_office #office').val(office)
	$('#modal_edit_office #office_id').val(office_id)
	modal.modal('show')
})

// Search Office
search('#searchOffice', '#tbodyOffice');

// Edit Office
$('#form_edit_office').on('submit', function(e){
	e.preventDefault();
	var data = $(this).serializeArray();
	data[data.length] = {name : 'edit_office', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		$('#modal_edit_office').modal('hide');
		loadofficeTable();
		notify(e.message, e.alert)
	})
	.fail(function(e){
		console.log(e)
	})
})

// Call modal remove office
$('#table_office').on('click', '.call_modal_remove_office', function(){
	var office_id = $(this).data('office_id')
	var office = $(this).data('office')
	var modal = $('#modal_remove_office')

	$('#modal_remove_office #office').html(office)
	$('#modal_remove_office #office_id').val(office_id)
	modal.modal('show')
})

// Remove office
$('#form_remove_office').on('submit', function(e){
	e.preventDefault()
	var data = $(this).serializeArray()
	data[data.length] = {name : 'remove_office', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		loadofficeTable();
		$('#modal_remove_office').modal('hide')
		notify(e.message, e.alert)
	})
	.fail(function(e){
		console.log(e)
	})
})

// Load holidas
function loadHolidayTable(){
	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {
			loadHolidayTable : 1
		},
		success : function(data){
			$('#tbodyHoliday').html(data);
		}
	});
}

// Search Office
search('#searchHoliday', '#tbodyHoliday');

// Call modal edit holiday
$('#table_holiday').on('click', '.call_modal_edit_holiday', function(){
	var holiday_id = $(this).data('holiday_id');
	var holiday = $(this).data('holiday');
	var holiday_date = $(this).data('date');
	var modal = $('#modal_edit_holiday');

	$('#modal_edit_holiday #holiday_id').val(holiday_id);
	$('#modal_edit_holiday #holiday').val(holiday);
	$('#modal_edit_holiday #holiday_date').val(holiday_date);
	modal.modal('show')
})

// Edit holiday
$('#form_edit_holiday').on('submit', function(e){
	e.preventDefault();
	var data = $(this).serializeArray();
	data[data.length] = {name : 'edit_holiday', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		loadHolidayTable();
		$('#modal_edit_holiday').modal('hide')
		notify(e.message, e.alert)
	})
	.fail(function(e){
		console.log(e)
	})
})

// Call modal remove holiday
$('#table_holiday').on('click', '.call_modal_remove_holiday', function(){
	var holiday_id = $(this).data('holiday_id');
	var holiday = $(this).data('holiday');
	var modal = $('#modal_remove_holidy');

	$('#modal_remove_holidy #holiday_id').val(holiday_id);
	$('#modal_remove_holidy #holiday_name').html(holiday);
	modal.modal('show')
})

// Remove holiday
$('#form_remove_holiday').on('submit', function(e){
	e.preventDefault();
	var data = $(this).serializeArray();
	data[data.length] = {name : 'remove_holiday', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		loadHolidayTable();
		$('#modal_remove_holidy').modal('hide')
		notify(e.message, e.alert)
	})
	.fail(function(e){
		console.log(e)
	})
})

$('#form_add_holiday').on('submit', function(e){
	e.preventDefault();
	var data = $(this).serializeArray();
	data[data.length] = {name : 'add_holiday', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		loadHolidayTable();
		$('#modal_add_holiday').modal('hide')
		notify(e.message, e.alert)
	})
	.fail(function(e){
		console.log(e)
	})
})

function chartAbsents(){
	var id = $('#hiddenID').val();

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {
			id : id,
			loadTableAbsents : 1
		},
		dataType : 'JSON'
	})
	.done(function(data){
		var date = [];
		var absents = [];
		var overtime = [];
		var undertime = [];
		var tardy = [];

		for(var i in data.date){
			date.push(data.date[i]);
			absents.push(data.absents[i]);
			overtime.push(data.overtime[i]);
			undertime.push(data.undertime[i]);
			tardy.push(data.tardy[i]);
		}

		var ctx = document.getElementById("myChart").getContext('2d');
		var myChart = new Chart(ctx, {
		    type: 'line',
		    data: {
		        labels: date,
		        datasets: [{
		            label: 'Absent',
		            data: absents,
		            backgroundColor: 'rgba(255, 99, 132, 0.2)',
		            borderColor: 'rgba(255, 99, 132,1)',
		            borderWidth: 1
		        },{
		            label: 'Tardy',
		            data: tardy,
		            backgroundColor: 'rgba(54, 162, 235, 0.2)',
		            borderColor: 'rgba(54, 162, 235, 1)',
		            borderWidth: 1
		        },{
		            label: 'Overtime',
		            data: overtime,
		            backgroundColor: 'rgba(255, 206, 86, 0.2)',
		            borderColor: 'rgba(255, 206, 86, 1)',
		            borderWidth: 1
		        },{
		            label: 'Undertime',
		            data: undertime,
		            backgroundColor: 'rgba(75, 192, 192, 0.2)',
		            borderColor: 'rgba(75, 192, 192, 1)',
		            borderWidth: 1
		        }]
		    },
		    options: {
		        scales: {
		            yAxes: [{
		                ticks: {
		                    beginAtZero:true
		                }
		            }]
		        }
		    }
		});
	})
	.fail(function(e){
		console.log(e)
	})
}

function loadTableAbsents(){
	var id = $('#hiddenID').val();

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {
			id : id,
			loadTableAbsents : 1
		},
		dataType : 'JSON'
	})
	.done(function(e){
		var data = '';

		for(i in e.date){
			data += '<tr>'
				data += '<td class="py-2">' + e.date[i] + '</td>'
				data += '<td>' + e.absents[i] + '</td>'
				data += '<td>' + e.tardy[i] + '</td>'
				data += '<td>' + e.overtime[i] + '</td>'
				data += '<td>' + e.undertime[i] + '</td>'
			data += '</tr>'
		}

		$('#tbodyAbsents').html(data)

	})
	.fail(function(e){
		console.log(e)
	})
}

$('#formAddTrainee').submit(function(e){
	e.preventDefault();
	addTrainee();
});

$('#form_add_trainee').on('submit', function(e){
	e.preventDefault();
	var data = $(this).serializeArray();
	data[data.length] = {name : 'add_trainee', value : 1}

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		dataType : 'JSON'
	})
	.done(function(e){
		notify(e.message, e.alert)
		$('#modalAddOJT').modal('hide')
	})
	.fail(function(e){
		console.log(e)
	})
})

function toMinutes(hour, minute){
	var hour = parseInt(hour)
	var minute = parseInt(minute) ? parseInt(minute) : 0

	// return (hour * 60) + minute <= 480 ? 480 :  (hour * 60) + minute
	return (hour * 60) + minute
}

function total(e){
	// var e = String(e).padStart(2, 0)
	var date = $('#hidden_date_' + e).val()
	var min_hr = $('#morning_in_hr_' + e).val()
	var min_min = $('#morning_in_min_' + e).val()
	var mout_hr = $('#morning_out_hr_' + e).val()
	var mout_min = $('#morning_out_min_' + e).val()
	var ain_hr = $('#afternoon_in_hr_' + e).val()
	var ain_min = $('#afternoon_in_min_' + e).val()
	var aout_hr = $('#afternoon_out_hr_' + e).val()
	var aout_min = $('#afternoon_out_min_' + e).val()

	var min = toMinutes(min_hr, min_min)
	var mout = toMinutes(mout_hr, mout_min)
	var ain = toMinutes(ain_hr, ain_min)
	var aout = toMinutes(aout_hr, aout_min)

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {
			get_total_time : 1,
			min : min,
			mout : mout,
			ain : ain,
			aout : aout,
			date : date
		},
		dataType : 'JSON'
	})
	.done(function(data){
		if(data.alert == 'danger'){
			$('#dtr_card #alert_message').html(data.message);
			$('#dtr_card #alert').removeClass('hidden');
		} else{
			$('#dtr_card #alert').addClass('hidden');
			$('#total_' + e).text(data.time);
		}

		$('#remarks_' + e).html(data.remarks);
	})
	.fail(function(e){
		console.log(e)
	})
}

$('#form_dtr').submit(function(e){
	e.preventDefault();
	var trainee_id = $('#hiddenID').val();
	var select_month = $('#select_month').val();
	var select_year = $('#select_year').val();
	var data = $(this).serializeArray();
	data[data.length] = { name: 'select_month', value: select_month };
	data[data.length] = { name: 'select_year', value: select_year };
	data[data.length] = { name: 'form_dtr', value: true };
	data[data.length] = { name: 'trainee_id', value: trainee_id };

	var alert = $('#alert')

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : data,
		beforeSend : function(){
			alertPrimary('<i class="fas fa-spinner fa-spin"></i> Saving data...');
		},
		dataType : 'JSON'
	})
	.done(function(e){
		loadTableAbsents();
		load_table_dtr_summary();
		chartAbsents();

		if(e.alert == 'success'){
			alert.removeClass('alert-danger hidden').addClass('alert-success')
		} else{
			alert.removeClass('alert-success hidden').addClass('alert-danger');
		}

		$('#alert_message').html(e.message);
		// notify(e.alert, e.message)
	})
	.fail(function(e){
		console.log(e)
	})
});

function load_table_dtr_summary(){
	var id = $('#trainee_id').val();

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {load_table_dtr_summary : id},
		dataType : 'JSON'
	})
	.done(function(e){
		var data = '';
		var total = 0;
		_.forEach(e, function(val, key){
			total += val.total;
			data += '<tr>'
				data += '<td>' + val.month +' '+ val.year + '</td>'
				data += '<td><b>' + val.total + '</b></td>'
				data += '<td>' + val.options + '</td>'
			data += '</tr>'
		})

		$('#table_dtr_summary tbody').html(data)
	})
	.fail(function(e){
		console.log(e)
	})
}

// Remove DTR
$('#table_dtr_summary').on('click', '.remove_dtr', function(){
	var month = $(this).data('month');
	var year = $(this).data('year');
	var trainee_id = $(this).data('trainee_id');
	var modal = $('#modal_remove_dtr_summary');

	$('#modal_remove_dtr_summary #month_year').text(month +' '+ year);
	$('#modal_remove_dtr_summary #month').val(month);
	$('#modal_remove_dtr_summary #year').val( year);
	$('#modal_remove_dtr_summary #id').val(trainee_id);
	modal.modal('show')
})

$('#form_remove_dtr_summary').on('submit', function(e){
	e.preventDefault();
	var month = $('#modal_remove_dtr_summary #month').val();
	var year = $('#modal_remove_dtr_summary #year').val();
	var id = $('#modal_remove_dtr_summary #id').val();

	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {
			month : month,
			year : year,
			trainee_id : id,
			removeDTRDate : 1
		},
		dataType : 'JSON'
	})
	.done(function(e){
		chartAbsents();
		load_table_dtr_summary();
		$('#modal_remove_dtr_summary').modal('hide')
		notify(e.message, e.alert)
	})
	.fail(function(e){
		console.log(e);
	})
})

$('#btn_refresh').on('click', function(){
	$.ajax({
		url : controllers('TraineesController'),
		method : 'POST',
		data : {refresh : 1}
	})
	.done(function(e){
		load_table_interns()
	})
	.fail(function(e){
		console.log(e);
	})
})