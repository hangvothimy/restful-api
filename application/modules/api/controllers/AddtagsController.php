<?php
/**
 * Regular controller
 **/
class Api_AddtagsController extends REST_Controller
{
    /**
     * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
     */
    public function indexAction()
    {   
        $this->view->message = 'indexAction has been called.';
        $this->_response->ok();
    }

    /**
     * The head action handles HEAD requests; it should respond with an
     * identical response to the one that would correspond to a GET request,
     * but without the response body.
     */
    public function headAction()
    {
        $this->view->message = 'headAction has been called';
        $this->_response->ok();
    }

    /**
     * The get action handles GET requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
     */
    public function getAction()
    {
        $tags = $this->_getParam('tags', 0);        
        $posts = $this->_getParam('posts', 0);
        
        $regex = "/(.*)[^,0-9](.*)/";
        $val = new Zend_Validate_Regex(array('pattern'=> $regex));
        if($val->isValid($tags) == false && $val->isValid($posts) == false){
            $tag_arr = explode(',', $tags);  
            $post_arr = explode(',', $posts);
            
            $list_add = array();
            if(is_array($tag_arr) && !empty($post_arr)) {
                foreach ($tag_arr as $tag_id){
                    foreach ($post_arr as $post_id){
                        if (Lazada_TagPost::checkNotExist($tag_id, $post_id) == true) {
                            $param = array('tag_id' => $tag_id , 'post_id' => $post_id);
                            $list      = Lazada_TagPost::add($param);
                            $list_add[] = $list;
                        }                    
                    }
                }
            }
            if(isset($list_add) && is_array($list_add) && !empty($list_add)){
                $this->view->contents = $list_add;
            } else {
                $this->view->errors = 'Error';
            }
        } else {
            $this->view->errors = 'Input wrong';
        }
        
        $this->_response->ok();        
    }

    /**
     * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
     */
    public function postAction()
    {
        $this->view->params = $this->_request->getParams();
        $this->view->message = 'Resource Created';
        $this->_response->created();
    }

    /**
     * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
     */
    public function putAction()
    {
        $id = $this->_getParam('id', 0);

        $this->view->id = $id;
        $this->view->params = $this->_request->getParams();
        $this->view->message = sprintf('Resource #%s Updated', $id);
        $this->_response->ok();
    }

    /**
     * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
     */
    public function deleteAction()
    {
        $id = $this->_getParam('id', 0);

        $this->view->id = $id;
        $this->view->message = sprintf('Resource #%s Deleted', $id);
        $this->_response->ok();
    }
}