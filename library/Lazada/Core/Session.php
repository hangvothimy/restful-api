<?php
class Lazada_Core_Session extends Zend_Session_Namespace{
	
	public function __construct(){
		//Init namespace
		parent::__construct("Everyday");
	}
	
    private function casttoclass($class, $object)
    {
        return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($class) . ':"' . $class . '"', serialize($object)));
    }
    
    /**
     * store an object into the session
     * @param Object $object
     * @param string $class_name Object type
     * @param string $variable_name object name variable
     */
    public function setObject($object, $class_name, $variable_name) {
        $object                 = $this->casttoclass($class_name, $object);
        $this->$variable_name   = $object;
    }
    
    /**
     * get an object with variable name
     * @param type $variable_name
     * @return Object 
     */
    public function getObject($variable_name) {
        if (isset($this->$variable_name)) {
            return $this->$variable_name;
        }
        return null;
    }
    
    /**
     * unset an object
     * @param string $variable_name  variable name
     */
    public function unsetObject($variable_name){
        unset($this->$variable_name);
    }

    /**
    * Set session user
    * @param Lazada_User_Account $user - object user
    */
	public function setUser(Lazada_User_Account $user){
            $user = $this->casttoclass('Lazada_User_Account', $user);
            $this->user	= $user;		
	}
	
    /**
    * get session user
    * @return Lazada_User_Account 
    */
	public function getUser(){
		return $this->user;
	}
	
	public function setEmployee(Admin_Employee_Account $employee){
            $this->user = $this->casttoclass('Admin_Employee_Account', $employee);
	}
	
    /**
    * get session user
    * @return Admin_Employee_Account 
    */
	public function getEmployee(){
		return $this->user;
	}
	
    /**
    * Set error message
    * @param string $error_message - error message
    */
	public function setError($error_message) {
        if ($this->error != null && is_array($this->error)) {
            array_push($this->error,$error_message);
        } else {
            $this->error = array($error_message);
        }
	}
	
	/**
    * get error message
    * @return array 
    */
	public function getError(){
		return $this->error;
	}
	
        /**
         * get success message
         * @return array 
         */
	public function getSuccess(){
		return $this->success;
	}
	
	public function setSuccess($success_message){
		if ($this->success != null && is_array($this->success)) {
			array_push($this->success,$success_message);
		} else {
			$this->success = array($success_message);
		}
	}
	
    /**
     * empty Error 
     */
	public function emptyError(){
		$this->error	=	array();
	}
	
        /**
         * Set warning message
         * @param string $warning_message - warning message
         */
	public function setWarning($warning_message){
        if ($this->warning != null && is_array($this->warning)) {
            array_push($this->warning,$warning_message);
        } else {
            $this->warning = array($warning_message);
        }	
	}
	
    /**
    * get warning message
    * @return array 
    */
	public function getWarning(){
		return $this->warning;
	}
	
        /**
         * empty warning message
         */
	public function emptyWarning(){
		$this->warning	=	array();
	}
        
    /**
    * Set is login
    * @param int $is_login - 0/1 (not login/is logined)
    */
    public function setIsLogin($is_login){
        if($is_login == 1)
            $this->is_login	= 1;
        else 
            $this->is_login = 0;
	}
	
    /**
    * get is login
    * @return int 0/1 (not login/is logined) 
    */
	public function getIsLogin(){
		return $this->is_login;
	}
        
    /**
    * empty is login
    */
    public function emptyIsLogin(){
        $this->is_login = 0;
	}
        
    /**
    * unset session
    * @param string $session_name - session name
    */
    public function unsetSession($session_name) {
        unset($this->$session_name);
    }
    public function unsetUser() {
        if ($this->user) {
            unset($this->user);
        }
    }
    
    public function storeVariable($name, $value) {
        $this->$name = $value;
    }
    
    public function getVariable($name) {
        return $this->$name;
    }
    
    public function unsetVariable($name) {
        unset($this->$name);
    }
    
    /**
    * Set session user
    * @param Lazada_User_Account $cart - object user
    */
	public function setCart(Lazada_Order_Cart $cart){
            $cart = $this->casttoclass('Lazada_Order_Cart', $cart);
            $this->cart	= $cart;		
	}
	
    /**
    * get session user
    * @return Lazada_User_Account 
    */
	public function getCart(){
		return $this->cart;
	}
    
    public function unsetCart() {
        unset($this->cart);
    }
    
}
