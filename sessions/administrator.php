<?php


if(!isset($_SESSION['login']) || $_SESSION['login'] == false){
	header('Location: /On-the-Job/');
	die();
}