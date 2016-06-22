<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_position
 *
 * @author WFMJ7631
 */
class position {
    //put your code here
    public static function addCoach($link,$tid,$rkid, $coach_name, $teammates, $roster, $criteria, $r1, $r2, $r3, $r4, $r5, $position,$posneg)
    {
         global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
         
        $cid = '';
        $teid='';
        // Get Coach ID
        $query="SELECT idCoach,Team_idTeam from `$db_name`.`" . $db_prefix . "Coach` where Tournament_idTournament=" . $tid . " AND Name='" . $coach_name . "';";
        //echo "$query<br>";
        $result = mysql_query($query);
        if ($result) {
            $row = mysql_fetch_assoc($result);
            if ($row) {
                $cid = $row['idCoach'];
                $teid = $row['Team_idTeam'];
            }
            mysql_free_result($result);
        } else {
            die('Requête invalide : ' . mysql_error());
        }

        $crit='';
         $query="SELECT idCriteria from `$db_name`.`" . $db_prefix . "Criteria` where Settings_Tournament_idTournament=" . $tid . " AND Name='" . $criteria . "'";
       //echo "$query<br>";
        $result = mysql_query($query);
        if ($result) {
            $row = mysql_fetch_assoc($result);
            if ($row) {
                $crit = $row['idCriteria'];
            }
            mysql_free_result($result);
        } else {
            die('Requête invalide : ' . mysql_error());
        }

        $query = "INSERT INTO `$db_name`.`" . $db_prefix . "position` (`Coach_idCoach` ,`Team_idTeam`,`Ranking_Criteria_idCriteria`,"
                . "`Ranking_idRanking`,`Name`,`Value1`,`Value2`,`Value3`,`Value4`,`Value5`,`Position`,`Positive`) "
                . "VALUES ('$cid', '$teid','$crit','$rkid', '$coach_name',"
                . "'$r1','$r2','$r3','$r4','$r5','$position','$posneg');";
        //echo "$query<br>";
         $result = mysql_query($query);
          mysql_close($link);
         return $result;
    }
    public static function addTeam($link,$tid,$rkid, $team, $criteria, $r1, $r2, $r3, $r4, $r5,$posneg)
    {
                global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());

         
        $teid='';
        // Get Coach ID
        $result = mysql_query("SELECT idTeam from `$db_name`.`" . $db_prefix . "Team` where Tournament_idTournament=" . $tid . " AND Name='" . $team . "'");
        if ($result) {
            $row = mysql_fetch_assoc($result);
            if ($row) {
                $teid = $row['idTeam'];
            }
            mysql_free_result($result);
        } else {
            die('Requête invalide : ' . mysql_error());
        }

        $crit='';
        $result = mysql_query("SELECT idCriteria from `$db_name`.`" . $db_prefix . "Criteria where Settings_Tournament_idTournament=" . $tid . " AND Name='" . $criteria . "'");
        if ($result) {
            $row = mysql_fetch_assoc($result);
            if ($row) {
                $crit = $row['idCriteria'];
            }
            mysql_free_result($result);
        } else {
            die('Requête invalide : ' . mysql_error());
        }
        //echo "$query<br>";
        $query = "INSERT INTO `$db_name`.`" . $db_prefix . "position` (`Team_idTeam`,`Ranking_Criteria_idCriteria`,"
                . "`Ranking_idRanking`,`Name`,`Value1`,`Value2`,`Value3`,`Value4`,`Value5`,`Position`,`Positive`) "
                . "VALUES ('$teid','$crit','$rkid', '$team',"
                . "'$r1','$r2','$r3','$r4','$r5','$position','$posneg');";
        
         $result = mysql_query($query);
          mysql_close($link);
         return $result;
    }
}
