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
    
    public static function add($tid,$rid,$name,$type,$subtype,$rankings,$criteria,$posneg)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);
             
       $idCriteria='';
       
       $result=mysql_query("SELECT idCriteria from Criteria where Tournament_idTournament=".$tid." AND Name='".$criteria."'");
       if ($result)
       {
        $idCriteria=  mysql_result($result, 0);
       }
       
       $query="INSERT INTO `$db_name`.`".$db_prefix."ranking` "
               . "(`Round_idRound` ,`Name`,`RankType` ,`RankSubType` ,`PosNeg` ,`Criteria_idCriteria`)"
               . "VALUES ('".$rid."', '".addslashes($name)."', '$type','$subtype', '$posneg', '$idCriteria');";
      
       echo $query."<br>";
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }

}
