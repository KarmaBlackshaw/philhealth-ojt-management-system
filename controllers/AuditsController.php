<?php
 require_once dirname(__DIR__) . '/lib/init.php';

 if(isset($_POST['loadAuditsTable'])){

 	$sql = $init->getQuery("SELECT * FROM audit_trails audit JOIN users ON audit.user_id = users.user_id ORDER BY audit.audit_id DESC");

 	foreach($sql as $data): ?>
		<tr>
			<td><?=$data->audit_id;?></td>
			<td><?=fullname($data->name);?></td>
			<td><?=$data->action;?></td>
			<td><?=$data->object;?></td>
			<td><?=$init->date($data->date_time);?></td>
			<td><?=$init->time($data->date_time);?></td>
		</tr>
	<?php endforeach;
}