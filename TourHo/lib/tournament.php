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
                <li><span class=\"dir\">Classements finaux</span>
                    <ul>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=general\">General</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_pos\">Touchdowns marqués</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_neg\">Touchdowns encaissés</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_pos\">Sorties réalisées</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_neg\">Sorties subies</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_pos\">Agressions réussies</a></li>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_neg\">Agressions subies</a></li>
                    </ul>
                </li>";    
    foreach ($rounds as $round) {
        print "<li><span class=\"dir\">Ronde $round->number</span>";
        print "<ul>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=matchs&amp;round=$round->rid\">Matchs</a></li>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=rank&amp;round=$round->rid\">Classement de la ronde</a></li>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=general&amp;round=$round->rid\">Classement General</a></li>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_pos&amp;round=$round->rid\">Touchdowns marqués</a></li>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_neg&amp;round=$round->rid\">Touchdowns encaissés</a></li>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_pos&amp;round=$round->rid\">Sorties réalisées</a></li>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_neg&amp;round=$round->rid\">Sorties subies</a></li>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_pos&amp;round=$round->rid\">Agressions réussies</a></li>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_neg&amp;round=$round->rid\">Agressions subies</a></li>
                </ul></li>";
    }
    echo "</ul>
        </div>";
}

function match_display($round_id) {
    $round=new round($round_id);
    $matchs=$round->getMatchs();

    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td colspan=\"1\" rowspan=\"3\">Coach 1</td>
      <td colspan=\"2\">Toudowns</td>
      <td colspan=\"1\" rowspan=\"3\">Coach 2</td>
    </tr>
    <tr>
      <td colspan=\"2\">Sorties</td>
    </tr>
    <tr>
      <td colspan=\"2\">Aggressions</td>
    </tr>";
    foreach ($matchs as $match) {
        $c1=new coach($match->f_cid1);
        $c2=new coach($match->f_cid2);
        print "<tr>;<td colspan=\"1\" rowspan=\"3\">$c1->name<br>$c1->team<br>$c1->race </td>";
        print "<td>$match->td1</td>";
        print "<td>$match->td2</td>";
        print "<td colspan=\"1\" rowspan=\"3\">$c2->name<br>$c2->team<br>$c2->race</td>";
        print "</tr>
    <tr>";
        print "<td>$match->cas1</td>";
        print "<td>$match->cas2</td>";
        print "</tr>
    <tr>";
        print "<td>$match->foul1</td>";
        print "<td>$match->foul2</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}

function general_display($tid,$rid) {
    $tour=new tournament($tid);

    if ($rid==-1) {
        $rounds=$tour->getRounds();
        foreach ($rounds as $round) {
            $rid=max($rid,$round->rid);
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

        $element['Value1']=$tour->getRankingValue($coach->cid,$rid,$tour->rank1);
        $element['Value2']=$tour->getRankingValue($coach->cid,$rid,$tour->rank2);
        $element['Value3']=$tour->getRankingValue($coach->cid,$rid,$tour->rank3);
        $element['Value4']=$tour->getRankingValue($coach->cid,$rid,$tour->rank4);
        $element['Value5']=$tour->getRankingValue($coach->cid,$rid,$tour->rank5);
        
        array_push($list, $element);
        array_push($sort_list1,$element['Value1']);
        array_push($sort_list2,$element['Value2']);
        array_push($sort_list3,$element['Value3']);
        array_push($sort_list4,$element['Value4']);
        array_push($sort_list5,$element['Value5']);
    }



    array_multisort($sort_list1, SORT_DESC,$sort_list2, SORT_DESC,$sort_list3, SORT_DESC,$sort_list4, SORT_DESC,$sort_list5, SORT_DESC,$list);
    $counter=1;
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
    <td>N°</td>
      <td>Coach</td>
      <td>Equipe</td>
      <td>Roster</td>
      <td>".$ranking_name1."</td>
        <td>".$ranking_name2."</td>
        <td>".$ranking_name3."</td>
        <td>".$ranking_name4."</td>
        <td>".$ranking_name5."</td>
    </tr>";
    foreach ($list as $element) {
        print "<td>".$counter++."</td>";
        print "<td>".$element['Coach']."</td>";
        print "<td>".$element['Team']."</td>";
        print "<td>".$element['Race']."</td>";
        print "<td>".$element['Value1']."</td>";
        print "<td>".$element['Value2']."</td>";
        print "<td>".$element['Value3']."</td>";
        print "<td>".$element['Value4']."</td>";
        print "<td>".$element['Value5']."</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}


function rank_display($tid,$rid,$rank_type) {
    $tour=new tournament($tid);

    if ($rid==-1) {
        $rounds=$tour->getRounds();
        foreach ($rounds as $round) {
            $rid=max($rid,$round->rid);
        }
    }
    $round=new round($rid);

    $coachs=$tour->getCoachs();

    $list =array();
    $sort_list=array();

    foreach ($coachs as $coach) {

        $element =array();
        $element['Coach']=$coach->name;
        $element['Team']=$coach->team;
        $element['Race']=$coach->race;
        $element['Value']=$tour->getValueByCoach($coach->cid, $rid, $rank_type);
        array_push($list, $element);
        array_push($sort_list,$element['Value']);
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

    array_multisort($sort_list, SORT_DESC,$list);
    $counter=1;
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"1\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
    <td>N°</td>
      <td>Coach</td>
      <td>Equipe</td>
      <td>Roster</td>
      <td>".$ranking_name."</td>
    </tr>";
    foreach ($list as $element) {
        print "<td>".$counter++."</td>";
        print "<td>".$element['Coach']."</td>";
        print "<td>".$element['Team']."</td>";
        print "<td>".$element['Race']."</td>";
        print "<td>".$element['Value']."</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}

function tournament_html($tour_id) {

    generate_tour_menu($tour_id);

    $tour=new tournament($tour_id);
    $rounds=$tour->getRounds();

    $r=-1;
    if (isset($_GET['round'])) {
        $r=$_GET['round'];
    }

    if ($_GET['rank'] == 'general') {
        general_display($tour_id,$r);
    }
    if ($_GET['rank'] == 'td_pos') {
        rank_display($tour_id,$r,tournament::C_TD_POS);
    }
    if ($_GET['rank'] == 'td_neg') {
        rank_display($tour_id,$r,tournament::C_TD_NEG);

    }
    if ($_GET['rank'] == 'cas_pos') {
        rank_display($tour_id,$r,tournament::C_CAS_POS);
    }
    if ($_GET['rank'] == 'cas_neg') {
        rank_display($tour_id,$r,tournament::C_CAS_NEG);
    }
    if ($_GET['rank'] == 'foul_pos') {
        rank_display($tour_id,$r,tournament::C_FOUL_POS);
    }
    if ($_GET['rank'] == 'foul_neg') {
        rank_display($tour_id,$r,tournament::C_FOUL_NEG);
    }

    if ($_GET['rank'] == 'matchs') {
        if (isset($_GET['round'])) {
            match_display($_GET['round']);
        }
    }

}
?>
