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
class match {
   public $mid=0;
    function __construct($id)
    {
        global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="SELECT * FROM `$db_name`.".$db_prefix."match WHERE mid=$id";
       $result=mysql_query($query);
       while ($r = mysql_fetch_assoc($result)) {
           foreach ($r as $key => $value)
           {
               $this->$key=$value;
           }
        }
        $this->mid=$id;
        mysql_close($link);
    }
    //put your code here
    public static function addValue($cm,$name,$value1,$value2)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="INSERT INTO `$db_name`.`".$db_prefix."CoachValue` "
               . "(`CoachMatch_idCoachMatch` ,`CriteriaName`,`Value1`,`Value2`)"
               . "VALUES ('".$cm."', '".$name."', '".$value1."','".$value2."');";
       
      // echo $query."<br>";
       
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }
    
    
    //put your code here
    public static function addCoachMatch($tid,$rid,$cname1,$cname2,$rf1,$rf2,$cc1,$cc2,$tmid)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $cid1='';
      // echo "SELECT idCoach from Coach where Tournament_idTournament=".$tid." AND Name='".$cname1."'";
       $result=mysql_query("SELECT idCoach from Coach where Tournament_idTournament=".$tid." AND Name='".$cname1."'");
       if ($result)
       {
        $cid1=mysql_result($result, 0);
       }
       
       $cid2='';
       
       $result=mysql_query("SELECT idCoach from Coach where Tournament_idTournament=".$tid." AND Name='".$cname2."'");
       if ($result)
       {
        $cid2=mysql_result($result, 0);
       }
       
       $query="INSERT INTO `$db_name`.`".$db_prefix."CoachMatch` "
               . "(`Round_idRound` ,`Coach1_idCoach`,`Coach2_idCoach`,"
               . "`RefusedBy1` ,`RefusedBy2` ,`ConceededBy1` ,`ConceededBy2` ,"
               . "`TeamMatch_idTeamMatch`)"
               . "VALUES ('".$rid."', '".$cid1."', '".$cid2."', '".$rf1."', '".$rf2."', '".$cc1."', '".$cc2."','".$tmid."');";
       
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }
    
    public static function addTeamMatch($tid,$rid,$tname1,$tname2)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $tid1='';
       //echo "SELECT idTeam from team where Tournament_idTournament=".$tid." AND Name='".$tname1."'"."<br>";
       $result=mysql_query("SELECT idTeam from team where Tournament_idTournament=".$tid." AND Name='".$tname1."'");
       if ($result)
       {
        $tid1=mysql_result($result, 0);
       }
       
       $tid2='';
       
       //echo "SELECT idTeam from team where Tournament_idTournament=".$tid." AND Name='".$tname2."'"."<br>";
       $result=mysql_query("SELECT idTeam from team where Tournament_idTournament=".$tid." AND Name='".$tname2."'");
       if ($result)
       {
        $tid2=mysql_result($result, 0);
       }
       
       $query="INSERT INTO `$db_name`.`".$db_prefix."TeamMatch` "
               . "(`Round_idRound` ,`Team1_idTeam`,`Team2_idTeam`)"
               . "VALUES ('".$rid."', '".$tid1."', '".$tid2."');";
       
       //echo $query."<br>";
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }
    
   /* public static function addCoachMatchValue($tid,$rid,$coachName)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $cid='';
       echo "SELECT idCoach from Coach where Tournament_idTournament=".$tid." AND Name='".$coachName."'";
       $result=mysql_query("SELECT idCoach from Coach where Tournament_idTournament=".$tid." AND Name='".$coachName."'");
       if ($result)
       {
        $cid=mysql_result($result, 0);
       }
       
       $query="INSERT INTO `$db_name`.`".$db_prefix."CoachMatchValue` (`Round_idRound` ,`Cooach_idCoach`)VALUES ('".$rid."', '".$cid1."');";
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }*/
}
?>
