<?php

class Lazada_Core_Routes {
    //put your code here
    public function setRouteIni(){
        /*$route = new Zend_Controller_Router_Route_Regex('archive-news/(\d{4})',
        array('controller' => 'news', 'action' => 'archive')
        );*/
        
        //set ini router
        $ctrl   = Zend_Controller_Front::getInstance();
        $router = $ctrl->getRouter();
        
        $ini     = new Lazada_Core_Ini();
        $sources   = $ini->getIni('uri.ini', 'Routes', 'Uri', false);
        
        foreach($sources as $key => $source){
            $string_input = $key;
            
            $arr_src = explode('/', $source);
            $arr_route['controller'] = $arr_src[0];
            $arr_route['action']     = $arr_src[1];
                
            if(count($arr_src) > 2) {
                for($i=2;$i<count($arr_src);$i++){
                    if($i%2==0){
                        $tmp = $arr_src[$i];
                    }else{
                        $params[$tmp] = $arr_src[$i];
                        $string_input .= '/:'.$tmp;
                    }
                }
            }
            if(isset($params)){
                $arr_route = array_merge($arr_route,$params);
                unset($params);
            }
            
            $route = new Zend_Controller_Router_Route($string_input,$arr_route);
            $router->addRoute($key, $route);
            //Zend_Debug::dump($router);
            unset($arr_route);
            unset($arr_src);
        }
        /*$route = new Zend_Controller_Router_Route('abcd/:a',
            array('controller' => 'quang', 
                  'action' => 'testurl',
                  'a' => '1234' //default value
            ),
            array('a'=>'\w{4}')  //requirement on parameter
        );
        $router->addRoute('routes', $route);*/
    }
}

?>
