<?php

class Lazada_Post {

    public static function getList() {
        try {
            $sql = trim("SELECT * FROM posts");
            $db_read = Zend_Registry::get("DB_READ");
            return $db_read->fetchAll($sql);
        } catch (Exception $e) {
            Lazada_Core_Log::setLog($e);
            return false;
        }
    }

    public static function delete($id) {
        try {
            $sql = "DELETE FROM `posts` WHERE `id` = " . $id;
            $db_write = Zend_Registry::get("DB_WRITE");
            return $db_write->query($sql);
        } catch (Exception $e) {
            Lazada_Core_Log::setLog($e);
            return false;
        }
    }

    public static function add($param) {
        try {
            $sql = " INSERT INTO posts  (
                                        id,
                                        title,
                                        body
                                       )
                                VALUES( 
                                        '" . $param['id'] . "', 
                                        '" . $param['title'] . "', 
                                        '" . $param['body'] . "'
                                       )";

            $db_write = Zend_Registry::get("DB_WRITE");
            $db_write->query($sql);
            return $db_write->lastInsertId();
        } catch (Exception $e) {
            Lazada_Core_Log::setLog($e);
            return false;
        }
    }

    public static function updateOrder($id, $title) {
        try {
            $sql = "UPDATE posts 
                    SET `title` = " . $title . "
                    WHERE id = " . $id;

            $db_write = Zend_Registry::get("DB_WRITE");
            $db_write->query($sql);
            return true;
        } catch (Exception $e) {
            Lazada_Core_Log::setLog($e);
            return false;
        }
    }

    public static function getListPostByTag($tags) {
        try {
            $sql = trim(" SELECT p.* FROM posts p WHERE p.id IN  (SELECT tg.post_id FROM tags_posts tg WHERE tag_id IN ($tags) )");
            $db_read = Zend_Registry::get("DB_READ");
            return $db_read->fetchAll($sql);
        } catch (Exception $e) {
//            Lazada_Core_Log::setLog($e);
            return false;
        }
    }
    
    public static function countPostByTag($tags) {
        try {
            $sql = trim(" SELECT p.* FROM posts p WHERE p.id IN  (SELECT tg.post_id FROM tags_posts tg WHERE tag_id IN ($tags) )");
            $db_read = Zend_Registry::get("DB_READ");
            return count($db_read->fetchAll($sql));
        } catch (Exception $e) {
            return false;
        }
    }
}
