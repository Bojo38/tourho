<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_ranking
 *
 * @author WFMJ7631
 */
class ranking {

    public function __construct($link, $type, $subtype, $rid) {
        $list = array();
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        mysql_select_db($db_name, $link);
        $rkid = 0;
        $query = "SELECT * FROM " . $db_prefix . "ranking WHERE RankType='" . $type . "' AND RankSubType='" . $subtype . "' and Round_idRound=$rid;";
        $result = mysql_query($query);

        if ($result) {
            while ($r = mysql_fetch_assoc($result)) {
                foreach ($r as $key => $value) {
                    $this->$key = $value;
                }
            }
        } else {
            die('Requête invalide : ' . mysql_error());
        }

        $rkid=$this->idRanking;
        
        $query = "SELECT idPosition FROM " . $db_prefix . "position WHERE Ranking_idRanking=$rkid;";
        $result = mysql_query($query);

        $index = 0;
        if ($result) {
            while ($r = mysql_fetch_row($result)) {
                $list[$index++] = new position($link, $r[0]);
            }
        }
        $this->Positions = $list;
    }

    public static function add($tid, $rid, $name, $type, $subtype, $rankings, $criteria, $posneg) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        $idCriteria = '';

        $result = mysql_query("SELECT idCriteria from Criteria where Tournament_idTournament=" . $tid . " AND Name='" . $criteria . "'");
        if ($result) {
            $idCriteria = mysql_result($result, 0);
        }

        $query = "INSERT INTO `$db_name`.`" . $db_prefix . "ranking` "
                . "(`Round_idRound` ,`Name`,`RankType` ,`RankSubType` ,`PosNeg` ,`Criteria_idCriteria`)"
                . "VALUES ('" . $rid . "', '" . addslashes($name) . "', '$type','$subtype', '$posneg', '$idCriteria');";

        echo $query . "<br>";
        $result = mysql_query($query);
        $id = mysql_insert_id($link);
        mysql_close($link);
        return $id;
    }

}
