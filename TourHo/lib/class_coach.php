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
    public static function add($name,$tid,$team,$roster,$naf,$rank)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="INSERT INTO `$db_name`.`".$db_prefix."coach` (`cid` ,`f_tid` ,`name` ,`team` ,`race` ,`NAF` ,`rank`)VALUES ('', '$tid', '".addslashes($name)."', '".addslashes($team)."', '".addslashes($roster)."', '$naf', '$rank');";
      
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }
}
?>
