<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */



function generate_tour_menu($tour_id) {

    $tour=new tournament($tour_id);
    $rounds=$tour->getRounds();
    echo "<div id=\"titre\">$tour->name</div><br>";

    echo "<div class=\"menu\">
            <ul id=\"nav\" class=\"dropdown dropdown-horizontal\">
                <li><a href=\"index.php?tournament=$tour_id&amp;details\">Détails</a></li>
                <li><span class=\"dir\">Classements finaux</span>
                    <ul>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=general&amp;total=1\">General</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_pos&amp;total=1\">Touchdowns marqués</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_neg&amp;total=1\">Touchdowns encaissés</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_pos&amp;total=1\">Sorties réalisées</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_neg&amp;total=1\">Sorties subies</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_pos&amp;total=1\">Agressions réussies</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_neg&amp;total=1\">Agressions subies</a></li>
                    </ul>
                </li>";    
    foreach ($rounds as $round) {
        print "<li><span class=\"dir\">Ronde $round->number</span>";
        print "<ul>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=matchs&amp;round=$round->rid\">Matchs</a></li>                    
                    <li><span class=\"dir\">Classements sur la ronde</span>
                        <ul>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=general&amp;round=$round->rid\">Classement General</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_pos&amp;round=$round->rid\">Touchdowns marqués</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_neg&amp;round=$round->rid\">Touchdowns encaissés</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_pos&amp;round=$round->rid\">Sorties réalisées</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_neg&amp;round=$round->rid\">Sorties subies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_pos&amp;round=$round->rid\">Agressions réussies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_neg&amp;round=$round->rid\">Agressions subies</a></li>
                        </ul>
                    </li>
                    <li><span class=\"dir\">Classements à ronde</span>
                        <ul>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=general&amp;round=$round->rid&amp;total=1\">Classement General</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_pos&amp;round=$round->rid&amp;total=1\">Touchdowns marqués</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_neg&amp;round=$round->rid&amp;total=1\">Touchdowns encaissés</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_pos&amp;round=$round->rid&amp;total=1\">Sorties réalisées</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_neg&amp;round=$round->rid&amp;total=1\">Sorties subies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_pos&amp;round=$round->rid&amp;total=1\">Agressions réussies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_neg&amp;round=$round->rid&amp;total=1\">Agressions subies</a></li>
                        </ul>
                    </li>
                </ul></li>";
    }
    echo "</ul>
        </div>";
}

function match_display($round_id) {
    $round=new round($round_id);
    $matchs=$round->getMatchs();
    print "<br><div id=\"titre\">Ronde $round->number</div>";
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td colspan=\"1\" rowspan=\"2\" class=\"tab_titre\">Coach 1</td>
      <td colspan=\"4\" class=\"tab_titre\">Touchdowns</td>
      <td colspan=\"1\" rowspan=\"2\" class=\"tab_titre\">Coach 2</td>
    </tr>
    <tr>
      <td colspan=\"2\" class=\"tab_titre\">Sorties</td>
      <td colspan=\"2\" class=\"tab_titre\">Aggressions</td>
    </tr>";
    foreach ($matchs as $match) {
        $c1=new coach($match->f_cid1);
        $c2=new coach($match->f_cid2);
        if ($match->td1>$match->td2) {
            $style1="winner";
            $style2="looser";
        }
        else {
            if ($match->td1<$match->td2) {
                $style2="winner";
                $style1="looser";
            }
            else {
                $style2="draw";
                $style1="draw";
            }
        }
        print "<tr><td colspan=\"1\" rowspan=\"2\" class=\"$style1\">$c1->team<br>$c1->name - $c1->race </td>";
        print "<td colspan=\"2\" class=\"$style1\">$match->td1</td>";
        print "<td colspan=\"2\" class=\"$style2\">$match->td2</td>";
        print "<td colspan=\"1\" rowspan=\"2\" class=\"$style2\">$c2->team<br>$c2->name - $c2->race</td>";
        print "</tr>
    <tr>";
        print "<td class=\"$style1\">$match->cas1</td>";
        print "<td class=\"$style2\">$match->cas2</td>";
        print "<td class=\"$style1\">$match->foul1</td>";
        print "<td class=\"$style2\">$match->foul2</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}


function general_display($tid,$rid,$round_max) {
    $tour=new tournament($tid);

    $rounds=$tour->getRounds();
    if ($rid==-1) {

        foreach ($rounds as $round) {
            $rid=max($rid,$round->rid);
        }
        $text="classement final";
    }
    else {
        $count=1;
        foreach ($rounds as $round) {
            if ($rid==$round->rid) {
                if ($round_max==round::C_UNIQUE) {
                    $text="classement sur la ronde $count";
                }
                else {
                    $text="classement à la ronde $count";
                }
                break;
            }
            $count++;
        }
    }

    $round=new round($rid);

    $coachs=$tour->getCoachs();
    global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

    $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
    mysql_select_db($db_name,$link);

    $list =array();
    $sort_list1=array();
    $sort_list2=array();
    $sort_list3=array();
    $sort_list4=array();
    $sort_list5=array();

    $ranking_name1=$tour->getRankingName($tour->rank1);
    $ranking_name2=$tour->getRankingName($tour->rank2);
    $ranking_name3=$tour->getRankingName($tour->rank3);
    $ranking_name4=$tour->getRankingName($tour->rank4);
    $ranking_name5=$tour->getRankingName($tour->rank5);

    foreach ($coachs as $coach) {
        $element =array();
        $element['Coach']=$coach->name;
        $element['Team']=$coach->team;
        $element['Race']=$coach->race;

        $element['Value1']=$tour->getRankingValue($coach->cid,$rid,$tour->rank1,$round_max);
        $element['Value2']=$tour->getRankingValue($coach->cid,$rid,$tour->rank2,$round_max);
        $element['Value3']=$tour->getRankingValue($coach->cid,$rid,$tour->rank3,$round_max);
        $element['Value4']=$tour->getRankingValue($coach->cid,$rid,$tour->rank4,$round_max);
        $element['Value5']=$tour->getRankingValue($coach->cid,$rid,$tour->rank5,$round_max);



        array_push($list, $element);
        array_push($sort_list1,$element['Value1']);
        array_push($sort_list2,$element['Value2']);
        array_push($sort_list3,$element['Value3']);
        array_push($sort_list4,$element['Value4']);
        array_push($sort_list5,$element['Value5']);
    }



    array_multisort($sort_list1, SORT_DESC,$sort_list2, SORT_DESC,$sort_list3, SORT_DESC,$sort_list4, SORT_DESC,$sort_list5, SORT_DESC,$list);
    $counter=1;
    print "<br><div id=\"titre\">Classement général</div><div id=\"soustitre\">$text</div>";
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_titre\">N°</td>
      <td class=\"tab_titre\">Coach</td>
      <td class=\"tab_titre\">Equipe</td>
      <td class=\"tab_titre\">Roster</td>
      <td class=\"tab_titre\">".$ranking_name1."</td>
        <td class=\"tab_titre\">".$ranking_name2."</td>
        <td class=\"tab_titre\">".$ranking_name3."</td>
        <td class=\"tab_titre\">".$ranking_name4."</td>
        <td class=\"tab_titre\">".$ranking_name5."</td>
    </tr>";
    foreach ($list as $element) {
        if ($counter==1) {
            $suffix="_1";
        }
        else {if ($counter==count($list)) {
                $suffix="_last";
            }
            else {
                $suffix="";
            }
        }
        print "<td  class=\"tab_pos$suffix\" id=\"".$counter."\">".$counter++."</td>";
        print "<td class=\"tab_result$suffix\">".$element['Coach']."</td>";
        if ($element['Team']=="") {
            $element['Team']="&nbsp;";
        }
        print "<td class=\"tab_result$suffix\">".$element['Team']."</td>";

        print "<td class=\"tab_result$suffix\">".$element['Race']."</td>";
        print "<td class=\"tab_result$suffix\">".$element['Value1']."</td>";
        print "<td class=\"tab_result$suffix\">".$element['Value2']."</td>";
        print "<td class=\"tab_result$suffix\">".$element['Value3']."</td>";
        print "<td class=\"tab_result$suffix\">".$element['Value4']."</td>";
        print "<td class=\"tab_result$suffix\">".$element['Value5']."</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}


function rank_display($tid,$rid,$rank_type,$round_max) {
    $tour=new tournament($tid);
    $text="";
    $rounds=$tour->getRounds();

    if ($rid==-1) {

        foreach ($rounds as $round) {
            $rid=max($rid,$round->rid);
        }
        $text="classement final";
    }
    else {
        $count=1;
        foreach ($rounds as $round) {
            if ($rid==$round->rid) {
                $text="classement à la ronde $count";
                break;
            }
            $count++;
        }
    }
    $round=new round($rid);

    $coachs=$tour->getCoachs();

    $list =array();
    $sort_list=array();

    $sort_list1=array();
    $sort_list2=array();
    $sort_list3=array();
    $sort_list4=array();
    $sort_list5=array();

    foreach ($coachs as $coach) {

        $element =array();
        $element['Coach']=$coach->name;
        $element['Team']=$coach->team;
        $element['Race']=$coach->race;
        $element['Value']=$tour->getValueByCoach($coach->cid, $rid, $rank_type,$round_max);
        array_push($list, $element);
        array_push($sort_list,$element['Value']);

        $element['Value1']=$tour->getRankingValue($coach->cid,$rid,$tour->rank1,$round_max);
        $element['Value2']=$tour->getRankingValue($coach->cid,$rid,$tour->rank2,$round_max);
        $element['Value3']=$tour->getRankingValue($coach->cid,$rid,$tour->rank3,$round_max);
        $element['Value4']=$tour->getRankingValue($coach->cid,$rid,$tour->rank4,$round_max);
        $element['Value5']=$tour->getRankingValue($coach->cid,$rid,$tour->rank5,$round_max);

        array_push($sort_list1,$element['Value1']);
        array_push($sort_list2,$element['Value2']);
        array_push($sort_list3,$element['Value3']);
        array_push($sort_list4,$element['Value4']);
        array_push($sort_list5,$element['Value5']);
    }

    switch($rank_type) {
        case tournament::C_TD_POS:
            $ranking_name="Touchdown marqués";
            break;
        case tournament::C_TD_NEG:
            $ranking_name="Touchdown encaissés";
            break;
        case tournament::C_CAS_POS:
            $ranking_name="Sorties réalisées";
            break;
        case tournament::C_CAS_NEG:
            $ranking_name="Sorties subies";
            break;
        case tournament::C_FOUL_POS:
            $ranking_name="Agrressions réussies";
            break;
        case tournament::C_FOUL_NEG:
            $ranking_name="Agressions subies";
            break;
    }

    //array_multisort($sort_list, SORT_DESC,$list);
    array_multisort($sort_list, SORT_DESC,$sort_list1, SORT_DESC,$sort_list2, SORT_DESC,$sort_list3, SORT_DESC,$sort_list4, SORT_DESC,$sort_list5, SORT_DESC,$list);
    $counter=1;
    print "<br><div id=\"titre\">$ranking_name</div><div id=\"soustitre\">$text</div>";
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_titre\">N°</td>
      <td class=\"tab_titre\">Coach</td>
      <td class=\"tab_titre\">Equipe</td>
      <td class=\"tab_titre\">Roster</td>
      <td class=\"tab_titre\">".$ranking_name."</td>
    </tr>";
    foreach ($list as $element) {
        if ($counter==1) {
            $suffix="_1";
        }
        else {if ($counter==count($list)) {
                $suffix="_last";
            }
            else {
                $suffix="";
            }
        }
        print "<td class=\"tab_pos$suffix\">".$counter++."</td>";
        print "<td class=\"tab_result$suffix\">".$element['Coach']."</td>";
        if ($element['Team']=="")
        {
            $element['Team']="&nbsp;";
        }
        print "<td class=\"tab_result$suffix\">".$element['Team']."</td>";
        print "<td class=\"tab_result$suffix\">".$element['Race']."</td>";
        print "<td class=\"tab_result$suffix\">".$element['Value']."</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}

function details_html($tour) {

    $rounds=$tour->getRounds();
    $round_number=count($rounds);
    $coach_number=count($tour->getCoachs());
    print "<br><div id=\"titre\">Résumé du tournoi</div>";
    print "<div id=\"soustitre\">$tour->date - $tour->place </div>";
    print "<div id=\"soustitre\">Organisé par: $tour->orgas </div>";
    print "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_pos\" colspan=\"2\">Nombre de rondes</td>
      <td class=\"tab_result\">$round_number</td>
    </tr>
      <tr>
      <td class=\"tab_pos\" colspan=\"2\">Nombre de coachs</td>
      <td class=\"tab_result\">$coach_number</td>
    </tr>
     <tr>
      <td class=\"tab_pos\" rowspan=\"5\" colspan=\"2\">Systèmes de classements</td>
      <td class=\"tab_result\">".$tour->getRankingName($tour->rank1)."</td>
    </tr>
    <tr>
    <td class=\"tab_result\">".$tour->getRankingName($tour->rank2)."</td>
    </tr>
    <tr>
    <td class=\"tab_result\">".$tour->getRankingName($tour->rank3)."</td>
    </tr>
    <tr>
    <td class=\"tab_result\">".$tour->getRankingName($tour->rank4)."</td>
    </tr>
    <tr>
    <td class=\"tab_result\">".$tour->getRankingName($tour->rank5)."</td>
    </tr>
 <tr>
      <td class=\"tab_pos\" rowspan=\"11\">Valeur des points</td>
      <td class=\"tab_result\">Grande victoire (".$tour->gv_gap." tds)</td>
      <td class=\"tab_result\">$tour->large_victory points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Victoire</td>
      <td class=\"tab_result\">$tour->victory points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Nul</td>
      <td class=\"tab_result\">$tour->draw points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Petite défaire (".$tour->ll_gap." tds)</td>
      <td class=\"tab_result\">$tour->little_lost points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Défaite</td>
      <td class=\"tab_result\">$tour->lost points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Touchdown marqué</td>
      <td class=\"tab_result\">$tour->td_pos points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Touchdown encaissé</td>
      <td class=\"tab_result\">$tour->td_neg points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Sortie réalisée</td>
      <td class=\"tab_result\">$tour->cas_pos points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Sortie subie</td>
      <td class=\"tab_result\">$tour->cas_neg points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Aggression réussie</td>
      <td class=\"tab_result\">$tour->foul_pos points</td>
    </tr>
    <tr>
      <td class=\"tab_result\">Aggression subie</td>
      <td class=\"tab_result\">$tour->foul_neg points</td>
    </tr>";


    print "</tbody>
</table>";
}

function tournament_html($tour_id) {

    generate_tour_menu($tour_id);

    $tour=new tournament($tour_id);
    $rounds=$tour->getRounds();

    $r=-1;

    if (isset($_GET['details'])) {
        details_html($tour);
    }

    if (isset($_GET['round'])) {
        $r=$_GET['round'];
    }

    $round_max=round::C_UNIQUE;
    if (isset($_GET['total'])) {
        if ($_GET['total']==1) {
            $round_max=round::C_MAX;
        }
    }

    if ($_GET['rank'] == 'general') {
        general_display($tour_id,$r,$round_max);
    }
    if ($_GET['rank'] == 'td_pos') {
        rank_display($tour_id,$r,tournament::C_TD_POS,$round_max);
    }
    if ($_GET['rank'] == 'td_neg') {
        rank_display($tour_id,$r,tournament::C_TD_NEG,$round_max);
    }
    if ($_GET['rank'] == 'cas_pos') {
        rank_display($tour_id,$r,tournament::C_CAS_POS,$round_max);
    }
    if ($_GET['rank'] == 'cas_neg') {
        rank_display($tour_id,$r,tournament::C_CAS_NEG,$round_max);
    }
    if ($_GET['rank'] == 'foul_pos') {
        rank_display($tour_id,$r,tournament::C_FOUL_POS,$round_max);
    }
    if ($_GET['rank'] == 'foul_neg') {
        rank_display($tour_id,$r,tournament::C_FOUL_NEG,$round_max);
    }

    if ($_GET['rank'] == 'matchs') {
        if (isset($_GET['round'])) {
            match_display($_GET['round']);
        }
    }

}
?>
