<?php

/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_tournament
 *
 * @author Frederic Berger
 */
class tournament {

    public $tid=0;

    function __construct($id)
    {
        global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="SELECT * FROM `$db_name`.".$db_prefix."tournament WHERE tid=$id";       
       $result=mysql_query($query);
       while ($r = mysql_fetch_assoc($result)) {
           foreach ($r as $key => $value)
           {
               $this->$key=$value;
           }
        }
        $this->tid=$id;
        mysql_close($link);
    }

    public static function add($name,$orgas,$date,$place,
        $large_victory,$_victory,$draw,$little_lost,$lost,
        $rank1,$rank2,$rank3,$rank4,$rank5,
        $td_pos,$td_neg,$foul_pos,$foul_neg,$cas_pos,$cas_neg)
    {
        global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;
        
       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);

       $query="INSERT INTO `$db_name`.`".$db_prefix."tournament` (`tid` ,`date` ,`name` ,`orgas` ,`place` ,`rank1` ,`rank2` ,`rank3` ,`rank4` ,`rank5` ,`large_victory` ,`victory` ,`draw` ,`little_lost` ,`lost` ,`td_pos` ,`td_neg` ,`foul_pos` ,`foul_neg` ,`cas_pos` ,`cas_neg`)VALUES ('', '$date', '".addslashes($name)."', '".addslashes($orgas)."', '".addslashes($place)."', '$rank1', '$rank2', '$rank3', '$rank4', '$rank5', '$large_victory', '$victory', '$draw', '$little_lost', '$lost', '$td_pos', '$td_neg', '$foul_pos', '$foul_neg', '$cas_pos', '$cas_neg');";
       $result=mysql_query($query);
       $id=mysql_insert_id ($link);
        mysql_close($link);
        return $id;
    }
}
?>
