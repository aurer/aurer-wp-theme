<?php

/*
	Accepted rules: 
		required
		email
		url
		min(int)
		max(int)
		match(field)

*/

class Validator{
	
	public 	$errors=array(),
			$rules=array();
			
	private $_post=array(),
			$_custom_messages=array();

	protected $_never_allowed_str = array(
				'document.cookie'	=> '[removed]',
				'document.write'	=> '[removed]',
				'.parentNode'		=> '[removed]',
				'.innerHTML'		=> '[removed]',
				'window.location'	=> '[removed]',
				'-moz-binding'		=> '[removed]',
				'<script>'			=> '[removed]',
				'</script>'			=> '[removed]',
				'<!--'				=> '&lt;!--',
				'-->'				=> '--&gt;',
				'<![CDATA['			=> '&lt;![CDATA[',
				'<comment>'			=> '&lt;comment&gt;'
			),

			$_never_allowed_regex = array(
				"javascript\s*:"			=> '[removed]',
				"expression\s*(\(|&\#40;)"	=> '[removed]', // CSS and IE
				"vbscript\s*:"				=> '[removed]', // IE, surprise!
				"Redirect\s+302"			=> '[removed]'
			);
	
	/*
		Clean up post data into $this->_post
	*/
	function __construct(){
		foreach($_POST as $key=>$value){
			$this->_post[$key] = $this->_sanitise($value);
		}
	}

	/*
		Add a rule to run with $this->run()
	*/
	public function add_rule($name, $label, $rules){
		$rule = array(
			'name'  => $name,
			'label' => $label,
			'rules' => $rules
		);
		array_push($this->rules, $rule);
	}

	public function custom_message($name, $rule, $message){
		$this->_custom_messages[$name."-".$rule] = $message;
	}

	/*
		Validate all the set rules
		Popullates $this->_errors array
		Returns true if no errors are found, otherwise returns false
	*/
	public function run(){
		foreach ($this->rules as $rule) {
			$name = $rule['name'];
			$label = $rule['label'];
			// Determine the rule and run it
			foreach ($this->_parse_rules($rule['rules']) as $key=>$value) {
				if($key=='required') 	$result[$key] = $this->_required($name, $label);
				if($key=='email') 		$result[$key] = $this->_email($name, $label);
				if($key=='url') 		$result[$key] = $this->_url($name, $label);
				if($key=='min') 		$result[$key] = $this->_minlength($name, $label, $value);
				if($key=='max') 		$result[$key] = $this->_minlength($name, $label, $value);
				if($key=='match') 		$result[$key] = $this->_match($name, $label, $value);
			}
		}
		if(count($this->errors) > 0) return false;
		return true;
	}

	/*
		Returns all errors
	*/
	public function get_errors(){
		return $this->errors;
	}
	
	/*
		Returns the submited value for the specified input
	*/
	public function get_value($input, $fallback=''){
		$output = $this->_post[$input];
		if(empty($output)) $output = $fallback;
		return $output;
	}
	
	/*
		Display the error message for the specified field if there is one
	*/
	public function field_error($field, $before='', $after=''){
		if(!empty($this->errors[$field])){
			echo $before;
			echo $this->errors[$field];
			echo $after;
		}
	}

	/*
		Print a class if the specified item has an error
		Use it to add a class to an inputs container	
	*/
	public function error_class($field, $class='error'){
		if(!empty($this->errors[$field])){
			echo $class;
		}	
	}
	
	/*
		Validate a post parameter: value must not be empty
	*/
	private function _required($name, $label=''){
		if($label=='') $label=$name;
		if(isset($this->_post[$name]) && trim($this->_post[$name]) == ''){
			
			if( isset($this->_custom_messages[$name."-required"]) ){
				$this->errors[$name] = $this->_custom_messages[$name."-required"];
			} else {
				$this->errors[$name] = "The $label field is required";
			}
			return false;
		}
		return true;
	}

	/*
		Validate a post parameter: value must be a valid email address
	*/
	private function _email($name, $label=''){
		if($label=='') $label=$name;
		if(!filter_var($this->_post[$name], FILTER_VALIDATE_EMAIL)){
			if( isset($this->_custom_messages[$name."-email"]) ){
				$this->errors[$name] = $this->_custom_messages[$name."-email"];
			} else {
				$this->errors[$name] = "The $label field requires a valid email address";
			}
			return false;
		}
		return true;
	}

	/*
		Validate a post parameter: value must be a valid url
	*/
	private function _url($name, $label=''){
		if($label=='') $label=$name;
		if(!filter_var($this->_post[$name], FILTER_VALIDATE_URL)){
			if( isset($this->_custom_messages[$name."-url"]) ){
				$this->errors[$name] = $this->_custom_messages[$name."-url"];
			} else {
				$this->errors[$name] = "The $label field requires a valid url stating with http://";
			}
			return false;
		}
		return true;
	}
	
	/*
		Validate a post parameter: value must be less <= $length characters long
	*/
	private function _minlength($name, $label='', $length){
		if($label=='') $label=$name;
		if(strlen($this->_post[$name]) < $length){
			if( isset($this->_custom_messages[$name."-min"]) ){
				$this->errors[$name] = $this->_custom_messages[$name."-min"];
			} else {
				$this->errors[$name] = "The $label field must be at least $length characters or more: $input";
			}
			return false;
		}
		return true;
	}

	/*
		Validate a post parameter: value must be at => $length characters long
	*/
	private function _maxlength($name, $label='', $length){
		if($label=='') $label=$name;
		if(strlen($this->_post[$name]) > $length){
			if( isset($this->_custom_messages[$name."-max"]) ){
				$this->errors[$name] = $this->_custom_messages[$name."-max"];
			} else {
				$this->errors[$name] = "The $label field must be less than $length characters long";
			}
			return false;
		}
		return true;
	}

	/*
		Validate a post parameter: value must be at => $length characters long
	*/
	private function _match($field1, $label='', $field2){
		if($label=='') $label = $field1;
		if($this->_post[$field1] != $this->_post[$field2]){
			if( isset($this->_custom_messages[$field1."-max"]) ){
				$this->errors[$field1] = $this->_custom_messages[$field1."-max"];
			} else {
				$this->errors[$field1] = "This must match the $label field";
			}
			return false;
		}
		return true;
	}

	/*
		Parse the rule string into the rule name and quantifier if it has one
		e.g.  a rule of 'min(20)' returns: array('min'=>20)
			a rule of 'required' returns array('required'=>true)
	*/
	private function _parse_rules($rules){
		$rules 		= explode('|', $rules);
		$set_rules 	= array();

		foreach($rules as $rule){
			// String contains a quantifier e.g. (n)
			if( strstr($rule, '(')!==false ){
				$quantifier = substr($rule, strpos($rule,'(')+1, -1 );
				$rulename = substr($rule, 0, strpos($rule,'('));
				$set_rules[$rulename] = $quantifier;
			}
			else{
				$set_rules[$rule] = true;
			}
		}
		return array_reverse($set_rules);
	}

	/*
		Try to remove nasties from a string
		Loosly based on Codeigniter security
	*/
	protected function _sanitise($str){
		$str = rawurldecode($str);
		foreach ($this->_never_allowed_str as $key => $val){
			$str = str_replace($key, $val, $str);
		}
		foreach ($this->_never_allowed_regex as $key => $val){
			$str = preg_replace("#".$key."#i", $val, $str);
		}
		return $str;
	}
}