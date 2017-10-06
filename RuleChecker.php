<?php
namespace forms;


class RuleChecker
{
	public function validateRules ($rules)
	{
		if (gettype($rules) !== 'array') throw new \Exception();
		// validate rules
		foreach ($rules as $attr => $rule) {
			if (!array_key_exists('present', $rule)) {
				throw new \Exception();
			}
			$type = gettype($rule['present']);
			if ($type !== 'array' && $type !== 'string') {
				throw new \Exception();
			}
			if (!array_key_exists('type', $rule)) {
				throw new \Exception();
			}
			$type = gettype($rule['type']);
			if ($type !== 'string') {
				throw new \Exception();
			}
			$rtype = $rule['type'];
			if ($rtype !== 'boolean'
				&& $rtype !== 'string'
				&& $rtype !== 'integer'
				&& $rtype !== 'float'
				&& $rtype !== 'double'
			) {
				throw new \Exception();
			}
			if (array_key_exists('filter', $rule)) {
				if (gettype($rule['filter']) !== 'array') {
					throw new \Exception();
				}
				$filter = $rule['filter'];
				if (array_key_exists('min', $filter) && !is_numeric($filter['min'])) {
					throw new \Exception();
				}
				if (array_key_exists('max', $filter) && !is_numeric($filter['max'])) {
					throw new \Exception();
				}
				if (array_key_exists('regexp', $filter) && gettype($filter['regexp']) !== 'string') {
					throw new \Exception();
				}
			}
		}
	}
}
