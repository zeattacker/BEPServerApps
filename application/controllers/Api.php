<?php
require(APPPATH . 'libraries/REST_Controller.php');

class Api extends REST_Controller {

	function kelas_get()
	{
		$id = $this->get('id');
		$section = $this->get('section');
		$type = $this->get('section');


		if($id !== null){
			$kelas = $this->kelasm->get_by('id_kelas', $id);

			if($kelas != null){
				$this->response(["status" => true, "kelas" => $kelas], 200);
			} else {
				$this->response(["status" => false, "message" => "ID Kelas not found"], 400);
			}
		} else if ($section !== null){
			$kelas = $this->kelasm->get_by('section', $section);

			if($kelas != null){
				$this->response(["status" => true, "kelas" => $kelas], 200);
			} else {
				$this->response(["status" => false, "message" => "Section Kelas not found"], 400);
			}
		} else if ($type !== null){
			$kelas = $this->kelasm->get_by('type', $type);

			if($kelas != null){
				$this->response(["status" => true, "kelas" => $kelas], 200);
			} else {
				$this->response(["status" => false, "message" => "Type Kelas not found"], 400);
			}
		} else {
			$kelas = $this->kelasm->get_all();

			$this->response(["status" => true, "kelas" => $kelas], 200);
		}
	}

	function kelas_post()
	{

	}

	function mapel_get()
	{
		$id = $this->get('id');
		$kelas = $this->get('kelas');

		$this->response(["status" => false, "message" => "Mapel doesnt have get api"], 400);
	}

	function mapel_post()
	{
		$data = $this->post();

		if($data != ""){
			$type_kelas = $data['type_kelas'];
			$jenjang = $data['jenjang'];
			$id_kelas = $data['id_kelas'];

			if($type_kelas != null && $jenjang != null){
				$mapel = $this->mapelm->get_many_by(array('type_kelas' => $type_kelas, 'kelas' => $id_kelas, 'jenjang' => $jenjang));
				
				if($mapel != null){
					$this->response(["status" => true, "mapel" => $mapel], 200);
				} else {
					$this->response(["status" => false, "message" => "Data not found."], 400);
				}
			} else {
				$this->response(["status" => false, "message" => "Parameter not found."], 400);
			}
		} else {
			$this->response(["status" => false, "message" => "Post data not found."], 400);
		}	
	}

	function search_get()
	{
		$term = $this->get('term');

		if($term !== NULL){
			
		} else {
			$kelas = $this->kelasm->with('mapel')->get_all();

			$this->response(["status" => true, "kelas" => $kelas], 200);
		}
	}

	//bep

	function users_get()
	{
		$id = $this->get('nid');

		if($id === NULL)
			{
				$users = $this->userm->get_all();

				if($users)
				{
					$this->response(["status" => true, "users" => $users], 200);
				} else {
					$this->response(["status" => false, "message" => "Users not found or empty."], 400);
				}
			} else {
				$users = $this->userm->get_by('nid', $id);

				if($users)
				{
					$this->response($users, 200);
				} else {
					$this->response(["status" => false, "message" => "Users id is not found."], 400);
				}
			}
	}

	function users_post()
	{
		$data = $this->post();

		if($data){
			if($data['act'] == "create"){
				$random_key = $this->randomString(32);
				$insert_id = $this->userm->insert(array('nama' => $data['nama'], 
													'alamat' => $data['alamat'], 
													'dob' => $data['dob'],
													'username' => $data['username'],
													'password' => md5($data['password']),
													'email' => $data['email'],
													'key' => $random_key,
													'is_activated' => 0));

				if($insert_id > 0){
					$this->response(["status" => true, "message" => "User has been created."], 200);
				} else {
					$this->response(["status" => false, "message" => "Create user has been failed"], 400);
				}
			} else if($data['act'] == "update"){
				$update_id = $this->userm->update($data['id'],array('nama' => $data['nama'],
													'alamat' => $data['alamat'],
													'dob' => $data['dob'],
													'username' => $data['username'],
													'password' => $data['password'],
													'email' => $data['email']));

				if($update_id){
					$this->response(["status" => true, "message" => "User has been updated."], 200);
				} else {
					$this->response(["status" => false, "message" => "Update user has been failed"], 400);
				}
			} else if($data['act'] == "login"){
				$password = md5($data['password']);
				$login_id = $this->userm->get_by(array('nid' => $data['nid'], 'password' => $password));

				if($login_id->nid > 0 && $login_id->status == 1){
					$random_key = $this->randomString(30);
					$update_id = $this->userm->update($login_id->nid, array('session' => $random_key));
					if($update_id){
						$this->response(["status" => true, "users" => $login_id, "message" => "Welcome, you are logged in successfully."], 200);
					} else {
						$this->response(["status" => false, "message" => "ERROR! Cant generaete session key."], 400);
					}
				} else {
					$this->response(["status" => false, "message" => "ERROR! Please check your detail correctly."], 400	);
				}
			} else {
				$this->response(["status" => false, "message" => "Action not recognized."], 400);
			}
		} else {
			$this->response(["status" => false, "message" => "No data retrieved."], 400);
		}
	}

	function parkir_get()
	{
		$id = $this->get('id_parkir');

		if($id === NULL)
			{
				$park = $this->parkm->get_all();

				if($park)
				{
					$this->response(["status" => true, "park" => $park], 200);
				} else {
					$this->response(["status" => false, "message" => "Tempat parkir not found or empty."], 400);
				}
			} else {
				$park = $this->parkm->get_by('id_parkir', $id);

				if($park)
				{
					$this->response($park, 200);
				} else {
					$this->response(["status" => false, "message" => "Users id is not found."], 400);
				}
			}
	}

	function parkir_post()
	{

	}

	function log_get()
	{

	}

	function log_post()
	{

	}
}