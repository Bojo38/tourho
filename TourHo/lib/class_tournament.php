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

    const C_TD_POS = 1;
    const C_TD_NEG = 2;
    const C_CAS_POS = 3;
    const C_CAS_NEG = 4;
    const C_FOUL_POS = 5;
    const C_FOUL_NEG = 6;

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

    public function getRounds()
    {
       $list=array();
       global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);      
       $query="SELECT rid FROM ".$db_prefix."round WHERE f_tid=$this->tid ORDER BY date";
       $result=mysql_query($query);
       $index=0;
       while ($r = mysql_fetch_row($result)) {
           $list[$index++]=new round($r[0]);
        }
        //mysql_close($link);
        return $list;
    }

    public function getCoachs()
    {
       $list=array();
       global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
       mysql_select_db($db_name,$link);
       $query="SELECT cid FROM ".$db_prefix."coach WHERE f_tid=$this->tid";
       $result=mysql_query($query);
       $index=0;
       while ($r = mysql_fetch_row($result)) {
           $list[$index++]=new coach($r[0]);
        }
        //mysql_close($link);
        return $list;
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

    public static function getYearList()
    {
        global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;
        $list=array();
       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name,$link);

       $query="SELECT DATE_FORMAT(date,'%Y') from ".$db_prefix."tournament";
       $result=mysql_query($query);
       
       while($row=mysql_fetch_array($result)) {
            $list[$row[0]]=$row[0];
        }
        mysql_close($link);
        return $list;
    }

    public static function getToursByYear($year)
    {
        global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;
        $list=array();
       $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name,$link);

       $query="SELECT name,tid from ".$db_prefix."tournament WHERE DATE_FORMAT(date,'%Y')=$year";
       $result=mysql_query($query);

       while($row=mysql_fetch_array($result)) {
            $list[$row[0]]=$row[1];
        }
        mysql_close($link);
        return $list;
    }
}
?>
