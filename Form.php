<?php
namespace forms;

class Form
{
	public $formId		= 'order-form';
	protected $errors	= array();
	protected $rules	= array();
	protected $map		= array();
	protected $method	= 'POST';

	public function __construct ($method = 'POST', $rules = array(), $map = array())
	{
		if (!empty($rules))	$this->rules	= $rules;
		if (!empty($map))	$this->map		= $map;

		$this->validator	= new FormValidator($this->rules);
		$this->builder		= new FormBuilder($this->rules);
	}

	public function load ()
	{
		if ($_SERVER['REQUEST_METHOD'] !== $this->method) {
			$this->errors[] = ['load' => 'Request "' . $this->method . '" method expected but got "' . $_SERVER['REQUEST_METHOD'] . '"'];
			return false;
		}
		$attributes = $this->_getAttributeSource();
		if ($this->loadAttributes($attributes)) {
			return true;
		}
		return false;
	}

	public function loadAttributes ($attributes = array())
	{
		return $this->validator->loadAttributes($attributes);
	}

	public function getRawAttributes ()
	{
		return $this->validator->getRawAttributes();
	}

	public function getValidAttributes ()
	{
		return $this->validator->getValidAttributes();
	}

	public function getValidationErrors ()
	{
		return $this->validator->getErrors();
	}

	public function getBuildErrors ()
	{
		return $this->builder->getErrors();
	}

	public function getAllErrors ()
	{
		return array_merge($this->errors, $this->getValidationErrors());
	}

	public function getErrors ()
	{
		return $this->errors;
	}

	public function getMappedAttributes ($map = array())
	{
		if (empty($map)) $map = $this->map;
		return $this->validator->getMappedAttributes($map);
	}

	public function buildForm ($values = array(), $errors = array())
	{
		return $this->builder->buildForm($this->method, ['id' => $this->formId, 'class' => 'fb_form'], $values, $errors);
	}

	private function _getAttributeSource ()
	{
		switch ($this->method) {
		case 'POST':	return $_POST;
		case 'GET':		return $_GET;
		default:		return array();
		}
	}
}
