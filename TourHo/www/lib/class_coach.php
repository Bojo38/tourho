<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_coach
 *
 * @author Frederic Berger
 */
class coach {

public $cid=0;
     function __construct($id)
    {
        global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="SELECT * FROM `$db_name`.".$db_prefix."coach WHERE cid=$id";
       $result=mysql_query($query);
       while ($r = mysql_fetch_assoc($result)) {
           foreach ($r as $key => $value)
           {
               $this->$key=$value;
           }
        }
        $this->cid=$id;
        mysql_close($link);
    }
    //put your code here
    public static function add($tid,$name,$team,$roster,$naf,$rank,$handicap,$pic,$clan,$teammates)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $cid='';
       $result=mysql_query("SELECT idClan from clan where Tournament_idTournament=".$tid." AND Name='".$clan."'");
       if ($result)
       {
        $cid=mysql_result($result, 0);
       }
       
       $tmid='';
//       echo "SELECT idTeam from team where Tournament_idTournament=".$tid." AND Name='".$teammates."'"."<br>";
       $result=mysql_query("SELECT idTeam from team where Tournament_idTournament=".$tid." AND Name='".$teammates."'");
       if ($result)
       {
        $row = mysql_fetch_assoc($result);
        if ($row)
        {
            $tmid=$row['idTeam'];
        }
        mysql_free_result($result);
       }
       else
       {
            die('Requête invalide : ' . mysql_error());
       }
       
       $query="INSERT INTO `$db_name`.`".$db_prefix."coach` "
               . "(`Tournament_idTournament` ,`Name`,`Team` ,`Roster` ,`NAF` ,`Rank` ,`Handicap`,`Picture`,`Clan_idClan`,`Team_idTeam`)"
               . "VALUES ('".$tid."', '".addslashes($name)."', '".addslashes($team)."', '".addslashes($roster)."', '".$naf."','".$rank."','".$handicap."','"
               .addslashes($pic)."', '".$cid."', '".$tmid."');";
      
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }


    public static function set_team_id($name,$tid,$teid)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="UPDATE `$db_name`.`".$db_prefix."coach` SET `Team_idTeam` = '$teid' WHERE `Name` ='$name' AND `Tournament_idTournament` ='$tid'";
  //     echo "$query<br>";
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }
}
?>
