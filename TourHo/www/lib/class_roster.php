<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_settings
 *
 * @author WFMJ7631
 */
class roster {
    //put your code here
    
     public static function add($tid,$gid,$name) {

        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        
         $query = "INSERT INTO `$db_name`.`" . $db_prefix . "Roster`
             (`Tournament_idTournament`, `Group_idGroup`,`Name`) 
             VALUES ('".$tid."', '".$gid."', '".$name."');";
        
//         echo $query."<br>";
         
        $result = mysql_query($query);
        $id = mysql_insert_id($link);
        mysql_close($link);
        return $id;
    }
}