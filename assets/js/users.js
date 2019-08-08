$('#table_users').on('click', '.call_modal_edit_user', function(){
    var user_id = $(this).data('user_id')
    $('#modal_edit_user #edit_user_id').val(user_id);

    $.ajax({
        url: controllers('UsersController'),
        method: 'POST',
        data: {preview_edit_user : user_id},
        dataType : 'JSON'
    })
    .done(function(e){
        $('#modal_edit_user #edit_user_fname').val(e.fname)
        $('#modal_edit_user #edit_user_mname').val(e.mname)
        $('#modal_edit_user #edit_user_lname').val(e.lname)
        $('#modal_edit_user #edit_user_office_id').val(e.office_id)
        $('#modal_edit_user #edit_user_position').val(e.position)
        $('#modal_edit_user').modal('show')
    })
    .fail(function(e){
        console.log(e)
    })
})

$('#form_edit_user').on('submit', function(e){
    e.preventDefault();
    var data = $(this).serializeArray();
    data[data.length] = {name : 'edit_user', value : 1}

    $.ajax({
        url: controllers('UsersController'),
        method: 'POST',
        data: data,
        dataType : 'JSON'
    })
    .done(function(e){
        $('#modal_edit_user').modal('hide')
        notify(e.message, e.alert)
        loadUserManagerTable()
    })
    .fail(function(e){
        console.log(e)
    })
})

$('#table_users').on('click', '.call_modal_remove_user', function(){
    var user_id = $(this).data('user_id')
    var name = $(this).data('user_name')
    $('#modal_remove_user #remove_user_id').val(user_id);
    $('#modal_remove_user #remove_user_name').html(name);
    $('#modal_remove_user').modal('show')
})

$('#form_remove_user').on('submit', function(e){
    e.preventDefault();
    var data = $(this).serializeArray();
    data[data.length] = {name : 'remove_user', value : 1}
    
    $.ajax({
        url: controllers('UsersController'),
        method: 'POST',
        data: data,
        dataType : 'JSON'
    })
    .done(function(e){
        $('#modal_remove_user').modal('hide')
        notify(e.message, e.alert)
        loadUserManagerTable()
    })
    .fail(function(e){
        console.log(e)
    })
})

function loadUserManagerTable() {
    $.ajax({
        url: controllers('UsersController'),
        method: 'POST',
        data: {
            loadUserManagerTable: 1
        },
        success: function(e) {
            $('#tbodyUserManager').html(e);
        }
    });
}

function removeUser(e) {
    var user_id = e;
    $.ajax({
        url: controllers('UsersController'),
        method: 'POST',
        data: {
            removeUser: 1,
            user_id: user_id
        },
        success: function(e) {
            loadUserManagerTable();
            var data = JSON.parse(e);
            if (data.bool == false) {
                alertDanger(data.message);
            } else if (data.bool) {
                alertSuccess(data.message);
                console.log(data.error);
            } else {
                alertDanger(data);
                console.log(data.error);
            }
        },
        error: function(e) {
            console.log(e);
            console.log(data.error);
        }
    });
}

function addUser() {
    var data = $('#formAddUser').serializeArray();
    $.ajax({
        url: controllers('UsersController'),
        method: 'POST',
        data: data,
        success: function(e) {
            loadUserManagerTable();
            var data = JSON.parse(e);
            if (data.bool == false) {
                alertDanger(data.message);
            } else if (data.bool) {
                alertSuccess(data.message);
                console.log(data.error);
            } else {
                alertDanger(data);
                console.log(data.error);
            }
        },
        error: function(e) {
            console.log(e);
        }
    });
}