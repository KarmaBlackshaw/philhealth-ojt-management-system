$(document).ready(function () {
	$('#editTrainee').submit(function (e) {
		e.preventDefault();
		editTrainee();
	});
});

function editTrainee() {
	var data = $('#editTrainee').serializeArray();
	data[data.length] = {
		name: 'editTrainee',
		value: 1
	};
	$.ajax({
		url: '../controllers/InternsController.php',
		method: 'POST',
		data: data,
		success: function (data) {
			var data = JSON.parse(data);
			if (data.bool == false) {
				alertDanger(data.message);
				console.log(data.error);
			} else if (data.bool) {
				alertSuccess(data.message);
			} else {
				alertDanger(data);
			}
		},
		error: function (data) {
			console.log(data);
		}
	});
}

function removeIntern(e) {
	var id = e;
	$.ajax({
		url: '../controllers/InternsController.php',
		method: 'POST',
		data: {
			removeIntern: 1,
			id: id
		},
		success: function (data) {
			var data = JSON.parse(data);
			if (data.bool == false) {
				alertDanger(data.message);
				window.location.href = 'intern.php';
			} else if (data.bool) {
				alertSuccess(data.message);
			} else {
				alertDanger(data);
			}
		},
		error: function (e) {
			console.log(e);
		}
	});
}