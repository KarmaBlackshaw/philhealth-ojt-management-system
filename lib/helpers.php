<?php

// Absolute Paths
function abs_base($str){
	return base_dir . $str;
}

function abs_assets($str){
	return assets . $str;
}

function abs_models($str){
	return models . $str . '.php';
}

function abs_views($str){
	return views . $str . '.php';
}

function abs_sessions($str){
	return sessions . $str . '.php';
}

// Relative Paths
function rel_base($str){
	return project_name . $str;
}

function rel_assets($str){
	return project_name . 'assets/' . $str;
}

function rel_models($str){
	return project_name . 'models/' . $str . '.php';
}

function rel_views($str){
	return project_name . 'views/' . $str . '.php';
}

function node($str){
	return project_name . 'node_modules/' . $str;
}

// Helpers
function fullname($name, $format = 'FML'){
	$explode = array_filter(explode('%', $name));

	$fname = $explode[0];
	$lname = end($explode);

	if($format == 'FML'){
		if(!empty($explode[1])){
			$mname = acronym($explode[1]);

			return "$fname $mname. $lname";
		}

		return "$fname $lname";
	}

	if($format == 'LFM'){
		if(!empty($explode[1])){
			$mname = acronym($explode[1]);

			return "$lname, $fname, $mname";
		}

		return "$lname, $fname";
	}
	
}

function validate($arr){
	$error = [];
	$str = "";

	foreach($arr as $data => $i){
		if(empty($i)){
			$explode = explode('_', $data);

			foreach($explode as $var){
				$str .= ucfirst($var) . " ";
			}

			$error[] = "<b>$str</b> is required!";
			$str = "";
		}
	}

	return $error;
}

function acronym($var){
	$temp = preg_replace('/\b(\w)|./', '$1', $var);
	return preg_replace("/(?![A-Z])./", "", $temp);
}