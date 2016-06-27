<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_match
 *
 * @author Frederic Berger
 */
class coachmatch {

    public $mid = 0;

    function __construct($id, $link) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        mysql_select_db($db_name, $link);

        $query = "SELECT * FROM `$db_name`." . $db_prefix . "coachmatch WHERE idCoachMatch=$id";
        $result = mysql_query($query);
        while ($r = mysql_fetch_assoc($result)) {
            foreach ($r as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->mid = $id;
    }

    public function getValues($link) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $list = array();

        mysql_select_db($db_name, $link);

        $query = "SELECT idCoachValue FROM `$db_name`." . $db_prefix . "coachvalue WHERE CoachMatch_idCoachMatch=$this->mid";
        $result = mysql_query($query);
        $index = 0;
        while ($r = mysql_fetch_row($result)) {
            $list[$index++] = new coachvalue($r[0], $link);
        }

        return $list;
    }

}

?>
