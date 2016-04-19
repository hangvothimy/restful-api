<?php
class Lazada_Core_Validate extends Zend_Validate
{
    
	//Validate Email
	public static function isEmail($email){ 
            $validate = new Zend_Validate_EmailAddress();
            if ($validate->isValid($email)) {
                return true;
            } else {
                return false;
            }
	}
        //Validate Date
	public static function isDate($date, $type){ 
            $validate   = new Zend_Validate_Date($type);
            if($validate->isValid($date)) {
                return true;
            } else {
                return false;
            }
	}
	
	public static function isIp($ip){
		$validate	=	new Zend_Validate_Ip;
		if($validate->isValid($ip)) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function isInt($integer){
		$validate	=	new Zend_Validate_Int();
		if($validate->isValid($integer)) {
			return true;
		} else {
			return false;
		}
    }
}

?>