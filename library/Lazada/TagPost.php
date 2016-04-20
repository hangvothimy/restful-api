<?php

class Lazada_TagPost {

    public static function getList() {
        try {
            $sql = trim("SELECT * FROM tags_posts");
            $db_read = Zend_Registry::get("DB_READ");
            return $db_read->fetchAll($sql);
        } catch (Exception $e) {
            Lazada_Core_Log::setLog($e);
            return false;
        }
    }

    public static function delete($id) {
        try {
            $sql = "DELETE FROM `tags_posts` WHERE `id` = " . $id;
            $db_write = Zend_Registry::get("DB_WRITE");
            return $db_write->query($sql);
        } catch (Exception $e) {
            Lazada_Core_Log::setLog($e);
            return false;
        }
    }

    public static function add($param) {
        try {
            if(isset($param['tag_id']) && is_numeric($param['tag_id']) && isset($param['post_id']) && is_numeric($param['post_id'])) {
                $post = Lazada_Post::getPost($param['post_id']);
                if (is_array($post)) {
                    $sql = " INSERT INTO tags_posts  (
                                                tag_id,
                                                post_id
                                               )
                                        VALUES( 
                                                '" . $param['tag_id'] . "', 
                                                '" . $param['post_id'] . "'
                                               )";

                    $db_write = Zend_Registry::get("DB_WRITE");
                    $db_write->query($sql);
                    $db_write->lastInsertId();

                    return $post;
                } else {
//                    Lazada_Core_Log::setLog($e);
                    return false;
                }                
            } else {
//                Lazada_Core_Log::setLog($e);
                return false;
            }
            
        } catch (Exception $e) {
            Lazada_Core_Log::setLog($e);
            return false;
        }
    }

    public static function updateOrder($id, $tag_id, $post_id) {
        try {
            $sql = "UPDATE tags_posts 
                    SET `tag_id` = " . $tag_id . 
                    ", `post_id` = " . $post_id ." WHERE id = " . $id;

            $db_write = Zend_Registry::get("DB_WRITE");
            $db_write->query($sql);
            return true;
        } catch (Exception $e) {
            Lazada_Core_Log::setLog($e);
            return false;
        }
    }

    public static function checkNotExist($tag_id, $post_id) {
        try {
            $post = Lazada_Post::getPost($post_id);
            if($post != false) {
                $sql = "SELECT * FROM tags_posts WHERE tag_id = $tag_id AND post_id = $post_id";

                $db_read = Zend_Registry::get("DB_READ");
                if(count($db_read->fetchAll($sql)) > 0) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
            
        } catch (Exception $e) {
            return false;
        }
    }
}
