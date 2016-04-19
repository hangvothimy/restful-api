<?php
class Evd_Core_Ini
{
    /**
     * Get content of .ini file
     * notice: format file .ini a = "abc"
     * @param string $filename - the name of file that we want get content
     * @param string $section - the name of section in file we focus
     * @param string $variable - get child of section with this variable
     * @return array 
     */
    public static function getIni($filename,$section,$variable='',$cache=true)
    {
            if($cache == true) {
                if($filename != '' && ($section !== '' && $section != null)) {
                    $cache = new Evd_Core_Cache();
                    $results = $cache->getCacheIni($filename, $section, $variable);
                    return $results;
                }else {
                    return false;
                }
            }else {
                return self::getIniInfo($filename, $section, $variable);
            }
        
    }

    public static function getIniInfo($filename,$section,$variable='')
    {
        try {
            if($filename != '' && ($section !== '' && $section != null)) {
                $file   = APPLICATION_PATH.'/configs/'.$filename;
                $config = new Zend_Config_Ini($file, $section);
                $list   = $config->toArray();
                if($variable != '') {
                    if(isset($list[$variable])){
                        return $list[$variable];
                    }else {
                        return '';
                    }
                }else {
                    return $list;
                }
            }else {
                return false;
            }
        }catch(Exception $e) {
            Evd_Core_Log::setLog($e);
            return '';
        }
    }

    /**
     * filter content of .ini file with request
     * @param array $arraylist - array content of file .ini
     * @param Zend_Controller_Request_Abstract $request - the current request
     * @return array 
     */
    public static function filterIniArray($arraylist, Zend_Controller_Request_Abstract $request)
    {
        $controller = $request->getControllerName();
        $action = $request->getActionName();
   
        $results = array();
        $tmp_key = array();
        foreach($arraylist as $key=>$value) {
            if(is_integer($key)) {
                $results[$key] = $value;
            }else {
                $tmp_key = explode('/', $key);
                if($tmp_key[0] == $controller) {
                    if(!isset($tmp_key[1])) {
                        $results[$key] = $value;
                    }else {
                        if($tmp_key[1] == $action) {
                            $results[$key] = $value;
                        }else {
                            if(is_numeric($tmp_key[1])) {
                                $results[$key] = $value;
                            }
                        }
                    }
                }
            }
        }
        return $results;
    }
    
    /**
     * set content of .ini file with isset section 
     * notice: format file .ini a = "abc"
     * @param string $filename - the name of file that we want get content
     * @param string $section - the name of section in file we focus
     * @param string $key - set key for section
     * @param string/array $value - set value for $key
     * @return boolean for can insert is true and false for cannot insert because not isset section
     */
    public function setIni($filename,$section,$key_variable,$value_variable)
    {
        $file       = APPLICATION_PATH.'/configs/'.$filename;
        $isset_section = false;
        $tmp_array = array();
        
        $config_root = new Zend_Config_Ini($file);
        $config_root = $config_root->toArray();        
        
        foreach($config_root as $key => $value) { 
            //convert from object to our format array
            foreach($value as $key1 => $value1) {
                if(is_array($value1)) {
                    foreach($value1 as $key1_inside => $value1_inside) {
                        $tmp_array[$key1."[".$key1_inside."]"] = $value1_inside;
                    }
                    unset($config_root[$key][$key1]);
                    $config_root[$key] = array_merge($tmp_array,$config_root[$key]);
                    unset($tmp_array);
                }    
            }
            
            //set new variable in isset section
            if($key == $section) {
                $isset_section = true;
                if(is_array($value_variable)) {
                    foreach($value_variable as $variable_key_inside => $variable_value_inside) {
                        $config_root[$key][$key_variable."[".$variable_key_inside."]"] = $variable_value_inside;
                    }
                }else {
                    $config_root[$key][$key_variable] = $value_variable;
                }
            }
        }
        
        if($isset_section == false) {
            return false;
        }else {
            $config = new Zend_Config($config_root);
            $writer = new Zend_Config_Writer_Ini(array('config' => $config,'filename' => $file));
            $writer->write();
            return true;
        }        
    }
}
?>