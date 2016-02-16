<?php
require(APPPATH . 'libraries/REST_Controller.php');

class Users extends REST_Controller {
	function user_get()
	{
		if(!$this->post('id'))
		{
			$this->response(NULL, 400);
		}

		$user = $this->userm->get_by('id', $this->post('id'));

		if($user){
			$this->response($user, 200);
		} else {
			$this->response(NULL, 404);
		}
	}

	function users_get()
	{
		$users = $this->userm->get_all();

		if($users){
			$this->response($users, 200);
		} else {
			$this->response(NULL, 400);
		}
	}
}