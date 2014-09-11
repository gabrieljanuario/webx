<?php
App::uses('AppModel', 'Model');

class Emailz extends AppModel {

	public $useTable = 'emails';

	public $validate = array(
		'email' => array(
			'rule1' => array(
				'rule' => array('isUnique'),
				'message' => 'Email already exists',
				'allowEmpty' => false,
				'required' => false,
			),
		),
	);
	
	
}
