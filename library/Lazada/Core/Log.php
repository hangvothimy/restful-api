<?php

class Lazada_Core_Log {
    
    /**
     * set file log
     * @param Zend_Exception $exception - exception
     * @param string $exceptionType - the type of exception (objec/text)
     */
    static function setLog(Exception $exception, $exceptionType='')
    {
        $zend_log = new Zend_Log();
        
        $cur_date = date('Y-m-d');
        $log_name = APPLICATION_PATH . '/var/logs/log_'.$cur_date.'.log';
        
        $writer = new Zend_Log_Writer_Stream($log_name);
        $zend_log->addWriter($writer);
        
        $ini = new Lazada_Core_Ini();
        
        try{
            if($exceptionType == '')
                $file   = APPLICATION_PATH.'/configs/log.ini';
                $config = new Zend_Config_Ini($file, 'LogSetting');
                $list   = $config->toArray();
                $exceptionType = $list['ExceptionType'];
                
        }catch (Exception $e){
            $exceptionType = '';
        }
        
        if($exceptionType == 'object') {
            ob_start();
            //var_dump($exception);
            $logexc = ob_get_clean();
            $logexc = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $logexc);
            $zend_log->info($logexc);
        }else {
            $zend_log->debug($exception->getMessage() . "\n" . $exception->getTraceAsString());
        }
    }
    
    static function writeLog($string){
        $zend_log = new Zend_Log();
        
        $cur_date = date('Y-m-d');
        $log_name = APPLICATION_PATH . '/var/logs/log_'.$cur_date.'.log';
        
        $writer = new Zend_Log_Writer_Stream($log_name);
        $zend_log->addWriter($writer);
        $zend_log->info($string);
    }
}
?>
