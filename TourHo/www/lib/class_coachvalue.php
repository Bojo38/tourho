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
class coachvalue {

    public $vid = 0;

    function __construct($id, $link) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        mysql_select_db($db_name, $link);

        $query = "SELECT * FROM `$db_name`." . $db_prefix . "coachvalue WHERE idCoachValue=$id";
        $result = mysql_query($query);
        while ($r = mysql_fetch_assoc($result)) {
            foreach ($r as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->vid = $id;
    }

}

?>
