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

    const C_RANKING_NONE=0;
    const C_RANKING_POINTS=1;
    const C_RANKING_OPP_POINTS=2;
    const C_RANKING_TD=3;
    const C_RANKING_SOR=4;
    const C_RANKING_FOUL=5;
    const C_RANKING_DIFF_TD=6;
    const C_RANKING_DIFF_SOR=7;
    const C_RANKING_DIFF_FOUL=8;
    const C_RANKING_VND=9;

    const C_VND_V=1;
    const C_VND_N=2;
    const C_VND_D=3;
    const C_VND_GV=4;
    const C_VND_PD=5;

    public $tid = 0;

    function __construct($id) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        $query = "SELECT * FROM `$db_name`." . $db_prefix . "tournament WHERE tid=$id";
        $result = mysql_query($query);
        while ($r = mysql_fetch_assoc($result)) {
            foreach ($r as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->tid = $id;
        mysql_close($link);
    }

    public function getRounds() {
        $list = array();
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);
        $query = "SELECT rid FROM " . $db_prefix . "round WHERE f_tid=$this->tid ORDER BY number";
        $result = mysql_query($query);
        $index = 0;
        while ($r = mysql_fetch_row($result)) {
            $list[$index++] = new round($r[0]);
        }
        //mysql_close($link);
        return $list;
    }

    public function getCoachs() {
        $list = array();
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);
        $query = "SELECT cid FROM " . $db_prefix . "coach WHERE f_tid=$this->tid";
        $result = mysql_query($query);
        $index = 0;
        while ($r = mysql_fetch_row($result)) {
            $list[$index++] = new coach($r[0]);
        }
        //mysql_close($link);
        return $list;
    }

    public function getTeams() {
        $list = array();
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);
        $query = "SELECT teid FROM " . $db_prefix . "team WHERE f_tid=$this->tid";
        $result = mysql_query($query);
        $index = 0;
        while ($r = mysql_fetch_row($result)) {
            $list[$index++] = new team($r[0]);
        }
        //mysql_close($link);
        return $list;
    }

    public static function add($name, $date, $place) {

        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);
       
        $query = "INSERT INTO `$db_name`.`" . $db_prefix . "tournament`
        (`dDate` ,`Name` ,`Place`)
        VALUES (str_to_date('$date','%d/%m/%Y'), '" . addslashes($name) . "', '" .addslashes($place) . "');";
        
        echo $query."<br>";
        
        $result = mysql_query($query);
        $id = mysql_insert_id($link);
        mysql_close($link);
        return $id;
    }

    public static function getYearList() {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;
        $list = array();
        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        $query = "SELECT DATE_FORMAT(dDate,'%Y') from " . $db_prefix . "tournament";
        $result = mysql_query($query);

        while ($row = mysql_fetch_array($result)) {
            $list[$row[0]] = $row[0];
        }
        mysql_close($link);
        return $list;
    }

    public static function getToursByYear($year) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;
        $list = array();
        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        $query = "SELECT Name,idTournament from " . $db_prefix . "tournament WHERE DATE_FORMAT(dDate,'%Y')=$year";
        $result = mysql_query($query);

        while ($row = mysql_fetch_array($result)) {
            $list[$row[0]] = $row[1];
        }
        mysql_close($link);
        return $list;
    }

    public function getCoachOpponents($coach_id, $round_id, $round_max) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;
        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);
        $list = array();
        if ($round_max == round::C_UNIQUE) {
            $sep = '=';
        } else {
            $sep = '<=';
        }
        $query = "(SELECT tourho_match.f_cid2 AS cid FROM tourho_match WHERE $coach_id = tourho_match.f_cid1 and tourho_match.f_rid" . $sep . "$round_id )
                        UNION
                        (SELECT tourho_match.f_cid1 AS cid FROM tourho_match WHERE $coach_id = tourho_match.f_cid2 and tourho_match.f_rid" . $sep . "$round_id )
            ";
        $result = mysql_query($query);
        while ($r = mysql_fetch_row($result)) {
            array_push($list, $r[0]);
        }
        mysql_close($link);
        return $list;
    }

    public function getTeamOpponents($team_id, $round_id, $round_max) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;
        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);
        $list = array();
        if ($round_max == round::C_UNIQUE) {
            $sep = '=';
        } else {
            $sep = '<=';
        }

        $query=" (
                SELECT tourho_coach.f_teid
                FROM tourho_match, tourho_coach
                WHERE tourho_match.f_rid " . $sep . "$round_id
                AND tourho_match.f_cid1
                IN (

                SELECT tourho_coach.cid
                FROM tourho_coach
                WHERE f_teid =$team_id
                )
                AND tourho_coach.cid = tourho_match.f_cid2
                )
                UNION (

                SELECT tourho_coach.f_teid
                FROM tourho_match, tourho_coach
                WHERE tourho_match.f_rid " . $sep . "$round_id
                AND tourho_match.f_cid2
                IN (

                SELECT tourho_coach.cid
                FROM tourho_coach
                WHERE f_teid =$team_id
                )
                AND tourho_coach.cid = tourho_match.f_cid1
                )
            ";
        /*$query = "(SELECT tourho_match.f_cid2 AS cid FROM tourho_match WHERE $coach_id = tourho_match.f_cid1 and tourho_match.f_rid" . $sep . "$round_id )
                        UNION
                        (SELECT tourho_match.f_cid1 AS cid FROM tourho_match WHERE $coach_id = tourho_match.f_cid2 and tourho_match.f_rid" . $sep . "$round_id )
            ";*/
        $result = mysql_query($query);
        while ($r = mysql_fetch_row($result)) {
            array_push($list, $r[0]);
        }
        mysql_close($link);
        return $list;
    }

    public function getValueByCoach($coach_id, $round_id, $value_type, $round_max) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;
        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        switch ($value_type) {
            case tournament::C_TD_POS:
                $s1 = 'td1';
                $s2 = 'td2';
                break;
            case tournament::C_TD_NEG:
                $s1 = 'td2';
                $s2 = 'td1';
                break;
            case tournament::C_CAS_POS:
                $s1 = 'cas1';
                $s2 = 'cas2';
                break;
            case tournament::C_CAS_NEG:
                $s1 = 'cas2';
                $s2 = 'cas1';
                break;
            case tournament::C_FOUL_POS:
                $s1 = 'foul1';
                $s2 = 'foul2';
                break;
            case tournament::C_FOUL_NEG:
                $s1 = 'foul2';
                $s2 = 'foul1';
                break;
        }
        if ($round_max == round::C_UNIQUE) {
            $sep = '=';
        } else {
            $sep = '<=';
        }

        $query1 = "SELECT SUM(tourho_match." . $s1 . ") AS value FROM tourho_match WHERE $coach_id = tourho_match.f_cid1 and tourho_match.f_rid" . $sep . "$round_id";
        $query2 = "SELECT SUM(tourho_match." . $s2 . ") AS value FROM tourho_match WHERE $coach_id = tourho_match.f_cid2 and tourho_match.f_rid" . $sep . "$round_id";

        /* $query="SELECT SUM( value ) FROM (
          (
          (SELECT tourho_match.".$s1." AS value FROM tourho_match WHERE $coach_id = tourho_match.f_cid1 and tourho_match.f_rid".$sep."$round_id)
          UNION
          (SELECT tourho_match.".$s2." AS value FROM tourho_match WHERE $coach_id = tourho_match.f_cid2 and tourho_match.f_rid".$sep."$round_id)
          ) AS liste) "; */
        $result = mysql_query($query1);
        while ($r = mysql_fetch_row($result)) {
            $value+= ( $r[0]);
        }
        $result = mysql_query($query2);
        while ($r = mysql_fetch_row($result)) {
            $value+= ( $r[0]);
        }
        mysql_close($link);
        //$coach=new coach($coach_id);

        /* if ($coach->name=='toblerona')
          {
          print $query;
          } */
        return $value;
    }

    public function getVNDByCoach($coach_id, $round_id, $value_type, $round_max) {

        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;
        $value = 0;
        switch ($value_type) {
            case tournament::C_VND_V:
                $c1 = 'td1>td2';
                $c2 = 'td1<td2';
                $log = "V";
                break;
            case tournament::C_VND_N:
                $c1 = 'td1=td2';
                $c2 = 'td1=td2';
                $log = "N";
                break;
            case tournament::C_VND_D:
                $c1 = 'td1<td2';
                $c2 = 'td1>td2';
                $log = "D";
                break;
            case tournament::C_VND_GV:
                $c1 = 'td1>=td2+' . $this->gv_gap;
                $c2 = 'td1+' . $this->gv_gap . '<=td2';
                $log = "GV";
                break;
            case tournament::C_VND_PD:
                $c1 = 'td1+' . $this->ll_gap . '=td2';
                $c2 = 'td1=td2+' . $this->ll_gap;
                $log = "PD";
                break;
        }

        if ($round_max == round::C_UNIQUE) {
            $sep = '=';
        } else {
            $sep = '<=';
        }

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        $query = "SELECT COUNT( value ) FROM (
                    (
                        (SELECT tourho_match.mid AS value FROM tourho_match WHERE $coach_id = tourho_match.f_cid1 and tourho_match.f_rid" . $sep . "$round_id AND $c1)
                        UNION
                        (SELECT tourho_match.mid AS value FROM tourho_match WHERE $coach_id = tourho_match.f_cid2 and tourho_match.f_rid" . $sep . "$round_id AND $c2)
                    ) AS liste) ";
        $result = mysql_query($query);
        while ($r = mysql_fetch_row($result)) {
            $value = ($r[0]);
        }
        /* $coach=new coach($coach_id);
          print "$coach->name $log : $value<br>"; */

        //mysql_close($link);
        return $value;
    }

    public function getVNDByTeam($team_id, $round_id, $value_type, $round_max) {
        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

        global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;
        $value = 0;
        switch ($value_type) {
            case tournament::C_VND_V:
                $c1 = 'td1>td2';
                $c2 = 'td1<td2';
                $log = "V";
                break;
            case tournament::C_VND_N:
                $c1 = 'td1=td2';
                $c2 = 'td1=td2';
                $log = "N";
                break;
            case tournament::C_VND_D:
                $c1 = 'td1<td2';
                $c2 = 'td1>td2';
                $log = "D";
                break;
        }

        if ($round_max == round::C_UNIQUE) {
            $sep = '=';
        } else {
            $sep = '<=';
        }

        $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
        mysql_select_db($db_name, $link);

        /* Faire une requete qui calcule les défaites/victoires/matchs nuls d'équipe*/
        
        /*$query = "SELECT COUNT( value ) FROM (
                    (
                        (SELECT tourho_match.mid AS value FROM tourho_match WHERE $coach_id = tourho_match.f_cid1 and tourho_match.f_rid" . $sep . "$round_id AND $c1)
                        UNION
                        (SELECT tourho_match.mid AS value FROM tourho_match WHERE $coach_id = tourho_match.f_cid2 and tourho_match.f_rid" . $sep . "$round_id AND $c2)
                    ) AS liste) ";*/
        
        $result = mysql_query($query);
        while ($r = mysql_fetch_row($result)) {
            $value = ($r[0]);
        }
        /* $coach=new coach($coach_id);
          print "$coach->name $log : $value<br>"; */

        //mysql_close($link);
        return $value;


        return $value;
    }

    public function getRankingName($ranking) {
        $ranking_name = '';
        switch ($ranking) {
            case tournament::C_RANKING_DIFF_FOUL:
                $ranking_name = "Diff. Aggressions";
                break;
            case tournament::C_RANKING_DIFF_SOR:
                $ranking_name = "Diff. Sorties";
                break;
            case tournament::C_RANKING_DIFF_TD:
                $ranking_name = "Diff. Touchdowns";
                break;
            case tournament::C_RANKING_FOUL:
                $ranking_name = "Aggressions";
                break;
            case tournament::C_RANKING_OPP_POINTS:
                $ranking_name = "Points adversaires";
                break;
            case tournament::C_RANKING_POINTS:
                $ranking_name = "Points";
                break;
            case tournament::C_RANKING_SOR:
                $ranking_name = "Sorties";
                break;
            case tournament::C_RANKING_TD:
                $ranking_name = "Touchdowns";
                break;
            case tournament::C_RANKING_VND:
                $ranking_name = "V/N/D";
                break;
            default:
                $ranking_name = "Rien";
                break;
        }
        return $ranking_name;
    }

    public function getRankingValue($coach_id, $round_id, $ranking, $round_max) {
        $value = 0;
        switch ($ranking) {
            case tournament::C_RANKING_DIFF_FOUL:
                $value = $this->getValueByCoach($coach_id, $round_id, tournament::C_FOUL_POS, $round_max) - $this->getValueByCoach($coach_id, $round_id, tournament::C_FOUL_NEG, $round_max);
                break;
            case tournament::C_RANKING_DIFF_SOR:
                $value = $this->getValueByCoach($coach_id, $round_id, tournament::C_CAS_POS, $round_max) - $this->getValueByCoach($coach_id, $round_id, tournament::C_CAS_NEG, $round_max);
                break;
            case tournament::C_RANKING_DIFF_TD:
                $value = $this->getValueByCoach($coach_id, $round_id, tournament::C_TD_POS, $round_max) - $this->getValueByCoach($coach_id, $round_id, tournament::C_TD_NEG, $round_max);
                break;
            case tournament::C_RANKING_FOUL:
                $value = $this->getValueByCoach($coach_id, $round_id, tournament::C_FOUL_POS, $round_max);
                break;
            case tournament::C_RANKING_OPP_POINTS:
                $opps = $this->getCoachOpponents($coach_id, $round_id, $round_max);
                $value = 0;
                foreach ($opps as $opp) {
                    $value+= $this->getPointsByCoach($opp, $round_id, $round_max);
                }
                break;
            case tournament::C_RANKING_POINTS:
                $value = $this->getPointsByCoach($coach_id, $round_id, $round_max);
                break;
            case tournament::C_RANKING_SOR:
                $value = $this->getValueByCoach($coach_id, $round_id, tournament::C_CAS_POS, $round_max);
                break;
            case tournament::C_RANKING_TD:
                $value = $this->getValueByCoach($coach_id, $round_id, tournament::C_TD_POS, $round_max);
                break;
            case tournament::C_RANKING_VND:
                $value = $this->getVNDByCoach($coach_id, $round_id, tournament::C_VND_V, $round_max) . "/" .
                    $this->getVNDByCoach($coach_id, $round_id, tournament::C_VND_N, $round_max) . "/" .
                    $this->getVNDByCoach($coach_id, $round_id, tournament::C_VND_D, $round_max);
                break;
            default:
                $value = 0;
                break;
        }
        return $value;
    }

    public function getRankingTeamValue($team_id, $round_id, $ranking, $round_max) {
        $value = 0;
        $team=new team($team_id);
        $coachs=$team->getCoachs();

        switch ($ranking) {
            case tournament::C_RANKING_DIFF_FOUL:
                foreach ($coachs as $coach) {
                    $value += $this->getValueByCoach($coach->cid, $round_id, tournament::C_FOUL_POS, $round_max) - $this->getValueByCoach($coach_id, $round_id, tournament::C_FOUL_NEG, $round_max);
                }
                break;
            case tournament::C_RANKING_DIFF_SOR:
                foreach ($coachs as $coach) {
                    $value += $this->getValueByCoach($coach->cid, $round_id, tournament::C_CAS_POS, $round_max) - $this->getValueByCoach($coach_id, $round_id, tournament::C_CAS_NEG, $round_max);
                }
                break;
            case tournament::C_RANKING_DIFF_TD:
                foreach ($coachs as $coach) {
                    $value += $this->getValueByCoach($coach->cid, $round_id, tournament::C_TD_POS, $round_max) - $this->getValueByCoach($coach_id, $round_id, tournament::C_TD_NEG, $round_max);
                }
                break;
            case tournament::C_RANKING_FOUL:
                foreach ($coachs as $coach) {
                    $value += $this->getValueByCoach($coach->cid, $round_id, tournament::C_FOUL_POS, $round_max);
                }
                break;
            case tournament::C_RANKING_OPP_POINTS:
                if (($this->team_pairing)&&($this->team_victory_only)) {
                    $opps = $this->getTeamOpponents($team_id, $round_id, $round_max);
                    foreach ($opps as $opp) {
                        $value+= $this->getPointsByTeam($opp, $round_id, $round_max);
                    }
                }
                else {
                    foreach ($coachs as $coach) {
                        $opps = $this->getCoachOpponents($coach->cid, $round_id, $round_max);
                        foreach ($opps as $opp) {
                            $value+= $this->getPointsByCoach($opp, $round_id, $round_max);
                        }
                        if ($this->team_pairing) {
                            $opps = $this->getTeamOpponents($team_id, $round_id, $round_max);
                            foreach ($opps as $opp) {
                                $value+=$this->getVNDByTeam($opp, $round_id, tournament::C_VND_V, $round_max)*$this->team_victory_points;
                            }
                        }
                    }
                }
                break;
            case tournament::C_RANKING_POINTS:
                if (($this->team_pairing)&&($this->team_victory_only)) {
                    $value += $this->getPointsByTeam($team->teid, $round_id, $round_max);
                }
                else {
                    foreach ($coachs as $coach) {
                        $value += $this->getPointsByCoach($coach->cid, $round_id, $round_max);
                    }
                    if ($this->team_pairing) {
                        $value+=$this->getVNDByTeam($team_id, $round_id, tournament::C_VND_V, $round_max)*$this->team_victory_points;
                    }
                }
                break;
            case tournament::C_RANKING_SOR:
                foreach ($coachs as $coach) {
                    $value += $this->getValueByCoach($coach->cid, $round_id, tournament::C_CAS_POS, $round_max);
                }
                break;
            case tournament::C_RANKING_TD:
                foreach ($coachs as $coach) {
                    $value += $this->getValueByCoach($coach->cid, $round_id, tournament::C_TD_POS, $round_max);
                }
                break;
            case tournament::C_RANKING_VND:
                if (($this->team_pairing)&&($this->team_victory_only)) {
                    $value += $this->getVNDByTeam($team_id, $round_id, tournament::C_VND_V, $round_max) . "/" .
                        $this->getVNDByTeam($team_id, $round_id, tournament::C_VND_N, $round_max) . "/" .
                        $this->getVNDByTeam($team_id, $round_id, tournament::C_VND_D, $round_max);
                }
                else {
                    foreach ($coachs as $coach) {
                        $value += $this->getVNDByCoach($coach->cid, $round_id, tournament::C_VND_V, $round_max) . "/" .
                            $this->getVNDByCoach($coach->cid, $round_id, tournament::C_VND_N, $round_max) . "/" .
                            $this->getVNDByCoach($coach->cid, $round_id, tournament::C_VND_D, $round_max);
                    }
                }
                break;
            default:
                $value = 0;
                break;
        }
        return $value;
    }

    public function getPointsByCoach($coach_id, $round_id, $round_max) {
        $gv = $this->getVNDByCoach($coach_id, $round_id, tournament::C_VND_GV, $round_max);
        $v = $this->getVNDByCoach($coach_id, $round_id, tournament::C_VND_V, $round_max);
        $n = $this->getVNDByCoach($coach_id, $round_id, tournament::C_VND_N, $round_max);
        $pd = $this->getVNDByCoach($coach_id, $round_id, tournament::C_VND_PD, $round_max);
        $d = $this->getVNDByCoach($coach_id, $round_id, tournament::C_VND_D, $round_max);

        $td_pos_nb = $this->getValueByCoach($coach_id, $round_id, tournament::C_TD_POS, $round_max);
        $td_neg_nb = $this->getValueByCoach($coach_id, $round_id, tournament::C_TD_NEG, $round_max);
        $cas_pos_nb = $this->getValueByCoach($coach_id, $round_id, tournament::C_CAS_POS, $round_max);
        $cas_neg_nb = $this->getValueByCoach($coach_id, $round_id, tournament::C_CAS_NEG, $round_max);
        $foul_pos_nb = $this->getValueByCoach($coach_id, $round_id, tournament::C_FOUL_POS, $round_max);
        $foul_neg_nb = $this->getValueByCoach($coach_id, $round_id, tournament::C_FOUL_NEG, $round_max);

        /* $coach=new coach($coach_id);
          echo "$coach->name: GV:$gv, V:$v, N:$n, PD:$pd, D:$d vGV:$this->large_victory, vV:$this->victory, vN:$this->draw
          vPD:$this->little_lost, vD:$this->lost <br>";
          echo "$coach->name: TD+: $td_pos_nb  * $this->td_pos$<br>";
          echo "$coach->name: CAS+: $cas_pos_nb  * $this->cas_pos$<br>";

        echo "$points = $gv * $this->large_victory + ($v - $gv) * $this->victory + $n * $this->draw +
            $pd * $this->little_lost + ($p - $pd) * $this->lost +
            $td_pos_nb * $this->td_pos + $td_neg_nb* $this->td_neg +
            $cas_pos_nb * $this->cas_pos + $cas_neg_nb * $this->cas_neg +
            $foul_pos_nb * $this->foul_pos + $foul_neg_nb * $this->foul_neg <BR>";*/

        $points = $gv * $this->large_victory + ($v - $gv) * $this->victory + $n * $this->draw +
            $pd * $this->little_lost + ($p - $pd) * $this->lost +
            $td_pos_nb * $this->td_pos + $td_neg_nb* $this->td_neg +
            $cas_pos_nb * $this->cas_pos + $cas_neg_nb * $this->cas_neg +
            $foul_pos_nb * $this->foul_pos + $foul_neg_nb * $this->foul_neg;
        return $points;
    }

    public function getPointsByTeam($team_id, $round_id, $round_max) {

        $v = $this->getVNDByTeam($team_id, $round_id, tournament::C_VND_V, $round_max);
        $n = $this->getVNDByTeam($team_id, $round_id, tournament::C_VND_N, $round_max);
        $d = $this->getVNDByTeam($team_id, $round_id, tournament::C_VND_D, $round_max);

        $td_pos_nb =0;
        $td_neg_nb =0;
        $cas_pos_nb =0;
        $cas_neg_nb =0;
        $foul_pos_nb =0;
        $foul_neg_nb =0;

        $team=new team($team_id);
        $coachs=$team->getCoachs();
        foreach ($coachs as $coach) {
            $td_pos_nb += $this->getValueByCoach($coach->cid, $round_id, tournament::C_TD_POS, $round_max);
            $td_neg_nb += $this->getValueByCoach($coach->cid, $round_id, tournament::C_TD_NEG, $round_max);
            $cas_pos_nb += $this->getValueByCoach($coach->cid, $round_id, tournament::C_CAS_POS, $round_max);
            $cas_neg_nb += $this->getValueByCoach($coach->cid, $round_id, tournament::C_CAS_NEG, $round_max);
            $foul_pos_nb += $this->getValueByCoach($coach->cid, $round_id, tournament::C_CAS_POS, $round_max);
            $foul_neg_nb += $this->getValueByCoach($coach->cid, $round_id, tournament::C_CAS_NEG, $round_max);
        }

        $points = $v*  $this->victory_team + $n * $this->draw_team + $p * $this->lost_team +
            $td_pos_nb * $this->td_pos_team + $td_neg_nb * $this->td_neg_team +
            $cas_pos_nb * $this->cas_pos_team + $cas_neg_nb * $this->cas_neg_team +
            $foul_pos_nb * $this->foul_pos_team + $foul_neg_nb * $this->foul_neg_team;
        return $points;
    }

}
?>
