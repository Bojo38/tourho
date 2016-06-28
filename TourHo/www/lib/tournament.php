<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function generate_tour_menu($tour_id, $link) {

    $tour = new tournament($tour_id, $link);

    $rounds = $tour->getRounds($link);
//print_r($tour);
    echo "<div id=\"titre\">$tour->Name</div><br>";

    print "<div class=\"menu\">
            <ul id=\"nav\" class=\"dropdown dropdown-horizontal\">
                <li><a href=\"index.php?tournament=$tour_id&amp;details\">Détails</a></li>";

    if ($tour->Parameters->byteam) {
        print "<li><span class=\"dir\">Classements par équipe</span>
        <ul>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=general&amp;total=1\">General</a></li>";
        foreach ($tour->Parameters->Criterias as $c) {
            print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=$c->idCriteria&amp;total=1&amp;posneg=1\">$c->Name +</a></li>";
            print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=$c->idCriteria&amp;total=1&amp;posneg=0\">$c->Name -</a></li>";
        }
        print "            </ul>
                </li>";
        print "<li><span class=\"dir\">Classements individuels</span>";
    } else {
        print "<li><span class=\"dir\">Classements finaux</span>";
    }

    print "<ul>
                        <li><a href=\"index.php?tournament=$tour_id&amp;rank=general&amp;total=1\">General</a></li>";
    foreach ($tour->Parameters->Criterias as $c) {
        print "<li><a href=\"index.php?tournament=$tour_id&amp;rank=$c->idCriteria&amp;total=1&amp;posneg=1\">$c->Name +</a></li>";
        print "<li><a href=\"index.php?tournament=$tour_id&amp;rank=$c->idCriteria&amp;total=1&amp;posneg=0\">$c->Name -</a></li>";
    }
    print"</ul>
                </li>";
    $roundindex = 1;
    foreach ($rounds as $round) {
        print "<li><span class=\"dir\">Ronde $roundindex</span>";
        print "<ul>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=matchs&amp;round=$round->rid\">Matchs</a></li>";
        if ($tour->Parameters->byteam) {
            if ($tour->Parameters->team_pairing == 1) {
                print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=matchs&amp;round=$round->rid\">Matchs d'équipes</a></li>";
            }
            print "<li><span class=\"dir\">Classements à la ronde (Equipe)</span>
                        <ul>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=general&amp;round=$round->rid&amp;total=1\">Classement General</a></li>";
            foreach ($tour->Parameters->Criterias as $c) {
                print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=$c->idCriteria&amp;total=1&amp;posneg=1\">$c->Name +</a></li>";
                print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=$c->idCriteria&amp;total=1&amp;posneg=0\">$c->Name -</a></li>";
            }
            print "</ul>
            </li>
            ";
        }
        if ($tour->Parameters->byteam) {
            print "<li><span class = \"dir\">Classements à la ronde (Individuel)</span>";
        } else {
            print "<li><span class=\"dir\">Classements à la ronde</span>";
        }
        print "<ul>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=general&amp;round=$round->rid&amp;total=1\">Classement General</a></li>";
                             foreach ($tour->Parameters->Criterias as $c) {
                print "<li><a href=\"index.php?tournament=$tour_id&amp;rank=$c->idCriteria&amp;total=1&amp;posneg=1\">$c->Name +</a></li>";
                print "<li><a href=\"index.php?tournament=$tour_id&amp;rank=$c->idCriteria&amp;total=1&amp;posneg=0\">$c->Name -</a></li>";
            }
                        print "</ul>
                    </li>
                </ul></li>";
        $roundindex++;
    }
    echo "</ul>
        </div>";
}

function match_display($link, $tid, $round_id) {
    $tour = new tournament($tid, $link);

    $rounds = $tour->getRounds($link);

    $index = 0;

    while (($rounds[$index]->idRound != $round_id) && ($index < count($rounds))) {
        $index = $index + 1;
    }
    if ($index == count($rounds)) {
        exit("Ronde non trouvée");
    }
    $matchs = $rounds[$index]->getCoachMatchs($link);
    $index = $index + 1;

    print "<br><div id=\"titre\">Ronde " . $index . "</div>";
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>";
    if ($tour->Parameters->byteam) {
        print "<td  class=\"tab_titre\">Equipe 1</td>";
    }
    print " <td  class=\"tab_titre\">Coach 1</td>
      <td class=\"tab_titre\" colspan=\"2\">Touchdowns</td>
      <td class=\"tab_titre\">Coach 2</td>";
    if ($tour->Parameters->byteam) {
        print "<td  class=\"tab_titre\" >Equipe 2</td>";
    }

    $i = 0;
    foreach ($tour->Parameters->Criterias as $crit) {
        if ($i != 0) {
            print "<td class=\"tab_result\"colspan=\"2\">$crit->Name</td>";
        }
        $i++;
    }
    print "</tr>";

    foreach ($matchs as $match) {
        $c1 = new coach($match->Coach1_idCoach, $link);
        $c2 = new coach($match->Coach2_idCoach, $link);
        $values = $match->getValues($link);

        $td1 = 0;
        $td2 = 0;

        $nb_crit = count($tour->Parameters->Criterias);

        foreach ($values as $val) {
            if ($val->CriteriaName != $tour->Parameters->Criterias[0]->Name) {
                $td1 = $val->Value1;
                $td2 = $val->Value2;
                break;
            }
        }


        if ($td1 > $td2) {
            $style1 = "winner";
            $style2 = "looser";
        } else {
            if ($td1 < $td2) {
                $style2 = "winner";
                $style1 = "looser";
            } else {
                $style2 = "draw";
                $style1 = "draw";
            }
        }
        print "<tr>";
        if ($tour->Parameters->byteam) {
            $t = new team($c1->Team_idTeam, $link);
            print "<td  class=\"tab_titre\"> $t->Name </td>";
        }
        print "
        <td   class=\"$style1\">$c1->Team<br>$c1->Name - $c1->Roster </td>";
        print "<td class=\"$style1\">$td1</td>";
        print "<td class=\"$style2\">$td2</td>";
        print "<td class=\"$style2\">$c2->Team<br>$c2->Name - $c2->Roster</td>";
        if ($tour->Parameters->byteam) {
            $team = new team($c2->Team_idTeam, $link);
            print "<td  class=\"tab_titre\">$team->Name</td>";
        }
        foreach ($values as $val) {
            if ($val->CriteriaName != $tour->Parameters->Criterias[0]->Name) {
                print "<td class=\"$style1\">$val->Value1</td>";
                print "<td class=\"$style2\">$val->Value2</td>";
            }
        }
        print "</tr>";
    }
    print "</tbody>
</table>";
}

function match_team_display($link, $tid, $round_id) {

    $tour = new tournament($tid, $link);

    $rounds = $tour->getRounds($link);

    $index = 0;

    while (($rounds[$index]->idRound != $round_id) && ($index < count($rounds))) {
        $index = $index + 1;
    }
    if ($index == count($rounds)) {
        exit("Ronde non trouvée");
    }
    $matchs = $rounds[$index]->getTeamMatchs($link);
    $index = $index + 1;

    print "<br><div id=\"titre\">Ronde " . $index . "</div>";
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>";
    print "<td  class=\"tab_titre\">Equipe 1</td>";
    print "<td class=\"tab_titre\" >V1</td>";
    print "<td class=\"tab_titre\" >N</td>";
    print "<td class=\"tab_titre\" >V2</td>";
    print "<td  class=\"tab_titre\" >Equipe 2</td>";

    $i = 0;
    foreach ($tour->Parameters->Criterias as $crit) {
        print "<td class=\"tab_titre\"colspan=\"2\">$crit->Name</td>";
        $i++;
    }
    print "</tr>";

    foreach ($matchs as $match) {
        $t1 = new team($match->Team1_idTeam, $link);
        $t2 = new team($match->Team2_idTeam, $link);
        $values = $match->Values;

        $nb_crit = count($tour->Parameters->Criterias);

        if ($match->V1 > $match->V2) {
            $style1 = "winner";
            $style2 = "looser";
        } else {
            if ($match->V1 < $match->V2) {
                $style2 = "winner";
                $style1 = "looser";
            } else {
                $style2 = "draw";
                $style1 = "draw";
            }
        }
        print "<tr>";

        print "
        <td   class=\"$style1\">$t1->Name</td>";
        print "<td class=\"$style1\"> $match->V1</td>";
        print "<td class=\"$style1\"> $match->N</td>";
        print "<td class=\"$style2\"> $match->V2</td>";
        print "<td class=\"$style2\">$t2->Name</td>";
        foreach ($values as $val) {
            print "<td class=\"$style1\">$val->Value1</td>";
            print "<td class=\"$style2\">$val->Value2</td>";
        }
        print "</tr>";
    }
    print "</tbody>
</table>";
}

function general_display($link, $tid, $rid, $round_max) {
    $tour = new tournament($tid, $link);

    $rounds = $tour->getRounds($link);
    if ($rid == -1) {
        foreach ($rounds as $round) {
            $rid = max($rid, $round->rid);
        }
        $text = "Classement final";
    } else {
        $count = 1;
        foreach ($rounds as $round) {
            if ($rid == $round->rid) {
                if ($round_max == round::C_UNIQUE) {
                    $text = "classement sur la ronde $count";
                } else {
                    $text = "classement à la ronde $count";
                }
                break;
            }
            $count++;
        }
    }

    $ranking_name1 = $tour->getRankingName($tour->Parameters->rank1);
    $ranking_name2 = $tour->getRankingName($tour->Parameters->rank2);
    $ranking_name3 = $tour->getRankingName($tour->Parameters->rank3);
    $ranking_name4 = $tour->getRankingName($tour->Parameters->rank4);
    $ranking_name5 = $tour->getRankingName($tour->Parameters->rank5);

    $counter = 1;
    print "<br><div id=\"titre\">Classement général</div><div id=\"soustitre\">$text</div>";
    $col_count = 0;
    print "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_titre\">N°</td>
      <td class=\"tab_titre\">Coach</td>";
    if ($tour->Parameters->byteam) {
        print "<td class=\"tab_titre\">Equipe</td>";
    }
    print "<td class=\"tab_titre\">Roster</td>";
    if ($ranking_name1 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name1 . "</td>";
        $col_count = $col_count + 1;
    }
    if ($ranking_name2 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name2 . "</td>";
        $col_count = $col_count + 1;
    }
    if ($ranking_name3 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name3 . "</td>";
        $col_count = $col_count + 1;
    }
    if ($ranking_name4 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name4 . "</td>";
        $col_count = $col_count + 1;
    }
    if ($ranking_name5 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name5 . "</td>";
        $col_count = $col_count + 1;
    }
    print "</tr>";


    $ranking = new ranking($link, 'INDIVIDUAL', 'GENERAL', $rid, -1, 0);

    foreach ($ranking->Positions as $pos) {
        $coach = new coach($pos->Coach_idCoach, $link);
        print "<tr><td class=\"tab_pos\">" . $pos->Position . "</td>";
        print "<td class=\"tab_pos\">" . $pos->Name . "</td>";
        if ($tour->Parameters->byteam) {
            $team = new team($pos->Team_idTeam, $link);
            print "<td class=\"tab_pos\">" . $team->Name . "</td>";
        }
        print "<td class=\"tab_pos\">" . $coach->Roster . "</td>";
        if ($ranking_name1 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value1 . "</td>";
        }
        if ($ranking_name2 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value2 . "</td>";
        }
        if ($ranking_name3 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value3 . "</td>";
        }
        if ($ranking_name4 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value4 . "</td>";
        }
        if ($ranking_name5 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value5 . "</td>";
        }
        print "</tr>";
    }

    print "</tbody>
</table>";
}

function general_team_display($link, $tid, $rid, $round_max) {

    $tour = new tournament($tid, $link);

    $rounds = $tour->getRounds($link);
    if ($rid == -1) {
        foreach ($rounds as $round) {
            $rid = max($rid, $round->rid);
        }
        $text = "Classement final";
    } else {
        $count = 1;
        foreach ($rounds as $round) {
            if ($rid == $round->rid) {
                if ($round_max == round::C_UNIQUE) {
                    $text = "classement sur la ronde $count";
                } else {
                    $text = "classement à la ronde $count";
                }
                break;
            }
            $count++;
        }
    }

    if (($tour->Parameters->team_pairing == 1) && ($tour->Parameters->team_victory_only == 1)) {
        $ranking_name1 = $tour->getRankingName($tour->Parameters->rank1_team);
        $ranking_name2 = $tour->getRankingName($tour->Parameters->rank2_team);
        $ranking_name3 = $tour->getRankingName($tour->Parameters->rank3_team);
        $ranking_name4 = $tour->getRankingName($tour->Parameters->rank4_team);
        $ranking_name5 = $tour->getRankingName($tour->Parameters->rank5_team);
    } else {
        $ranking_name1 = $tour->getRankingName($tour->Parameters->rank1);
        $ranking_name2 = $tour->getRankingName($tour->Parameters->rank2);
        $ranking_name3 = $tour->getRankingName($tour->Parameters->rank3);
        $ranking_name4 = $tour->getRankingName($tour->Parameters->rank4);
        $ranking_name5 = $tour->getRankingName($tour->Parameters->rank5);
    }
    $counter = 1;
    print "<br><div id=\"titre\">Classement général</div><div id=\"soustitre\">$text</div>";
    $col_count = 0;
    print "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_titre\">N°</td>
      <td class=\"tab_titre\">Equipe</td>";
    if ($ranking_name1 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name1 . "</td>";
        $col_count = $col_count + 1;
    }
    if ($ranking_name2 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name2 . "</td>";
        $col_count = $col_count + 1;
    }
    if ($ranking_name3 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name3 . "</td>";
        $col_count = $col_count + 1;
    }
    if ($ranking_name4 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name4 . "</td>";
        $col_count = $col_count + 1;
    }
    if ($ranking_name5 != '') {
        print "<td class=\"tab_titre\">" . $ranking_name5 . "</td>";
        $col_count = $col_count + 1;
    }
    print "</tr>";


    $ranking = new ranking($link, 'TEAM', 'GENERAL', $rid, -1, 0);

    foreach ($ranking->Positions as $pos) {
        $team = new team($pos->Team_idTeam, $link);
        print "<tr><td class=\"tab_pos\">" . $pos->Position . "</td>";
        print "<td class=\"tab_pos\">" . $pos->Name . "</td>";
        if ($ranking_name1 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value1 . "</td>";
        }
        if ($ranking_name2 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value2 . "</td>";
        }
        if ($ranking_name3 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value3 . "</td>";
        }
        if ($ranking_name4 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value4 . "</td>";
        }
        if ($ranking_name5 != '') {
            print "<td class=\"tab_pos\">" . $pos->Value5 . "</td>";
        }
        print "</tr>";
    }

    print "</tbody>
</table>";
}

function rank_display($link, $tid, $rid, $rank_type, $round_max, $posneg) {
    $tour = new tournament($tid, $link);
    $text = "";
    $rounds = $tour->getRounds($link);

    if ($rid == -1) {

        foreach ($rounds as $round) {
            $rid = max($rid, $round->rid);
        }
        $text = "classement final";
    } else {
        $count = 1;
        foreach ($rounds as $round) {
            if ($rid == $round->rid) {
                $text = "classement à la ronde $count";
                break;
            }
            $count++;
        }
    }

    $round = new round($link, $rid);
    $criteria = criteria::fromID($rank_type, $link);

    if ($posneg) {
        print "<br><div id=\"titre\">Classement par critère +</div><div id=\"soustitre\">$text</div>";
    } else {
        print "<br><div id=\"titre\">Classement par critère -</div><div id=\"soustitre\">$text</div>";
    }

    print "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_titre\">N°</td>
      <td class=\"tab_titre\">Coach</td>";
    if ($tour->Parameters->byteam) {
        print "<td class=\"tab_titre\">Equipe</td>";
    }
    print "<td class=\"tab_titre\">Roster</td>";
    print "<td class=\"tab_titre\">" . $criteria->Name . "</td>";
    print "</tr>";


    $ranking = new ranking($link, 'INDIVIDUAL', 'CRITERIA', $rid, $rank_type, $posneg);

    foreach ($ranking->Positions as $pos) {
        $coach = new coach($pos->Coach_idCoach, $link);
        print "<tr><td class=\"tab_pos\">" . $pos->Position . "</td>";
        print "<td class=\"tab_pos\">" . $pos->Name . "</td>";
        if ($tour->Parameters->byteam) {
            $team = new team($pos->Team_idTeam, $link);
            print "<td class=\"tab_pos\">" . $team->Name . "</td>";
        }
        print "<td class=\"tab_pos\">" . $coach->Roster . "</td>";

        print "<td class=\"tab_pos\">" . $pos->Value1 . "</td>";

        print "</tr>";
    }

    print "</tbody>
</table>";
}

function rank_team_display($link, $tid, $rid, $rank_type, $round_max, $posneg) {
    $tour = new tournament($tid, $link);
    $text = "";
    $rounds = $tour->getRounds($link);

    if ($rid == -1) {

        foreach ($rounds as $round) {
            $rid = max($rid, $round->rid);
        }
        $text = "classement final";
    } else {
        $count = 1;
        foreach ($rounds as $round) {
            if ($rid == $round->rid) {
                $text = "classement à la ronde $count";
                break;
            }
            $count++;
        }
    }

    $round = new round($link, $rid);
    $criteria = criteria::fromID($rank_type, $link);

    if ($posneg) {
        print "<br><div id=\"titre\">Classement par critère +</div><div id=\"soustitre\">$text</div>";
    } else {
        print "<br><div id=\"titre\">Classement par critère -</div><div id=\"soustitre\">$text</div>";
    }

    print "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_titre\">N°</td>";
    print "<td class=\"tab_titre\">Equipe</td>";
    print "<td class=\"tab_titre\">" . $criteria->Name . "</td>";
    print "</tr>";


    $ranking = new ranking($link, 'TEAM', 'CRITERIA', $rid, $rank_type, $posneg);

    foreach ($ranking->Positions as $pos) {
        print "<tr><td class=\"tab_pos\">" . $pos->Position . "</td>";
        print "<td class=\"tab_pos\">" . $pos->Name . "</td>";
        print "<td class=\"tab_pos\">" . $pos->Value1 . "</td>";
        print "</tr>";
    }

    print "</tbody>
</table>";
}

function details_html($tour, $link) {

    global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;


    $rounds = $tour->getRounds($link);
    $round_number = count($rounds);
    $coach_number = count($tour->getCoachs($link));
    $team_number = count($tour->getTeams($link));

    print "<br><div id=\"titre\">Résumé du tournoi</div>";
    print "<div id=\"soustitre\">$tour->dDate - $tour->Place </div>";
    print "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_pos\" colspan=\"2\">Nombre de rondes</td>
      <td class=\"tab_result\">$round_number</td>
    </tr>";
    if ($tour->Parameters->byteam) {
        if ($tour->Parameters->team_pairing == 1) {
            $txt = "Appariement par équipe";
            if ($tour->Parameters->team_indiv_pairing == 1) {
                $txt2 = "Libre";
            } else {
                $txt2 = "En fonction du classement";
            }
        } else {
            $txt = "Appariement individuel";
        }
        print "<tr>
      <td class=\"tab_pos\" colspan=\"2\">Nombre d'équipes</td>
      <td class=\"tab_result\">$team_number</td>
    </tr>
        <tr>
      <td class=\"tab_pos\" colspan=\"2\">Taille des équipes</td>
      <td class=\"tab_result\">" . $tour->Parameters->teammates . " joueurs</td>
    </tr>
        <tr>
      <td class=\"tab_pos\" colspan=\"2\">Appariement</td>
      <td class=\"tab_result\">$txt</td>
    </tr>";
        if ($tour->Parameters->team_pairing == 1) {
            print "<tr>
                  <td class=\"tab_pos\" colspan=\"2\">Appariement dans les équipes</td>
                  <td class=\"tab_result\">$txt2</td>
                </tr>";
        }
    }

    print "<tr>
        <td class=\"tab_pos\" colspan=\"2\">Nombre de coachs</td>
            <td class=\"tab_result\">$coach_number</td>
            </tr>
            <tr>";
    if ($tour->Parameters->byteam) {
        print"<td class=\"tab_pos\" rowspan=\"5\" colspan=\"2\">Systèmes de classements par équipe</td>";
        print"<td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank1_team) . "</td>
            </tr>
            <tr>
            <td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank2_team) . "</td>
            </tr>
            <tr>
            <td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank3_team) . "</td>
            </tr>
            <tr>
            <td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank4_team) . "</td>
            </tr>
            <tr>
            <td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank5_team) . "</td>
            </tr>
            <tr>";
        if ($tour->Parameters->team_pairing == 1) {
            if ($tour->Parameters->team_victory_only) {
                print "<tr>
            <td colspan =\"2\" class=\"tab_pos\">Système de points</td>
            <td class=\"tab_result\">Points de l'équipe</td>
            </tr>";
                print "<td class=\"tab_pos\" rowspan=\"9\">Valeur des points</td>
            <td class=\"tab_result\">Victoire d'équipe</td>
            <td class=\"tab_result\">" . $tour->Parameters->victory_team . " points</td>
            </tr>
            <tr>
            <td class=\"tab_result\">Nul d'équipe</td>
            <td class=\"tab_result\">" . $tour->Parameters->draw_team . " points</td>
            </tr>
            <tr>
            <td class=\"tab_result\">Défaite d'équipe</td>
            <td class=\"tab_result\">" . $tour->Parameters->lost_team . " points</td>
            </tr>";
                /* <tr>
                  <td class=\"tab_result\">Touchdown marqué</td>
                  <td class=\"tab_result\">$tour->td_pos_team points</td>
                  </tr>
                  <tr>
                  <td class=\"tab_result\">Touchdown encaissé</td>
                  <td class=\"tab_result\">$tour->td_neg_team points</td>
                  </tr>
                  <tr>
                  <td class=\"tab_result\">Sortie réalisée</td>
                  <td class=\"tab_result\">$tour->cas_pos_team points</td>
                  </tr>
                  <tr>
                  <td class=\"tab_result\">Sortie subie</td>
                  <td class=\"tab_result\">$tour->cas_neg_team points</td>
                  </tr>
                  <tr>
                  <td class=\"tab_result\">Aggression réussie</td>
                  <td class=\"tab_result\">$tour->foul_pos_team points</td>
                  </tr>
                  <tr>
                  <td class=\"tab_result\">Aggression subie</td>
                  <td class=\"tab_result\">$tour->foul_neg_team points</td>
                  </tr>"; */
            } else {
                print "<tr>
            <td colspan =\"2\" rowspan=\"3\" class=\"tab_pos\">Système de points</td>
            <td class=\"tab_result\">Cumul des points des joueurs</td>
            </tr>
                <tr>
            <td class=\"tab_result\">Prime à la victoire d'équipe</td>
            <td class=\"tab_result\">" . $tour->Parameters->team_victory_points . " points</td>
            </tr>
            <tr>
            <td class=\"tab_result\">Prime au nul d'équipe</td>
            <td class=\"tab_result\">" . $tour->Parameters->team_draw_points . " points</td>
            </tr>";
            }
        }
        print"<td class=\"tab_pos\" rowspan=\"5\" colspan=\"2\">Systèmes de classements individuel</td>";
    } else {
        print"<td class=\"tab_pos\" rowspan=\"5\" colspan=\"2\">Systèmes de classements</td>";
    }
    $span = 6;
    if ($tour->Parameters->use_large_victory) {
        $span = $span + 1;
    }
    if ($tour->Parameters->use_little_loss) {
        $span = $span + 1;
    }
    print"<td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank1) . "</td>
            </tr>
            <tr>
            <td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank2) . "</td>
            </tr>
            <tr>
            <td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank3) . "</td>
            </tr>
            <tr>
            <td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank4) . "</td>
            </tr>
            <tr>
            <td class=\"tab_result\">" . $tour->getRankingName($tour->Parameters->rank5) . "</td>
            </tr>
            <tr>
            <td class=\"tab_pos\" rowspan=\"" . $span . "\" colspan=\"2\">Valeur des points</td>
            </tr>";
    if ($tour->Parameters->use_large_victory) {
        print "<tr>
            <td class=\"tab_result\">Grande victoire (" . $tour->Parameters->large_victory_gap . " tds)</td>
            <td class=\"tab_result\">" . $tour->Parameters->large_victory . " points</td>
            </tr>";
    }
    print "<tr>
            <td class=\"tab_result\">Victoire</td>
            <td class=\"tab_result\">" . $tour->Parameters->victory . " points</td>
            </tr>
            <tr>
            <td class=\"tab_result\">Nul</td>
            <td class=\"tab_result\">" . $tour->Parameters->draw . " points</td>
            </tr>";

    if ($tour->Parameters->use_little_loss) {
        print "<tr><td class=\"tab_result\">Petite défaire (" . $tour->Parameters->little_loss_gap . " tds)</td>
            <td class=\"tab_result\">" . $tour->Parameters->little_lost . " points</td>
            </tr>";
    }
    print "<tr>
            <td class=\"tab_result\">Défaite</td>
            <td class=\"tab_result\">" . $tour->Parameters->lost . " points</td>
            </tr>";


    print "<tr>
            <td class=\"tab_result\">Abandon</td>
            <td class=\"tab_result\">" . $tour->Parameters->conceeded . " points</td>
            </tr>";

    print "<tr>
            <td class=\"tab_result\">Refus</td>
            <td class=\"tab_result\">" . $tour->Parameters->refused . " points</td>
            </tr>";

//Add criterias display
    print "<tr><td class=\"tab_pos\" colspan=\"2\" rowspan=\"" . (count($tour->Parameters->Criterias) + 1) . "\">Critères</td>";
    print "</tr>";

    foreach ($tour->Parameters->Criterias as $crit) {
        print "<tr>
            <td class=\"tab_result\">$crit->Name</td>
            <td class=\"tab_result\">pour " . $crit->Points_For . " points<br>
            contre " . $crit->Points_Against . " points</td>";
        if ($tour->Parameters->byteam) {
            print "<td class=\"tab_result\">pour (Equipe) " . $crit->Points_Team_For . " points<br>
                            contre (Equipe)" . $crit->Points_Team_Against . " points</td>";
        }
        print "</tr>";
    }

// Ajouter les bonus de tables
    if ($tour->Parameters->table_bonus) {
        print "<tr>
            <td class=\"tab_pos\">Bonus de table</td>
            <td class=\"tab_result\">x " . $tour->Parameters->table_bonus_coeff . "</td>
            </tr>";
    }

    if ($tour->Parameters->table_bonus_per_round) {
        print "<tr>
            <td class=\"tab_pos\">Ajustement des bonus par ronde<br>Système \"Galanthil\"</td>
            <td class=\"tab_result\">Oui</td>
            </tr>";
    }

    print "</tbody> </table>";
}

function tournament_html($tour_id) {

    global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;
    $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());


    generate_tour_menu($tour_id, $link);

    $tour = new tournament($tour_id, $link);
//$rounds = $tour->global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;
//getRounds();

    $r = -1;

    if (isset($_GET['details'])) {
        details_html($tour, $link);
    }

    if (isset($_GET['round'])) {
        $r = $_GET['round'];
    }

    $round_max = round::C_UNIQUE;
    if (isset($_GET['total'])) {
        if ($_GET['total'] == 1) {
            $round_max = round::C_MAX;
        }
    }

    if (isset($_GET['rank'])) {
        if ($_GET['rank'] == 'matchs') {
            match_display($link, $tour_id, $r);
        } else {
            if ($_GET['rank'] == 'general') {
                general_display($link, $tour_id, $r, $round_max);
            } else {
                if (isset($_GET['posneg'])) {
                    rank_display($link, $tour_id, $r, $_GET['rank'], $round_max, $_GET['posneg']);
                }
            }
        }
    }

    if (isset($_GET['rank_team'])) {
        if ($_GET['rank_team'] == 'matchs') {
            match_team_display($link, $tour_id, $r);
        } else {
            if ($_GET['rank_team'] == 'general') {
                general_team_display($link, $tour_id, $r, $round_max);
            } else {
                if (isset($_GET['posneg'])) {
                    rank_team_display($link, $tour_id, $r, $_GET['rank_team'], $round_max, $_GET['posneg']);
                }
            }
        }
    }

    /* if ($_GET['rank_team'] == 'general') {
      general_team_display($tour_id, $r, $round_max);
      }

      if ($_GET['rank'] == 'minus') {
      general_display($tour_id, $r, $round_max, 1);
      }
      if ($_GET['rank'] == 'td_pos') {
      rank_display($tour_id, $r, tournament::C_TD_POS, $round_max);
      }
      if ($_GET['rank'] == 'td_neg') {
      rank_display($tour_id, $r, tournament::C_TD_NEG, $round_max);
      }
      if ($_GET['rank'] == 'cas_pos') {
      rank_display($tour_id, $r, tournament::C_CAS_POS, $round_max);
      }
      if ($_GET['rank'] == 'cas_neg') {
      rank_display($tour_id, $r, tournament::C_CAS_NEG, $round_max);
      }
      if ($_GET['rank'] == 'foul_pos') {
      rank_display($tour_id, $r, tournament::C_FOUL_POS, $round_max);
      }
      if ($_GET['rank'] == 'foul_neg') {
      rank_display($tour_id, $r, tournament::C_FOUL_NEG, $round_max);
      }

      if ($_GET['rank_team'] == 'td_pos') {
      rank_team_display($tour_id, $r, tournament::C_TD_POS, $round_max);
      }
      if ($_GET['rank_team'] == 'td_neg') {
      rank_team_display($tour_id, $r, tournament::C_TD_NEG, $round_max);
      }
      if ($_GET['rank_team'] == 'cas_pos') {
      rank_team_display($tour_id, $r, tournament::C_CAS_POS, $round_max);
      }
      if ($_GET['rank_team'] == 'cas_neg') {
      rank_team_display($tour_id, $r, tournament::C_CAS_NEG, $round_max);
      }
      if ($_GET['rank_team'] == 'foul_pos') {
      rank_team_display($tour_id, $r, tournament::C_FOUL_POS, $round_max);
      }
      if ($_GET['rank_team'] == 'foul_neg') {
      rank_team_display($tour_id, $r, tournament::C_FOUL_NEG, $round_max);
      }


      if ($_GET['rank'] == 'matchs') {
      if (isset($_GET['round'])) {
      match_display($tour_id, $_GET['round']);
      }
      }
      if ($_GET['rank_team'] == 'matchs') {
      if (isset($_GET['round'])) {
      match_team_display($tour_id, $_GET['round']);
      }
      } */

    mysql_close($link);
}

?>
