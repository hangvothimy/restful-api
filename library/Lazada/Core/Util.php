<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Util
 *
 * @author admin
 */
class Lazada_Core_Util {
    
    public static function limitString($str, $limit, $detail_string = '...') {
        if(strlen($str) <= $limit) {
            return $str;
        }
        else{
            if(strpos($str," ",$limit) > $limit){
                $new_limit =strpos($str," ",$limit);
                $new_str   = substr($str,0,$new_limit).$detail_string;
                return $new_str;
            }
            $new_str = substr($str,0,$limit).$detail_string;
            return $new_str;
        }
    }
    
    public static function getDayList() {
        $day_list = array();
        for ($i = 1; $i <= 31; ++$i) {
            $day_list[$i] = $i; 
        }
        return $day_list;
    }
    
    public static function getMonthList() {
        $month_list = array();
        for ($i = 1; $i <= 12; ++$i) {
            $month_list[$i] = "Tháng $i"; 
        }
        return $month_list;
    }
    
    public static function getYearList() {
        $year_list = array();
        for ($i = 1950; $i <= 2010; ++$i) {
            $year_list[$i] = $i; 
        }
        return $year_list;
    }
        
    public static function generatePassword($plainText, $salt = null){
        $shal_length = Lazada_Core_Ini::getIni('constant.ini', 'Password', 'SaltLength');
		if ($salt === null)
			$salt = substr(md5(uniqid(rand(), true)), 0, $shal_length);
		else
			$salt = substr($salt, 0, $shal_length);
		return $salt . sha1($salt . $plainText);
	}

    /**
     * Create update sql from table name, param array and condition
     * @param string $table_name
     * @param array $param array(field1 => value1, field2 => value2, .... )
     * @param array $str_param array('fullname', 'email', ...) contain the fields has datatype is string
     * @param type $str_cond conditon of sql update
     * @return string sql create
     */
    public static function createUpdateSql($table_name, $param, $str_param, $str_cond) {        
        $str_update = '';
        $delimiter = ", ";
        foreach ($param as $field => $field_value) {
            if (in_array($field, $str_param)) {
                $str_update .= "$field = '$field_value'".$delimiter;
            } else {
                $str_update .= "$field = $field_value".$delimiter;
            }
        }
        $str_update = rtrim($str_update, $delimiter);
        $sql_update = "UPDATE $table_name
                       SET $str_update
                       WHERE $str_cond";
        return $sql_update;
    }
    
    public static function createInsertSql($table_name, $param, $str_param) {
        $sql_insert = '';
        $delimiter  = ", ";
        $field_str = '';
        $value_str = '';
        foreach ($param as $field => $field_value) {
            $field_str .= $field.$delimiter;
            if (in_array($field, $str_param)) {
                $value_str .= "'$field_value'".$delimiter;
            } else {
                $value_str .= "$field_value".$delimiter;
            }
        }
        $field_str = rtrim($field_str, $delimiter);
        $value_str = rtrim($value_str, $delimiter);
        
        $sql_insert = "INSERT INTO $table_name ($field_str) VALUE($value_str)";
        return $sql_insert;
    }
    
    public static function stripUnicode($str) {
        if (!$str) {
            return false;
        }
        
        $arr_unicode = array(
            'a' => array('á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ặ', 'ằ', 'ẳ', 'ẵ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ'),
            'A' => array('Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ặ', 'Ằ', 'Ẳ', 'Ẵ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ'),
            'd' => array('đ'),
            'D' => array('Đ'),
            'e' => array('é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ'),
            'E' => array('É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ'),
            'i' => array('í', 'ì', 'ỉ', 'ĩ', 'ị'),
            'I' => array('Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị'),
            'o' => array('ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ'),
            'O' => array('Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ'),
            'u' => array('ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự'),
            'U' => array('Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự'),
            'y' => array('ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ'),
            'Y' => array('Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ')
        );

        foreach ($arr_unicode as $non_unicode => $unicode)
        {
            foreach ($unicode as $value)
            {
                $str = str_replace($value, $non_unicode, $str);
            }
        }

        return $str;
    }

    /**
     * hàm chuyển chuỗi utf thành chuỗi iso
     *
     * @param mixed $str : chuỗi cần chuyển thành iso
     * @return chuỗi đã được chuyển
     *
     */
    public static function unicodeToIso($str) {
        if (!$str) {
            return false;
        }

        $utf_code = array(
            'á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ặ', 'ằ', 'ẳ', 'ẵ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ',
            'Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ặ', 'Ằ', 'Ẳ', 'Ẵ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ',
            'đ',
            'Đ',
            'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ',
            'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ',
            'í', 'ì', 'ỉ', 'ĩ', 'ị',
            'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị',
            'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ',
            'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ',
            'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự',
            'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự',
            'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ',
            'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ'
        );

        $iso_code = array(
            '&aacute;', '&agrave;', '&#7843;', '&atilde;', '&#7841;', '&#259;', '&#7855;', '&#7863;', '&#7857;', '&#7859;', '&#7861;', '&acirc;', '&#7845;', '&#7847;', '&#7849;', '&#7851;', '&#7853;',
            '&Aacute;', '&Agrave;', '&#7842;', '&Atilde;', '&#7840;', '&#258;', '&#7854;', '&#7862;', '&#7856;', '&#7858;', '&#7860;', '&Acirc;', '&#7844;', '&#7846;', '&#7848;', '&#7850;', '&#7852;',
            '&#273;',
            '&#272;',
            '&eacute;', '&egrave;', '&#7867;', '&#7869;', '&#7865;', '&ecirc;', '&#7871;', '&#7873;', '&#7875;', '&#7877;', '&#7879;',
            '&Eacute;', '&Egrave;', '&#7866;', '&#7868;', '&#7864;', '&Ecirc;', '&#7870;', '&#7872;', '&#7874;', '&#7876;', '&#7878;',
            '&iacute;', '&igrave;', '&#7881;', '&#297;', '&#7883;',
            '&Iacute;', '&Igrave;', '&#7880;', '&#296;', '&#7882;',
            '&oacute;', '&ograve;', '&#7887;', '&otilde;', '&#7885;', '&ocirc;', '&#7889;', '&#7891;', '&#7893;', '&#7895;', '&#7897;', '&#417;', '&#7899;', '&#7901;', '&#7903;', '&#7905;', '&#7907;',
            '&Oacute;', '&Ograve;', '&#7886;', '&Otilde;', '&#7884;', '&Ocirc;', '&#7888;', '&#7890;', '&#7892;', '&#7894;', '&#7896;', '&#416;', '&#7898;', '&#7900;', '&#7902;', '&#7904;', '&#7906;',
            '&uacute;', '&ugrave;', '&#7911;', '&#361;', '&#7909;', '&#432;', '&#7913;', '&#7915;', '&#7917;', '&#7919;', '&#7921;',
            '&Uacute;', '&Ugrave;', '&#7910;', '&#360;', '&#7908;', '&#431;', '&#7912;', '&#7914;', '&#7916;', '&#7918;', '&#7920;',
            '&yacute;', '&#7923;', '&#7927;', '&#7929;', '&#7925;',
            '&Yacute;', '&#7922;', '&#7926;', '&#7928;', '&#7924;'
        );
        return str_replace($utf_code, $iso_code, $str);
    }

    /**
     * ConvertString::remove_space()
     * hàm xoá các khoảng trắng dư thừa trong chuỗi
     *
     * @param mixed $str : chuỗi cần xoá khoảng trắng
     * @return chuỗi đã được xoá khoảng trắng
     *
     */
    public static function removeSpace($str) {
        if (!$str) {
            return false;
        }

        $str = trim($str);
        while (substr_count($str, '  ')) {
            $str = str_replace('  ', ' ', $str);
        }
        return $str;
    }

    /**
     * create_random_string Create random string 
     * 
     * @param int $max_length Max length of string
     * @return string Random string with max_lenght given
     */
    public static function createRandomString($min_length, $max_length) {
        $leng       = mt_rand($min_length, $max_length);
        $str        = '0123456789abcdefghiklmnopqrstxyz';
        $return_str = '';
        for ($i = 0; $i < $leng; $i++) {
           $return_str .= substr($str, mt_rand(0, strlen($str)-1), 1);
        }
        return $return_str;
    }

    /**
     * strip_special_character Strip specal character for creating alias
     * 
     * @param string $str String you want strip
     * @return string String has no special character
     */
    public static function  stripSpecialCharacter($str) {
        $special_char = array('`', '~', '!', '#', '$', '%', '^', '&', '*',
                              '(', ')', '-', '_', '=', '+', '\\', '|', '{',
                              '}', ':', ';', '\'', '"', '<', ',', '>', '.', '/', '?', '–', '“', '”');
        foreach($special_char as $value) {
            $str = str_replace($value, "", $str);
        }
        return $str;
    }
    
    public static function implodeSearchParam($param) {
        $return_str = "";
        foreach ($param as $key => $value ) {
            $return_str .= "$key=$value&";
        }
        return rtrim($return_str, "&");
    }
    
    public static function explodeSearchParam($str_param) {
        $return_param   = array();
        $param          = explode("&", $str_param);
        if($param){
        foreach ($param as $value) {
            $tmp_arr                    = explode("=", $value);
            $return_param[$tmp_arr[0]]  = $tmp_arr[1];
        }
        }
        return $return_param;
    }
    
    public static function getPaging($total_item, $page = 1, $num_view = 10, $paging_view = 9) {
        $page_info = array();

        # current page
        if (!$page) {
            $page = 1;
        }

        // $num_page: calculate total page
        $num_page = ceil($total_item / $num_view);

        if ($num_page == 1) {
            return $page_info;
        }

        if ($num_page <= $paging_view) {
            $begin  = 1;
            $end    = $num_page;
        } else {
            $begin  = $page - (int) ($paging_view / 2);
            $end    = $page + (int) ($paging_view / 2);

            if ($begin <= 0) {
                $begin  = 1;
                $end    = $paging_view;
            } elseif ($end > $num_page) {
                $end    = $num_page;
                $begin  = $end - $paging_view + 1;
            }
        }

        if ($begin > 1) {
            $page_info['first'] = 1;
            $page_info['prev']  = $page - 1;
        } elseif ($page > 1) {
            $page_info['prev']  = $page - 1;
        }

        $i = $begin;
        # declare an array which contain page number 2, 3, 4...
        $page_num_array = array();
        while ($i <= $end) {
            $page_num_array[] = $i;
            $i++;
        }
        $page_info['page_num_array'] = $page_num_array;
        if ($end < $num_page) {
            $page_info['next'] = $page + 1;
            $page_info['last'] = $num_page;
        } elseif ($page < $num_page) {
            $page_info['next'] = $page + 1;
        }

        return $page_info;
    }
}
?>
