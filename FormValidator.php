<?php
namespace forms;


class FormValidator
{
	private $errors		= array();
	private $attributes	= array();
	private $rawattr	= array();
	private $rules		= array();

	public function __construct ($rules)
	{
		$checker = new RuleChecker;

		$checker->validateRules($rules);

		$this->rules = $rules;
	}

	public function loadAttributes ($attributes = array())
	{
		$this->rawattr = $attributes;
		foreach ($this->rules as $attr => $rule) {
			if ($rule['present'] === 'required') $present = true;
			else if ($rule['present'] === 'optional') $present = false;
			else $present = $this->_shouldAttributePresent($attributes, $attr, $rule['present']);
			$this->_loadAttribute($attributes, $attr, $rule, $present);
		}
		return (empty($this->errors));
	}

	public function getRawAttributes ()
	{
		return $this->rawattr;
	}

	public function getValidAttributes ()
	{
		return $this->attributes;
	}

	public function getMappedAttributes ($map = array())
	{
		$attributes = array();
		foreach ($this->attributes as $attribute) {
			$key = key($attribute);
			$attributes[] = [(array_key_exists($key, $map) ? $map[$key] : $key) => $attribute[$key]];
		}
		return $attributes;
	}

	public function getErrors ()
	{
		return $this->errors;
	}

	public function printAttributes ()
	{
		var_dump($this->attributes); echo "\r\n"; var_dump($this->errors); echo "\r\n";
	}

	// the first pass is not pending and the last one is.
	private function _loadAttribute ($attributes, $attr, $rule, $present)
	{
		foreach ($attributes as $name => $value) {
			if ($name !== $attr || empty($value)) continue;
			$value = htmlspecialchars(trim(urldecode($value)));
			$status = $this->_validateAttribute($name, $value, $rule);
			if ($status === true) {
				$this->attributes[$name] = $value;
				return true;
			}
			return $status;
		}
		// not found
		if ($present === true) {
			// it's needed (required)
			$label = $this->_getLabel($attr);
			$this->errors[] = ['attribute' => $attr, 'message' => $label . " is required"];
			return false;
		}
		// else ignore

		return true; // OK
	}

	private function _validateAttribute ($name, $value, $rule)
	{
		if (!settype($value, $rule['type'])) {
			$label = $this->_getLabel($attr);
			$this->errors[] = ['attribute' => $name, 'message' => $label . " has invalid type"];
			return false;
		}
		$type = gettype($value);
		if ($type === 'string') {
			return $this->_validateString($name, $value, $rule['filter']);
		}
		else if ($type === 'float' || $type === 'integer') {
			return $this->_validateNumber($name, $value, $rule['filter']);
		}

		return false;
	}

	private function _shouldAttributePresent ($attributes, $attr, $present)
	{
		if ($present === 'optional') return false;
		if ($present === 'required') return true;
		// value is required conditionally ($present is array of conditions)
		foreach ($present as $cond_attr => $cond_value) {
			foreach ($attributes as $real_attr => $real_value) {
				if ($real_attr !== $cond_attr) continue;
				if ($real_value === $cond_value) return true;
				return false;
			}
		}
		return false;
	}

	private function _validateString ($name, $value, $filter)
	{
		$len = strlen($value);
		if (array_key_exists('min', $filter)) {
			if ($len < $filter['min']) {
				$label = $this->_getLabel($name);
				$this->errors[] = ['attribute' => $name, 'message' => $label . " is too short"];
				return false;
			}
		}
		if (array_key_exists('max', $filter)) {
			if ($len > $filter['max']) {
				$label = $this->_getLabel($name);
				$this->errors[] = ['attribute' => $name, 'message' => $label . " is too long"];
				return false;
			}
		}
		if (array_key_exists('regexp', $filter) && !empty($filter['regexp'])) {
			if (preg_match($filter['regexp'], $value) === 0) {
				$label = $this->_getLabel($name);
				$this->errors[] = ['attribute' => $name, 'message' => $label . " has invalid format"];
				return false;
			}
		}

		return true;
	}

	private function _validateNumber ($name, $value, $filter)
	{
		if (array_key_exists('min', $filter)) {
			if ($value < $filter['min']) {
				$label = $this->_getLabel($name);
				$this->errors[] = ['attribute' => $name, 'message' => $label . " is too small"];
				return false;
			}
		}
		if (array_key_exists('max', $filter)) {
			if ($value > $filter['max']) {
				$label = $this->_getLabel($name);
				$this->errors[] = ['attribute' => $name, 'message' => $label . " is too big"];
				return false;
			}
		}
		if (array_key_exists('regexp', $filter) && !empty($filter['regexp'])) {
			if (preg_match($filter['regexp'], $value) === 0) {
				$label = $this->_getLabel($name);
				$this->errors[] = ['attribute' => $name, 'message' => $label . " has invalid format"];
				return false;
			}
		}

		return true;
	}

	private function _getLabel ($attr)
	{
		if (!empty($this->rules)) {
			foreach ($this->rules as $attr_name => $rule) {
				if ($attr_name !== $attr) continue;
				if (array_key_exists('html', $rule)) {
					if (array_key_exists('label', $rule['html'])) {
						return $rule['html']['label'];
					}
				}
			}
		}
		return $attr;
	}
}
