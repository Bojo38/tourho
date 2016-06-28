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
class teammatch {

    public $mid = 0;

    function __construct($id, $link) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        mysql_select_db($db_name, $link);

        $query = "SELECT * FROM `$db_name`." . $db_prefix . "teammatch WHERE idTeamMatch=$id";
        $result = mysql_query($query);
        while ($r = mysql_fetch_assoc($result)) {
            foreach ($r as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->mid = $id;

        $this->V1 = 0;
        $this->V2 = 0;
        $this->N = 0;
        $this->Values = array();

        $query = "SELECT idCoachMatch FROM `$db_name`." . $db_prefix . "coachmatch WHERE TeamMatch_idTeamMatch=$id";
        $result = mysql_query($query);
        while ($r = mysql_fetch_row($result)) {
            $query = "SELECT idCoachValue FROM `$db_name`." . $db_prefix . "coachvalue WHERE CoachMatch_idCoachMatch=$r[0]";
            $result2 = mysql_query($query);
            $index = 0;
            while ($r2 = mysql_fetch_row($result2)) {
                $v = new coachvalue($r2[0], $link);
                if ($index == 0) {
                    if ($v->Value1 > $v->Value2) {                        
                        $this->V1 = $this->V1 + 1;
                    } else {
                        if ($v->Value1 < $v->Value2) {
                            $this->V2 = $this->V2 + 1;
                        } else {
                            $this->N = $this->N + 1;
                        }
                    }
                }
                if (!isset($this->Values[$index])) {
                    $this->Values[$index] = $v;
                } else {
                    $this->Values[$index]->Value1 = $this->Values[$index]->Value1 + $v->Value1;
                    $this->Values[$index]->Value2 = $this->Values[$index]->Value2 + $v->Value2;
                }
                $index = $index + 1;
            }
        }
    }

   

}

?>
