const base = '/On-the-Job';

function controllers(str){
	return base + '/controllers/' + str + '.php';
}

function views(str){
	return base + '/views/' + str + '.php';
}

function search(input, body){
    $(input).on("input", function() {
      var value = $(this).val().toLowerCase();
      $(body + " tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
}


$.notifyDefaults({
    allow_dismiss: true,
    delay: 1,
    animate: {
        enter: 'animated fadeIn',
        exit: 'animated rollOut',
        showProgressbar: true
    }
});

function notify(message, type){
	$.notify({
	    message: message
	}, {
	    type: type
	});
}

function alertSuccess(e) {
    $.notify({
        message: e
    }, {
        type: 'success'
    });
}

function alertPrimary(e) {
    $.notify({
        message: e
    }, {
        type: 'primary'
    });
}

function alertDanger(e) {
    $.notify({
        message: e
    }, {
        type: 'danger'
    });
}

function alertWarning(e) {
    $.notify({
        message: e
    }, {
        type: 'warning'
    });
}

function alertDark(e) {
    $.notify({
        message: e
    }, {
        type: 'dark'
    });
}

function alertLight(e) {
    $.notify({
        message: e
    }, {
        type: 'light'
    });
}

function manageProfile() {
    var data = $('#formManageProfile').serializeArray();

    $.ajax({
        url: controllers('HomesController'),
        method: 'POST',
        data: data,
        dataType : 'JSON'
    })
    .done(function(e){
        if (e.bool == false) {
            alertDanger(e.message);
        } else if (e.bool) {
            alertSuccess(e.message);
        } else {
            alertDanger(e);
        }
    })
    .fail(function(e){
        console.log(e);
    })
}