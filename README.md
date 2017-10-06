
#### _Form builder and validator by one rule array_


## Example

```PHP
<?php

error_reporting(-1);
ini_set('display_errors', 'On');

include_once('autoload.php');
include_once('FormExample.php');

$example = new FormExample('POST');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if ($example->load()) {
		// go to success
		echo "<h1 style=\"text-align:center;\">Your POST parameters are valid !</h1>";
		exit();
	}
	else {
		// request has invalid parameters (one at least)
		echo "<h1 style=\"text-align:center;\">Your POST parameters are invalid ! Try again, please.</h1>";
	}
}

// build form
$form = $example->buildForm
(
	$example->getRawAttributes(),
	$example->getValidationErrors()
);

?>
<!DOCTYPE html>
<html>
<head>
<style type="text/css">
body,div {
	text-align:center;
}
</style>
</head>
<body>
<?= "<div style=\"width:100%;\">$form</div>" ?>
<?= $example->css() ?>
<?= $example->js() ?>
</body>
</html>

```
