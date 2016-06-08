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
class settings {
    
    public $Criterias;
    //put your code here
    
     public static function add($tid,$victory, $large_victory, $draw, $lost,$little_lost,$refused,$conceeded,
             $victory_team,$draw_team,$lost_team, $large_victory_gap,$little_victory_gap,
             $rank1,$rank2,$rank3,$rank4,$rank5,$rank_team1,$rank_team2,$rank_team3,$rank_team4,$rank_team5,
             $byteam,$teammates,$team_pairing,$team_indiv_pairing,$team_victory_points,$team_draw_points,
             $team_victory_only,$groups_enable,$substitutes,$game_type,$activate_clans,$avoid_first_match,$avoid_match,
             $clan_teammates_number,$multiroster,$individually_balanced,$team_balanced,$use_large_victory,$use_little_loss,
             $table_bonus,$table_bonus_per_round,$table_bonus_coeff,
             $use_best_indiv,$use_best_team,$best_indiv_result,$best_team_result,
             $apply_to_annex_indiv, $appl_to_annex_team, $except_worst_and_best_indiv, $except_worst_and_best_team) {

        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        /*$query = "INSERT INTO `$db_name`.`" . $db_prefix . "settings`
        (`dDate` ,`Name` ,`Place`)
        VALUES (str_to_date('$date','%d/%m/%Y'), '" . addslashes($name) . "', '" .addslashes($place) . "');";*/
        
         $query = "INSERT INTO `$db_name`.`" . $db_prefix . "settings`
             (`Tournament_idTournament`, `victory`, `large_victory`, `draw`, `lost`, `little_lost`, `refused`, `conceeded`,
             `victory_team`, `draw_team`, `lost_team`, `large_victory_gap`, `little_lost_gap`, `rank1`, `rank2`, `rank3`, `rank4`, `rank5`,
             `rank1_team`, `rank2_team`, `rank3_team`, `rank4_team`, `rank5_team`, 
             `byteam`, `teammates`, `team_pairing`, `team_indiv_pairing`, `team_victory_points`, `team_draw_points`, `team_victory_only`, `groups_enable`, 
             `substitutes`, `game_type`, `activate_clans`, `avoid_first_match`, `avoid_match`, `clan_teammates_number`, `multi_roster`, 
             `individually_balanced`, `team_balanced`, `use_large_victory`, `use_little_loss`, 
             `table_bonus`, `table_bonus_per_round`, `table_bonus_coeff`, `use_best_indiv`, `use_best_team`, `best_indiv_result`, `best_team_result`, 
             `apply_to_annex_indiv`, `appl_to_annex_team`, `except_worst_and_best_indiv`, `except_worst_and_best_team`) 
             VALUES ('".$tid."', '".$victory."', '".$large_victory."', '". $draw."', '". $lost."', '".$little_lost."', '".$refused."', '".$conceeded."', '"
             .$victory_team."', '".$draw_team."', '".$lost_team."', '". $large_victory_gap."', '".$little_victory_gap."', '"
             .$rank1."', '".$rank2."', '".$rank3."', '".$rank4."', '".$rank5."', '".$rank_team1."', '".$rank_team2."', '".$rank_team3."', '".$rank_team4."', '".$rank_team5."', '"
             .$byteam."', '".$teammates."', '".$team_pairing."', '".$team_indiv_pairing."', '".$team_victory_points."', '".$team_draw_points."', '"
             .$team_victory_only."', '".$groups_enable."', '".$substitutes."', '".$game_type."', '".$activate_clans."', '".$avoid_first_match."', '".$avoid_match."', '"
             .$clan_teammates_number."', '".$multiroster."', '".$individually_balanced."', '".$team_balanced."', '".$use_large_victory."', '".$use_little_loss."', '"
             .$table_bonus."', '".$table_bonus_per_round."', '".$table_bonus_coeff."', '"
             .$use_best_indiv."', '".$use_best_team."', '".$best_indiv_result."', '".$best_team_result."', '"
             .$apply_to_annex_indiv."', '". $appl_to_annex_team."', '". $except_worst_and_best_indiv."', '".$except_worst_and_best_team."');";
        
         //echo $query."<br>";
         
        $result = mysql_query($query);
        $id = mysql_insert_id($link);
        mysql_close($link);
        return $id;
    }
    
    public static function getCriterias($link,$sid)
    {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        
        return $criterias;
    }
    
    function __construct($link,$id) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $query = "SELECT * FROM `$db_name`." . $db_prefix . "settings WHERE Tournament_idTournament=$id";
   //     echo "$query<br>";
        $result = mysql_query($query);
        if ($result) {
            while ($r = mysql_fetch_assoc($result)) {
                foreach ($r as $key => $value) {
                    $this->$key = $value;
                }
            }
            $this->Criterias=array();
            $query = "SELECT * FROM `$db_name`." . $db_prefix . "criteria WHERE Settings_idSettings=$this->idSettings";
            //echo "$query<br>";
            $i=0;
            $result = mysql_query($query);
            if ($result) {
                while ($r = mysql_fetch_assoc($result)) {
                    $this->Criterias[$i]=new criteria($r);
                    $i=$i+1;
                }
            }
        }
    }
    
    
}
