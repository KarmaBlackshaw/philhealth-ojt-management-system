<?php
require_once dirname(__DIR__) . '/lib/init.php';
use Carbon\Carbon;
$user = new User;

if(isset($_POST['loadUserManagerTable'])) {
    $sql = $user->getUsers();
    foreach ($sql as $data): ?>
		<tr>
			<td class="text-left pl-3">
                <span class="d-block mb-0 font-weight-bold"><?= fullname($data->name); ?></span>
                <span class="small text-muted"><?= $data->position; ?></span>
            </td>
			<td><?= $data->office; ?></td>
			<td><?= Carbon::createFromFormat('Y-m-d', $data->date_created)->diffForHumans(); ?></td>
			<td>
				<div class="btn-group">
					<button class="btn btn-outline-primary btn-sm call_modal_edit_user" data-user_id="<?= $data->user_id; ?>"><i class="fas fa-edit"></i></button>

					<!-- Remove -->
					<button class="btn btn-outline-danger btn-sm" data-toggle="modal" data-target="#modalRemoveUser_<?= $data->user_id; ?>"><i class="fas fa-trash"></i></button>
					<div class="modal fade" id="modalRemoveUser_<?= $data->user_id; ?>">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<div class="modal-title h4">Remove User</div>
									<button class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body text-left">
									<h6>Are you sure to remove <?= $explode[0] . ' ' . $explode[1] . ' ' . $explode[2] ?>?</h6>
								</div>
								<div class="modal-footer">
									<button class="btn btn-primary btn-sm" onclick="removeUser(<?= $data->user_id; ?>)" data-dismiss="modal">Yes</button>
									<button class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
	<?php endforeach;
}

if(isset($_POST['preview_edit_user'])){
    $user_id = $init->inject($_POST['preview_edit_user']);
    $json = [];

    $sql = $user->getUsers($user_id);

    foreach($sql as $data){
        $explode = explode('%', $data->name);
        $json['fname'] = $explode[0];
        $json['mname'] = $explode[1];
        $json['lname'] = $explode[2];
        $json['office_id'] = $data->office_id;
        $json['position'] = $data->position;
    }

    echo json_encode($json);
}

if(isset($_POST['edit_user'])) {
    $user_id = $init->inject($_POST['user_id']);
    $fname = $init->inject($_POST['fname']);
    $mname = $init->inject($_POST['mname']);
    $lname = $init->inject($_POST['lname']);
    $office_id = $init->inject($_POST['office_id']);
    $position = $init->inject($_POST['position']);

    $json['alert'] = 'danger';
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $validate = validate($_POST);

    if(empty($validate)){
        $fullname = "$fname%$mname%$lname";
        $sql = $init->query("UPDATE users SET name = '$fullname', office_id = '$office_id', position = '$position' WHERE user_id = '$user_id'");

        if($sql){
            $audit = $init->audit("Updated user information", $user_id);
            $json['alert'] = 'success';
            $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully updated user!";
        }
        
    } else{
        $json['message'] = "<b>Error!</b> Fields cannot be left empty!";
    }

    $json['error'] = $init->error();

    echo json_encode($json);
}

if(isset($_POST['remove_user'])) {
    $user_id = $init->inject($_POST['user_id']);

    $json['alert'] = 'danger';
    $json['message'] = "<b>Error!</b> Something went wrong! Please contact the administrator!";

    $sql = $init->query("UPDATE users SET removed = 1 WHERE user_id = '$user_id'");

    if($sql) {
        $audit = $init->audit("Removed user", $user_id);
        if($audit) {
            $json['alert'] = 'success';
            $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully removed user!";
        }
    }

    $json['error'] = $init->error();

    echo json_encode($json);
}

if(isset($_POST['hiddenAddUser'])) {
    $date = $init->dateNow();
    $fname = $init->inject($_POST['fname']);
    $mname = $init->inject($_POST['mname']);
    $lname = $init->inject($_POST['lname']);
    $office = $init->inject($_POST['office']);
    $position = ucwords($init->inject($_POST['position']));
    $username = $init->inject($_POST['username']);
    $password = $init->hash($init->inject($_POST['password']));
    $json = array();
    if(empty($fname) || empty($mname) || empty($lname) || empty($office) || empty($position) || empty($username) || empty($password)) {
        $json['bool'] = false;
        $json['message'] = "<b>Error!</b> Cannot leave empty fields!";
        $json['error'] = $init->error();
    } else {
        $fullname = $fname . '%' . $mname . '%' . $lname;
        $count = $init->count("SELECT * FROM users WHERE name = '$fullname' OR username = '$username'");
        if($count > 0) {
            $json['bool'] = false;
            $json['message'] = '<b>Error!</b> User information already exists in the database!';
            $json['error'] = $init->error();
        } else {
            $sql = $init->query("INSERT INTO users(name, office_id, position, username, password, date_created) VALUES ('$fullname', '$office', '$position', '$username', '$password', '$date')");
            $insert_id = $init->insert_id();
            if($sql) {
                $audit = $init->audit("Added new user!", $insert_id);
                if($audit) {
                    $json['bool'] = true;
                    $json['message'] = "<i class='fas fa-thumbs-up fa-lg fa-spin'></i> Successfully registered new user!";
                    $json['error'] = $init->error();
                } else {
                    $json['bool'] = false;
                    $json['message'] = '<b>Error!</b> Failed updating audit trails!';
                    $json['error'] = $init->error();
                }
            } else {
                $json['bool'] = false;
                $json['message'] = '<b>Error!</b> Failed adding new user!';
                $json['error'] = $init->error();
            }
        }
    }
    echo json_encode($json);
}