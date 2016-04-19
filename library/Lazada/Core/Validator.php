<?php
class Validator {
    public $rules = array();// store rules
    public $data  = array();// store data
            
    public function __construct() {
    }
    
    // set rules
    public function setRules($rules) {
        $this->rules = $rules;
    }
    
    // set data
    public function setData($data) {
        $this->data = $data;
    }
    
    // add a rule
    public function addRule($field, $rules) {
        $this->rules[$field] = $rules;
    }
    
    // add a value
    public function addData($field, $data) {
        $this->data[$field] = $data;
    }
    
    // valid a field with its rules
    private function _validate($data, $rules, $css_class = 'error') {
        $error_message  = '';
        $delimiter      = ', ';
        list($value, $label) = $data;
        foreach ($rules as $rule) {
            if (is_array($rule)) {
                foreach ($rule as $sub_rule => $sub_rule_value) {
                    switch ($sub_rule) {
                    case 'equal':
                        if ($value !== $sub_rule_value)
                            $error_message .= "không khớp".$delimiter;
                        break;
                    case 'minlength': 
                        if (strlen($value) < $sub_rule_value)
                            $error_message .= "ít nhất là $sub_rule_value kí tự".$delimiter;
                        break;
                    case 'maxlength': 
                        if (strlen($value) > $sub_rule_value)
                            $error_message .= "nhiều nhất là $sub_rule_value kí tự".$delimiter;
                        break;
                    case 'max': 
                        if ($value > $sub_rule_value)
                            $error_message .= "giá trị lớn nhất là $sub_rule_value".$delimiter;
                        break;
                    case 'min': 
                        if ($value < $sub_rule_value)
                            $error_message .= "giá trị nhỏ nhất là $sub_rule_value".$delimiter;
                        break;
                    }
                }                
            } else {
                switch ($rule) {
                    case 'required': 
                        if (!$value)
                            $error_message .= "không được để trống".$delimiter;
                        break;
                    case 'email':
                        if (!preg_match('/^([^@\s]+)@((?:[-a-z0-9]+\.)+[a-z]{2,})$/i', $value))
                            $error_message .= "không hợp lệ".$delimiter;
                        break;
                    case 'numeric':
                        if (!is_numeric($value))
                            $error_message .= "phải là dạng số".$delimiter;
                        break;
                }
            }

        }
        if ($error_message) {
            $error_message = rtrim($error_message, $delimiter);
            $error_message = "<div class='$css_class'>$label $error_message</div>";
        }
        return $error_message;
    }
    
    
    public function validate($css_class = 'error') {
        $return_message = '';
        foreach ($this->data as $field => $data_detail) {
            $validate_result = $this->_validate($data_detail, $this->rules[$field], $css_class);
            if ($validate_result)
                $return_message .= $validate_result;
        }
        return $return_message;
    }
}

?>