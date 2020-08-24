<?php


class Logout {
	public function __constructor(){
		session_destroy();
		header('location:index.php');
	}
}