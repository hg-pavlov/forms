<?php
namespace forms;


class FormBuilder
{
	public $class_error_message			= "fb_error_message";
	public $class_input_field			= "fb_input_field";
	public $class_label_field			= "fb_label_field";
	public $class_form_input_container	= "fb_form_input_container";

	private $errors		= array();
	private $attributes	= array();
	private $rules		= array();
	private $values		= array();

	public function __construct ($rules)
	{
		$checker = new RuleChecker;

		$checker->validateRules($rules);

		$this->rules = $rules;
	}

	public function getErrors ()
	{
		return $this->errors;
	}

	public function setValues ($values = array())
	{
		$this->values = $values;
	}

	public function build ($values = array(), $errors = array())
	{
		foreach ($this->rules as $attribute => $rule) {
			if (!array_key_exists('html', $rule)) continue;
			$value = ((array_key_exists($attribute, $values))? $values[$attribute] : "");
			$err = array();
			foreach ($errors as $error) {
				if ($error['attribute'] === $attribute) {
					$err[] = $error['message']; break;
				}
			}
			$this->fields[] = $this->buildField($attribute, $rule, $value, $err);
		}
	}

	public function buildField ($attribute, $rule, $value, $error)
	{
		if (!array_key_exists('tag', $rule['html'])) {
			$this->errors[] = ['attribute' => $attribute, 'message' => "Cannot build form field: no any tag defined"];
			return "";
		}

		switch ($rule['html']['tag']) {
		case 'input':
			return $this->inputField($attribute, $rule['present'], $rule['filter'], $rule['html'], $value, $error);
		}
		return "";
	}

	public function label ($attribute, $val)
	{
		return "<label for=\"$attribute\" class=\"" . $this->class_label_field . "\" >$val</label>";
	}

	public function getErrorMessage ($error)
	{
		$message = ""; foreach ($error as $msg) { $message .= $msg . "<br />"; }
		return $message;
	}

	public function inputField ($attribute, $present, $filter, $html, $value, $error)
	{
		$html_attr = "";
		$html_label = "";
		$style = "";
		if (isset($html['label']) && !empty($html['label'])) {
			$html_label = $this->label($attribute, $html['label']);
		}
		foreach ($html['attributes'] as $attr => $val) {
			// style for container
			if ($attr === "style") { $style = $val; continue; }
			$html_attr .= " " . $attr . "=\"" . $val . "\"";
		}
		$html_attr .= (($present === 'required')? ' required="required" ' : "");
		$html_attr .= ((!array_key_exists('class', $html['attributes']))? " class=\"" . $this->class_input_field . "\" " : "");

		$field = "<input id=\"" . $attribute . "\" name=\"" . $attribute . "\"" . $html_attr . " value=\"" . $value . "\" />";

		if (isset($html['attributes']['type']) && $html['attributes']['type'] !== 'hidden') {
			$error_msg = "&emsp;<span class=\"" . $this->class_error_message . "\">" . $this->getErrorMessage($error) . "</span>";
			$field = "<div class=\"" . $this->class_form_input_container . "\" " . (($style)? "style=\"$style;\"" : "") . ">"
						. $html_label . $field . $error_msg . "</div>";
		}

		return $field;
	}

	public function buildForm ($method = 'POST', $attr = array(), $values = array(), $errors = array())
	{
		$attributes = "";
		foreach ($attr as $a => $v) {
			$attributes .= $a . "=\"" . $v . "\" ";
		}
		$this->build($values, $errors);
		$fields = implode("\n", $this->fields);
		$submit = "<button type=\"submit\">Submit</button>";
		$form = "<form action=\"\" method=\"" . $method . "\" " . $attributes . " >" . $fields . "\n" . $submit . "\n</form>\n";
		return $form;
	}
}
