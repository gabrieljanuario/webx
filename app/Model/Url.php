<?php
App::uses('AppModel', 'Model');

class Url extends AppModel {

	public $useTable = 'urls';
	
	public $actsAs = array('Containable');
	
	/*
	public $validate = array(
		'url' => array(
			'rule1' => array(
				'rule' => array('isUnique'),
				'message' => 'Url already exists',
				'allowEmpty' => false,
				'required' => true,
			),
		)
	);
	*/
	
}
