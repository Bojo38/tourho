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
class team {

    public $teid = 0;

    function __construct($id) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        $query = "SELECT * FROM `$db_name`." . $db_prefix . "team WHERE teid=$id";
        $result = mysql_query($query);
        while ($r = mysql_fetch_assoc($result)) {
            foreach ($r as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->teid = $id;
        mysql_close($link);
    }

    public function getCoachs() {
        $list = array();
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);
        $query = "SELECT cid FROM " . $db_prefix . "coach WHERE f_teid=$this->teid";
        $result = mysql_query($query);
        $index = 0;
        while ($r = mysql_fetch_row($result)) {
            $list[$index++] = new coach($r[0]);
        }
        //mysql_close($link);
        return $list;
    }

    //put your code here
    public static function add($tid, $name, $clan, $picture) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        $cid = '';
        $result = mysql_query("SELECT idClan from Clan where Tournament_idTournament=" . $tid . " AND Name='" . $clan . "'");

        if ($result) {
            $row = mysql_fetch_assoc($result);
            if ($row) {
                $cid = $row['idTeam'];
            }
            mysql_free_result($result);
        } else {
            die('Requête invalide : ' . mysql_error());
        }


        $query = "INSERT INTO `$db_name`.`" . $db_prefix . "team` (`Tournament_idTournament` ,`Name`,`Picture`,`Clan_idClan`)VALUES ('$tid', '$name','$picture','$cid');";
//        echo $query . "<br>";
        $result = mysql_query($query);
        $id = mysql_insert_id($link);
        mysql_close($link);
        return $id;
    }

}

?>
