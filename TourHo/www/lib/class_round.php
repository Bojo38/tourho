<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_round
 *
 * @author Frederic Berger
 */
class round {
    const C_UNIQUE=1;
    const C_MAX=2;
    
    public $rid=0;
     function __construct($id)
    {
        global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="SELECT * FROM `$db_name`.".$db_prefix."round WHERE rid=$id";
       $result=mysql_query($query);
       while ($r = mysql_fetch_assoc($result)) {
           foreach ($r as $key => $value)
           {
               $this->$key=$value;
           }
        }
        $this->rid=$id;
        mysql_close($link);
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

     public function getMatchs()
    {
       $list=array();
       global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);
       $query="SELECT mid FROM ".$db_prefix."match WHERE f_rid=$this->rid";
       $result=mysql_query($query);
       mysql_close($link);
       $index=0;
       while ($r = mysql_fetch_row($result)) {
           $list[$index++]=new match($r[0]);
        }
        
        return $list;
    }
}
?>
