<?php

/**
 * Description of class_round
 *
 * @author Frederic Berger
 */
class round {
    const C_UNIQUE=1;
    const C_MAX=2;
    
    public $rid=0;
     function __construct($link,$id)
    {
        global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       //$link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       //mysql_select_db($db_name,$link);

       $query="SELECT * FROM `$db_name`.".$db_prefix."round WHERE idRound=$id";
       $result=mysql_query($query);
       if ($result)
       {
       while ($r = mysql_fetch_assoc($result)) {
           foreach ($r as $key => $value)
           {
               $this->$key=$value;
           }
        }
       }
        $this->rid=$id;
        //mysql_close($link);
    }
    //put your code here
    public static function add($tid,$date)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="INSERT INTO `$db_name`.`".$db_prefix."Round` (`Tournament_idTournament` ,`dDate` )VALUES ('".$tid."',str_to_date('$date','%d/%m/%Y %H:%i:%s'));";
       
       $result=mysql_query($query);
        $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }

     public function getCoachMatchs($link)
    {
       $list=array();
       global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       mysql_select_db($db_name,$link);

       $query="SELECT idCoachMatch FROM ".$db_prefix."coachmatch WHERE Round_idRound=$this->idRound";
       $result=mysql_query($query);

       $index=0;
       while ($r = mysql_fetch_row($result)) {
           $list[$index++]=new coachmatch($r[0],$link);
        }
        
        return $list;
    }
    
    
     public function getTeamMatchs($link)
    {
       $list=array();
       global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       mysql_select_db($db_name,$link);

       $query="SELECT idTeamMatch FROM ".$db_prefix."teammatch WHERE Round_idRound=$this->idRound";
       $result=mysql_query($query);

       $index=0;
       while ($r = mysql_fetch_row($result)) {
           $list[$index++]=new teammatch($r[0],$link);
        }
        
        return $list;
    }
}
?>
