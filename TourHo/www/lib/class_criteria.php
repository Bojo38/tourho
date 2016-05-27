<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_criteria
 *
 * @author WFMJ7631
 */
class criteria {
    //put your code here
    
    public static function add($tid,$sid,$name,$points_for,$points_against,$points_team_for,$points_team_against) {

        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        
         $query = "INSERT INTO `$db_name`.`" . $db_prefix . "criteria`
             (`Settings_idSettings`,`Settings_Tournament_idTournament`, `Name`, `Points_For`, `Points_Against`, `Points_Team_For`, `Points_Team_Against`) 
             VALUES ('".$tid."', '".$sid."', '".$name."', '". $points_for."', '". $points_against."', '".$points_team_for."', '".$points_team_against."');";
        
   //      echo $query."<br>";
         
        $result = mysql_query($query);
        $id = mysql_insert_id($link);
        mysql_close($link);
        return $id;
    }
}
