<?php

class Kelas_model extends MY_Model {
	public $_table = 'kelas';

	public $has_many = array('mapel');
}