<?php
class Lazada_Core_Page extends Zend_Controller_Action
{
    //Smarty template var
	var $tpl;
	//Global template var
    var $page_data=array();
	
    public function init(){
        //init $tpl
        $this->tpl  = Zend_Registry::get("TEMPLATE");
        $session    = Zend_Registry::get("SESSION");
        //get CSS
        $this->page_data['css']     = $this->getCSS($this->getRequest());
        
        //get Js
        $this->page_data['js']      = $this->getJS($this->getRequest());

        //get Title
        $this->page_data['title']   = $this->getTitle();
        //get Meta tag
        $this->page_data['meta']    = $this->getMeta();

		//get key of page
		$this->page_data['page']    = $this->getPageStyle($this->getRequest());
		
		$this->page_data['is_login']= $session->is_login;
        
		if($session->is_login == 1 && isset($session->user)){
			$this->page_data['user']['fullname'] 	= $session->user->fullname;
			$this->page_data['user']['email'] 		= $session->user->email;
		}
		
		$this->page_data['left']	= 0;
		$this->page_data['right']	= 0;
		
		$this->tpl->assign('page_data', $this->page_data);
		
        // display popup
        $this->page_data['allow_popup'] = 0;
        if (!$session->have_seen_popup) {
            $session->have_seen_popup = 1;
            $this->page_data['allow_popup'] = 1;
        }
    }
    /*
	*
	*
	*/
    public function getCSS(Zend_Controller_Request_Abstract $request) {
        $core_ini       = new Lazada_Core_Ini;
        $css_setting    = $core_ini->getIni('design.ini', 'CSSSettings', 'CSSFileList');
        $css_setting    = $core_ini->filterIniArray($css_setting, $request);
        return $css_setting;
    }
	
    /*
	*
	*/
    public function getJS(Zend_Controller_Request_Abstract $request){
        //read design.ini get js file list
        $core_ini   = new Lazada_Core_Ini;
        $js_setting = $core_ini->getIni('design.ini', 'JSSettings', 'JSFileList');
        $js_setting = $core_ini->filterIniArray($js_setting, $request);
        return $js_setting;
    }
	
    /*
	* Get Title default in site.ini / SiteSettings / Title
	* 
	*
	*/
    public function getTitleDefault(){
        $ini	=	new Lazada_Core_Ini;
        $title	=	$ini->getIni('site.ini', 'SiteSettings', 'Title');
        return $title;
    }
	
    /*
	* Set Title 
	* 
	*
	*/
    public function setTitle($title_string = ''){
        if ($title_string != '') {
            $this->page_data['title']	=	$title_string . " - " . $this->getTitleDefault();
        } else {
            $this->page_data['title']	=	$this->getTitleDefault();
        }
        return $this->page_data['title'];
    }
	
    /*
	* Get Title of page to assign page_data
	*
	*
	*/
    public function getTitle($title_string = ''){
        return $this->setTitle($title_string);	
    }

    
    /**
     * get default meta declare in site.ini
     * @return type 
     */
    public function getMetaDefault(){
        $ini	=	new Lazada_Core_Ini;
        $meta	=	$ini->getIni('site.ini','SiteSettings','Meta');	
        return $meta;	
    }
    
    /**
     * set meta
     * @param array $meta 
     */
    public function setMeta($meta = null) {
        if (is_array($meta)) {
            $this->page_data['keywords']     = $meta['keywords'];
            $this->page_data['description']  = $meta['description'];
        } else {
            $meta                            = $this->getMetaDefault();
            $this->page_data['keywords']     = $meta['keywords'];
            $this->page_data['description']  = $meta['description'];
        }
    }
    
    /**
     * get meta from array
     * @param array $meta 
     */
    public function getMeta($meta = null) {
        $this->setMeta($meta);
    }
    /*
     * get key of PageSettings in design.ini to assign for page_data
     * 
     */
    public function getPageStyle(Zend_Controller_Request_Abstract $request) {
        //read design.ini get key 
        $core_ini       = new Lazada_Core_Ini;
        $page_setting   = $core_ini->getIni('design.ini', 'PageSettings', 'PageList');
        $page_setting   = $core_ini->filterIniArray($page_setting, $request);
        foreach($page_setting as $page_name) {
            return $page_name;
        }
        
    }
    
    public function setBeforeColumns($template, $params = array()){

            foreach($params as $key => $value){
                    $this->page_data[$key] = $value;
            }

            $this->tpl->assign('page_data',$this->page_data);
            $this->page_data['before_content'] = $this->tpl->fetch($template);
            $this->tpl->assign('page_data',$this->page_data);
    }

    public function setRightColumns($template, $params = array()){
            $this->page_data['right'] = 1;

            foreach($params as $key => $value){                
                    $this->page_data[$key] = $value;
            }

            $this->tpl->assign('page_data',$this->page_data);
            $this->page_data['right_content'] = $this->tpl->fetch($template);
            $this->tpl->assign('page_data',$this->page_data);
    }
	
	public function setLeftColumns($template, $params = array()){
            $this->page_data['left'] = 1;

            foreach($params as $key => $value){
                    $this->page_data[$key] = $value;
            }

            $this->tpl->assign('page_data',$this->page_data);
            $this->page_data['left_content'] = $this->tpl->fetch($template);
            $this->tpl->assign('page_data',$this->page_data);
    }
}

?>
