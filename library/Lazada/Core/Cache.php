<?php
class Lazada_Core_Cache extends Zend_Cache{
    var $cache;
    
    /**
     * construct zend cache
     * @param string $type_cache - type of cache is 'Core' or 'Output'
     * @param string $lifetime - time cache ('unlimited' to cache forever)
     * @param string $variable - get child of section with this variable
     * @return array 
     */
    public function __construct($type_cache='Core',$lifetime=''){
        $ini = new Evd_Core_Ini();
        $cache_ini = $ini->getIniInfo('cache.ini', 'CacheSetting');
        
        if($lifetime == '') {
            $lifetime = $cache_ini['LifeTime'];
        }else if($lifetime=='unlimited') {
            $lifetime = null;
        }
        
        $frontendOptions = array(
           'lifetime' => $lifetime,           // cache lifetime of 30 seconds
           'automatic_serialization' => true  // this is the default anyways
        );
        //$backendOptions = array('cache_dir' => APPLICATION_PATH . '/var/cache/');
        $backendOptions = array('cache_dir' => APPLICATION_PATH . $cache_ini['CacheDir']);
        
        if($type_cache != 'Core')
            $type_cache = 'Output';
            
        $this->cache = $this::factory($type_cache,
                       'File',
                       $frontendOptions,
                       $backendOptions);
    }
    
    public function getCacheIni($filename='',$section='',$variable='') {
        if($filename != '' && $section != '') {
            //create single cache
            return $this->createCacheIni($filename, $section, $variable);      
        }else if ($filename == '' && $section == '' && $variable == '') {
            //create all cache
            
            $handle = opendir(APPLICATION_PATH.'/configs');
            if ($handle) {
                while (false !== ($file = readdir($handle))) {
                    $tmp_file = substr($file, strlen($file)-4,  strlen($file));
                    if($tmp_file == '.ini') {
                        $config = new Zend_Config_Ini(APPLICATION_PATH.'/configs/'.$file);
                        $config = $config->toArray();
                        foreach($config as $key=>$value) {
                            $this->createCacheIni($file, $key); 
                        }
                    }
                }
                closedir($handle);
            }            
            return true;
        }else {
            return false;
        }
    }
    
    private function createCacheIni($filename,$section,$variable='') {
        //load file ini
        //create cache
        $tmp_name = substr($filename, 0,  strlen($filename)-4);
        $cache_name = $tmp_name.'_'.$section.'_'.$variable;
        
        if (($results = $this->cache->load($cache_name)) === false ) {
            $ini = new Evd_Core_Ini();
            $results = $ini->getIniInfo($filename, $section, $variable);
            $this->cache->save($results);
        }
        return $results;
    }
} 

?>