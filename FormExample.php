<?php
namespace forms;

use forms\Form;

class FormExample extends Form
{
	protected $rules	= [
		'csrfmiddlewaretoken' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'hidden'] ],
		],
		'country' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
		],
		'buyer_first_name' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'text', 'style' => 'width:49%;'], 'label' => 'First Name (buyer)' ],
		],
		'buyer_last_name' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'text', 'style' => 'width:49%;'], 'label' => 'Last Name (buyer)' ],
		],
		'recipient_first_name' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'text', 'style' => 'width:49%;'], 'label' => 'First Name (recipient)' ],
		],
		'recipient_last_name' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'text', 'style' => 'width:49%;'], 'label' => 'Last Name (recipient)' ],
		],
		'address_line1' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'text'], 'label' => 'Address line 1' ],
		],
		'address_line2' => [
					'present' => 'optional', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'text'], 'label' => 'Address line 2' ],
		],
		'address_city' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'text', 'style' => 'width:49%;'], 'label' => 'City' ],
		],
		'address_state' => [
					'present' => ['country' => 'US'], 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
		],
		'address_postcode' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'text', 'style' => 'width:49%;'], 'label' => 'Postal code' ],
		],
		'shipping_method' => [
					'present' => 'required', 'type' => 'integer',
					'filter' => [ 'min' => 1, 'max' => 256, 'regexp' => "" ],
		],
		'recipient_email' => [
					'present' => 'required', 'type' => 'string',
					'filter' => [ 'min' => 2, 'max' => 64, 'regexp' => "" ],
					'html' => [ 'tag' => 'input', 'attributes' => ['type' => 'text'], 'label' => 'Recipient email' ],
		],
	];
	protected $map		= [
		'country'					=> "country",
		'buyer_first_name'			=> 'buyer_first_name',
		'buyer_last_name'			=> 'buyer_last_name',
		'recipient_first_name'		=> 'recipient_first_name',
		'recipient_last_name'		=> 'recipient_last_name',
		'recipient_email'			=> 'recipient_email',
		'address_line1'				=> "address_line1",
		'address_line2'				=> "address_line2",
		'address_city'				=> "address_city",
		'address_state'				=> "address_state",
		'address_postcode'			=> "address_postcode",
		'shipping_method'			=> "shipping_method",
	];


	public function __construct ($method = 'POST')
	{
		parent::__construct($method, $this->rules, $this->map);
	}

	function css ()
	{
		ob_start();
?>
	<style type="text/css">
		.fb_form {
			text-align:center;
			max-width:600px;
			margin:0 auto;
		}
		.fb_error_message {
			color:#f44;
			font-size:12px;
			font-weight:bold;
		}
		.fb_input_field {
			border:solid 1px #AAA;
			color:#666;
			font-size:18px;
			padding:10px;
			margin:10px 0 0 0;
			box-sizing:border-box;
			width:100%;
		}
		.fb_label_field {
			font-size:14px;
			font-weight:bold;
			vertical-align:middle;
		}
		.fb_form_input_container {
			width:100%;
			margin:10px auto;
			display:inline-block;
		}
		.form-row {
			margin:10px 0;
			width:100%;
			text-align:center;
			box-sizing:border-box;
		}
	</style>
<?php
		return ob_get_clean();
	}

	public function js ()
	{
		ob_start();
?>
	<script type="text/javascript">
		function highlightErrors ()
		{
			return false;
		}
	</script>
<?php
		return ob_get_clean();
	}
}

