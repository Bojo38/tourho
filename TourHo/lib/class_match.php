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
    public static function add($rid,$cid1,$cid2,$td1,$td2,$sor1,$sor2,$foul1,$foul2)
    {
         global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="INSERT INTO `$db_name`.`".$db_prefix."match` (`mid` ,`f_rid`,`f_cid1`,`f_cid2` ,`td1` ,`td2` ,`cas1` ,`cas2` ,`foul1`,`foul2`)VALUES ('', '$rid', '$cid1', '$cid2', '$td1', '$td2', '$sor1','$sor2','$foul1','$foul2');";
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }
}
?>
