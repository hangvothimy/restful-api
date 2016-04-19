<?php
class Lazada_Core_Mail extends Zend_Mail{
	
	public $from_email;
	public $from_name;
	
	public function __construct(){
		try{
			parent::__construct();
			$ini	=	new Zend_Config_Ini(APPLICATION_PATH . "/configs/application.ini","production");
			$this->from_email	=	$ini->resources->mail->defaultFrom->email;
			$this->from_name	=	$ini->resources->mail->defaultFrom->name;
			$this->_charset		=	"utf-8";
		}catch(Exception $e){
			Lazada_Core_Log::setLog($e);
		}
	}
	
    /**
     * send email
     * 
     * @param string $to_email
     * @param string $to_name
     * @param string $subject
     * @param string $body 
     */
	public function sendMail($to_email, $to_name, $subject, $body){
		try {
			$this->setFrom($this->from_email, $this->from_name);
			$this->addTo($to_email, $to_name);
			$this->setSubject($subject);
			$this->setBodyHtml($body);
			$this->send();
		} catch (Exception $e) {
			Lazada_Core_Log::setLog($e);
		}
	}
	
    /**
     * send email which generate by template with param
     * @param type $to_email
     * @param type $to_name
     * @param type $template
     * @param type $params 
     */
	public function sendMailTemplate($to_email, $to_name, $template, $params = array()){
		try {		
            $tpl = Zend_Registry::get("TEMPLATE");
		foreach($params as $key => $value) {
			$tpl->assign($key, $value);
		}
        // fetch the email body
        $body = $tpl->fetch($template);

        // the subject is on the first line, so parse that out
        $lines 		= explode("\n", $body);
        $subject 	= trim(array_shift($lines));
        $body 		= join("\n", $lines);
		$this->sendMail($to_email, $to_name, $subject, $body);
		} catch (Exception $e) {
			Lazada_Core_Log::setLog($e);
		}
	}

}
?>