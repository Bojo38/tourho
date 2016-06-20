<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function generate_tour_menu($tour_id,$link) {

    $tour = new tournament($tour_id,$link);
    
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
            print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=$c->idCriteria&amp;total=1\">$c->Name +</a></li>";
            print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=$c->idCriteria&amp;total=1\">$c->Name -</a></li>";
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
        print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=$c->idCriteria&amp;total=1\">$c->Name +</a></li>";
        print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=$c->idCriteria&amp;total=1\">$c->Name -</a></li>";
    }
    print"</ul>
                </li>";
    $roundindex=1;
    foreach ($rounds as $round) {
        print "<li><span class=\"dir\">Ronde $roundindex</span>";
        print "<ul>
                    <li><a href=\"index.php?tournament=$tour_id&amp;rank=matchs&amp;round=$round->rid\">Matchs</a></li>";
        if ($tour->Parameters->byteam) {
            if ($tour->team_pairing == 1) {
                print "<li><a href=\"index.php?tournament=$tour_id&amp;rank_team=matchs&amp;round=$round->rid\">Matchs d'équipes</a></li>";
            }
            print "<li><span class=\"dir\">Classements sur la ronde (Equipe)</span>
                        <ul>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=general&amp;round=$round->rid\">Classement General</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=td_pos&amp;round=$round->rid\">Touchdowns marqués</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=td_neg&amp;round=$round->rid\">Touchdowns encaissés</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=cas_pos&amp;round=$round->rid\">Sorties réalisées</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=cas_neg&amp;round=$round->rid\">Sorties subies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=foul_pos&amp;round=$round->rid\">Agressions réussies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=foul_neg&amp;round=$round->rid\">Agressions subies</a></li>
                        </ul>
                    </li>
                    <li><span class=\"dir\">Classements à la ronde (Equipe)</span>
                        <ul>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=general&amp;round=$round->rid&amp;total=1\">Classement General</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=td_pos&amp;round=$round->rid&amp;total=1\">Touchdowns marqués</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=td_neg&amp;round=$round->rid&amp;total=1\">Touchdowns encaissés</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=cas_pos&amp;round=$round->rid&amp;total=1\">Sorties réalisées</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=cas_neg&amp;round=$round->rid&amp;total=1\">Sorties subies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=foul_pos&amp;round=$round->rid&amp;total=1\">Agressions réussies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank_team=foul_neg&amp;round=$round->rid&amp;total=1\">Agressions subies</a></li>
                        </ul>
                    </li>
                ";
        }
        if ($tour->team_tournament) {
            print "<li><span class=\"dir\">Classements sur la ronde (Individuel)</span>";
        } else {
            print "<li><span class=\"dir\">Classements sur la ronde</span>";
        }

        print "        <ul>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=general&amp;round=$round->rid\">Classement General</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_pos&amp;round=$round->rid\">Touchdowns marqués</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_neg&amp;round=$round->rid\">Touchdowns encaissés</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_pos&amp;round=$round->rid\">Sorties réalisées</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_neg&amp;round=$round->rid\">Sorties subies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_pos&amp;round=$round->rid\">Agressions réussies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_neg&amp;round=$round->rid\">Agressions subies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=minus&amp;round=$round->rid\">Minus</a></li>
                        </ul>
                    </li>";
        if ($tour->team_tournament) {
            print "<li><span class=\"dir\">Classements à la ronde (Individuel)</span>";
        } else {
            print "<li><span class=\"dir\">Classements à la ronde</span>";
        }
        print "<ul>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=general&amp;round=$round->rid&amp;total=1\">Classement General</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_pos&amp;round=$round->rid&amp;total=1\">Touchdowns marqués</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=td_neg&amp;round=$round->rid&amp;total=1\">Touchdowns encaissés</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_pos&amp;round=$round->rid&amp;total=1\">Sorties réalisées</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=cas_neg&amp;round=$round->rid&amp;total=1\">Sorties subies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_pos&amp;round=$round->rid&amp;total=1\">Agressions réussies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=foul_neg&amp;round=$round->rid&amp;total=1\">Agressions subies</a></li>
                            <li><a href=\"index.php?tournament=$tour_id&amp;rank=minus&amp;round=$round->rid&amp;total=1\">Minus</a></li>
                        </ul>
                    </li>
                </ul></li>";
        $roundindex++;
    }
    echo "</ul>
        </div>";
}

function match_display($tid, $round_id) {
    $tour = new tournament($tid);

    $round = new round($round_id);
    $matchs = $round->getMatchs();
    print "<br><div id=\"titre\">Ronde $round->number</div>";
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>";
    if ($tour->team_tournament) {
        print "<td colspan=\"1\" rowspan=\"2\" class=\"tab_titre\">Clan 1</td>";
    }
    print " <td colspan=\"1\" rowspan=\"2\" class=\"tab_titre\">Coach 1</td>
      <td colspan=\"4\" class=\"tab_titre\">Touchdowns</td>
      <td colspan=\"1\" rowspan=\"2\" class=\"tab_titre\">Coach 2</td>";
    if ($tour->team_tournament) {
        print "<td colspan=\"1\" rowspan=\"2\" class=\"tab_titre\">Clan 2</td>";
    }
    print "
    </tr>
    <tr>
      <td colspan=\"2\" class=\"tab_titre\">Sorties</td>
      <td colspan=\"2\" class=\"tab_titre\">Aggressions</td>
    </tr>";
    foreach ($matchs as $match) {
        $c1 = new coach($match->f_cid1);
        $c2 = new coach($match->f_cid2);
        if ($match->td1 > $match->td2) {
            $style1 = "winner";
            $style2 = "looser";
        } else {
            if ($match->td1 < $match->td2) {
                $style2 = "winner";
                $style1 = "looser";
            } else {
                $style2 = "draw";
                $style1 = "draw";
            }
        }
        print "<tr>";
        if ($tour->team_tournament) {
            $t = new team($c1->f_teid);
            print "<td colspan=\"1\" rowspan=\"2\" class=\"tab_titre\"> $t->name </td>";
        }
        print "
        <td colspan=\"1\" rowspan=\"2\" class=\"$style1\">$c1->team<br>$c1->name - $c1->race </td>";
        print "<td colspan=\"2\" class=\"$style1\">$match->td1</td>";
        print "<td colspan=\"2\" class=\"$style2\">$match->td2</td>";
        print "<td colspan=\"1\" rowspan=\"2\" class=\"$style2\">$c2->team<br>$c2->name - $c2->race</td>";
        if ($tour->team_tournament) {
            $team = new team($c2->f_teid);
            print "<td colspan=\"1\" rowspan=\"2\" class=\"tab_titre\">$team->name</td>";
        }
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

function match_team_display($tid, $round_id) {
    $tour = new tournament($tid);
    $round = new round($round_id);
    $matchs = $round->getMatchs();
    $teams = $tour->getTeams();

    print "<br><div id=\"titre\">Ronde $round->number</div>";
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>";
    print "<td class=\"tab_titre\">Clan 1</td>";

    print " <td class=\"tab_titre\">V1</td>
      <td class=\"tab_titre\">N</td>
      <td  class=\"tab_titre\">V 2</td>";
    print "<td  class=\"tab_titre\">Clan 2</td></tr>";

    $displays = array();

    for ($i = 0; $i < count($teams) / 2; $i++) {
        $team1 = $teams[2 * $i];
        $team2 = $team1;

        $team_matchs = array();

        foreach ($matchs as $match) {
            $c1 = new coach($match->f_cid1);
            $c2 = new coach($match->f_cid2);
            $t1 = new team($c1->f_teid);
            $t2 = new team($c2->f_teid);

            if ($t1->teid == $team1->teid) {
                $team2 = $t2;
                array_push($team_matchs);
            }
            if ($t2->teid == $team1->teid) {
                $team2 = $t1;
                array_push($team_matchs, $match);
            }
        }

        $nbVictory = 0;
        $nbDraw = 0;
        $nbLost = 0;
        foreach ($team_matchs as $team_match) {

            $c1 = new coach($team_match->f_cid1);
            $c2 = new coach($team_match->f_cid2);
            $t1 = new team($c1->f_teid);
            $t2 = new team($c2->f_teid);

            if ($t1->teid == $team1->teid) {
                if ($team_match->td1 > $team_match->td2) {
                    $nbVictory++;
                } else {
                    if ($team_match->td1 < $team_match->td2) {
                        $nbLost++;
                    } else {
                        $nbDraw++;
                    }
                }
            }
            if ($t2->teid == $team1->teid) {
                if ($team_match->td1 < $team_match->td2) {
                    $nbVictory++;
                } else {
                    if ($team_match->td1 > $team_match->td2) {
                        $nbLost++;
                    } else {
                        $nbDraw++;
                    }
                }
            }
        }
        $display = array();

        $display['Team1'] = $team1->name;
        $display['Team2'] = $team2->name;
        $display['V1'] = $nbVictory;
        $display['V2'] = $nbLost;
        $display['N'] = $nbDraw;

        array_push($displays, $display);
    }

    foreach ($displays as $display) {


        if ($display['V1'] > $display['V2']) {
            $style1 = "winner";
            $style2 = "looser";
        } else {
            if ($display['V1'] < $display['V2']) {
                $style2 = "winner";
                $style1 = "looser";
            } else {
                $style2 = "draw";
                $style1 = "draw";
            }
        }

        print "<tr>";
        print "<td class=\"$style1\"> " . $display['Team1'] . "</td>";
        print "<td class=\"$style1\"> " . $display['V1'] . "</td>";
        print "<td class=\"draw\"> " . $display['N'] . "</td>";
        print "<td class=\"$style2\"> " . $display['V2'] . "</td>";
        print "<td class=\"$style2\"> " . $display['Team2'] . "</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}

function general_display($tid, $rid, $round_max, $minus) {
    $tour = new tournament($tid);

    $rounds = $tour->getRounds();
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

    $round = new round($rid);

    $coachs = $tour->getCoachs();

    global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

    $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
    mysql_select_db($db_name, $link);

    $list = array();
    $sort_list1 = array();
    $sort_list2 = array();
    $sort_list3 = array();
    $sort_list4 = array();
    $sort_list5 = array();

    $ranking_name1 = $tour->getRankingName($tour->rank1);
    $ranking_name2 = $tour->getRankingName($tour->rank2);
    $ranking_name3 = $tour->getRankingName($tour->rank3);
    $ranking_name4 = $tour->getRankingName($tour->rank4);
    $ranking_name5 = $tour->getRankingName($tour->rank5);

    foreach ($coachs as $coach) {
        $element = array();
        $element['Coach'] = $coach->name;
        $element['Team'] = $coach->team;
        $element['Race'] = $coach->race;

        $element['Value1'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank1, $round_max);
        $element['Value2'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank2, $round_max);
        $element['Value3'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank3, $round_max);
        $element['Value4'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank4, $round_max);
        $element['Value5'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank5, $round_max);

        array_push($list, $element);
        array_push($sort_list1, $element['Value1']);
        array_push($sort_list2, $element['Value2']);
        array_push($sort_list3, $element['Value3']);
        array_push($sort_list4, $element['Value4']);
        array_push($sort_list5, $element['Value5']);
    }



    array_multisort($sort_list1, SORT_DESC, $sort_list2, SORT_DESC, $sort_list3, SORT_DESC, $sort_list4, SORT_DESC, $sort_list5, SORT_DESC, $list);
    $counter = 1;
    if ($minus == 1) {
        print "<br><div id=\"titre\">Classement Minus</div><div id=\"soustitre\">$text</div>";
    } else {
        print "<br><div id=\"titre\">Classement général</div><div id=\"soustitre\">$text</div>";
    }
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_titre\">N°</td>
      <td class=\"tab_titre\">Coach</td>
      <td class=\"tab_titre\">Equipe</td>
      <td class=\"tab_titre\">Roster</td>
      <td class=\"tab_titre\">" . $ranking_name1 . "</td>
        <td class=\"tab_titre\">" . $ranking_name2 . "</td>
        <td class=\"tab_titre\">" . $ranking_name3 . "</td>
        <td class=\"tab_titre\">" . $ranking_name4 . "</td>
        <td class=\"tab_titre\">" . $ranking_name5 . "</td>
    </tr>";
    foreach ($list as $element) {
        if ($counter == 1) {
            $suffix = "_1";
        } else {
            if ($counter == count($list)) {
                $suffix = "_last";
            } else {
                $suffix = "";
            }
        }

        if (
                ($minus == 0) ||
                (($minus == 1) &&
                (
                ($element['Race'] == "Gobelin") ||
                ($element['Race'] == "Halfling") ||
                ($element['Race'] == "Ogre")
                ))
        ) {
            print "<tr>";
            print "<td  class=\"tab_pos$suffix\" id=\"" . $counter . "\">" . $counter++ . "</td>";
            print "<td class=\"tab_result$suffix\">" . $element['Coach'] . "</td>";
            if ($element['Team'] == "") {
                $element['Team'] = "&nbsp;";
            }
            print "<td class=\"tab_result$suffix\">" . $element['Team'] . "</td>";

            print "<td class=\"tab_result$suffix\">" . $element['Race'] . "</td>";
            print "<td class=\"tab_result$suffix\">" . $element['Value1'] . "</td>";
            print "<td class=\"tab_result$suffix\">" . $element['Value2'] . "</td>";
            print "<td class=\"tab_result$suffix\">" . $element['Value3'] . "</td>";
            print "<td class=\"tab_result$suffix\">" . $element['Value4'] . "</td>";
            print "<td class=\"tab_result$suffix\">" . $element['Value5'] . "</td>";
            print "</tr>";
        }
    }
    print "</tbody>
</table>";
}

function general_team_display($tid, $rid, $round_max) {
    $tour = new tournament($tid);

    $rounds = $tour->getRounds();
    if ($rid == -1) {

        foreach ($rounds as $round) {
            $rid = max($rid, $round->rid);
        }
        $text = "classement final";
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

    $round = new round($rid);
    $teams = $tour->getTeams();

    global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

    $link = mysql_connect($db_host, $db_user, $db_passwd) or die("Impossible de se connecter : " . mysql_error());
    mysql_select_db($db_name, $link);

    $list = array();
    $sort_list1 = array();
    $sort_list2 = array();
    $sort_list3 = array();
    $sort_list4 = array();
    $sort_list5 = array();

    $ranking_name1 = $tour->getRankingName($tour->rank1);
    $ranking_name2 = $tour->getRankingName($tour->rank2);
    $ranking_name3 = $tour->getRankingName($tour->rank3);
    $ranking_name4 = $tour->getRankingName($tour->rank4);
    $ranking_name5 = $tour->getRankingName($tour->rank5);

    foreach ($teams as $team) {
        $element = array();
        $element['Name'] = $team->name;

        $element['Value1'] = $tour->getRankingTeamValue($team->teid, $rid, $tour->rank1, $round_max);
        $element['Value2'] = $tour->getRankingTeamValue($team->teid, $rid, $tour->rank2, $round_max);
        $element['Value3'] = $tour->getRankingTeamValue($team->teid, $rid, $tour->rank3, $round_max);
        $element['Value4'] = $tour->getRankingTeamValue($team->teid, $rid, $tour->rank4, $round_max);
        $element['Value5'] = $tour->getRankingTeamValue($team->teid, $rid, $tour->rank5, $round_max);

        array_push($list, $element);
        array_push($sort_list1, $element['Value1']);
        array_push($sort_list2, $element['Value2']);
        array_push($sort_list3, $element['Value3']);
        array_push($sort_list4, $element['Value4']);
        array_push($sort_list5, $element['Value5']);
    }



    array_multisort($sort_list1, SORT_DESC, $sort_list2, SORT_DESC, $sort_list3, SORT_DESC, $sort_list4, SORT_DESC, $sort_list5, SORT_DESC, $list);
    $counter = 1;
    print "<br><div id=\"titre\">Classement général</div><div id=\"soustitre\">$text</div>";
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_titre\">N°</td>
      <td class=\"tab_titre\">Clan</td>
      <td class=\"tab_titre\">" . $ranking_name1 . "</td>
        <td class=\"tab_titre\">" . $ranking_name2 . "</td>
        <td class=\"tab_titre\">" . $ranking_name3 . "</td>
        <td class=\"tab_titre\">" . $ranking_name4 . "</td>
        <td class=\"tab_titre\">" . $ranking_name5 . "</td>
    </tr>";
    foreach ($list as $element) {
        if ($counter == 1) {
            $suffix = "_1";
        } else {
            if ($counter == count($list)) {
                $suffix = "_last";
            } else {
                $suffix = "";
            }
        }
        print "<td  class=\"tab_pos$suffix\" id=\"" . $counter . "\">" . $counter++ . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Name'] . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Value1'] . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Value2'] . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Value3'] . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Value4'] . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Value5'] . "</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}

function rank_display($tid, $rid, $rank_type, $round_max) {
    $tour = new tournament($tid);
    $text = "";
    $rounds = $tour->getRounds();

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
    $round = new round($rid);

    $coachs = $tour->getCoachs();

    $list = array();
    $sort_list = array();

    $sort_list1 = array();
    $sort_list2 = array();
    $sort_list3 = array();
    $sort_list4 = array();
    $sort_list5 = array();

    foreach ($coachs as $coach) {

        $element = array();
        $element['Coach'] = $coach->name;
        $element['Team'] = $coach->team;
        $element['Race'] = $coach->race;
        $element['Value'] = $tour->getValueByCoach($coach->cid, $rid, $rank_type, $round_max);

        array_push($list, $element);
        array_push($sort_list, $element['Value']);

        $element['Value1'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank1, $round_max);
        $element['Value2'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank2, $round_max);
        $element['Value3'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank3, $round_max);
        $element['Value4'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank4, $round_max);
        $element['Value5'] = $tour->getRankingValue($coach->cid, $rid, $tour->rank5, $round_max);

        array_push($sort_list1, $element['Value1']);
        array_push($sort_list2, $element['Value2']);
        array_push($sort_list3, $element['Value3']);
        array_push($sort_list4, $element['Value4']);
        array_push($sort_list5, $element['Value5']);
    }

    switch ($rank_type) {
        case tournament::C_TD_POS:
            $ranking_name = "Touchdown marqués";
            break;
        case tournament::C_TD_NEG:
            $ranking_name = "Touchdown encaissés";
            break;
        case tournament::C_CAS_POS:
            $ranking_name = "Sorties réalisées";
            break;
        case tournament::C_CAS_NEG:
            $ranking_name = "Sorties subies";
            break;
        case tournament::C_FOUL_POS:
            $ranking_name = "Agrressions réussies";
            break;
        case tournament::C_FOUL_NEG:
            $ranking_name = "Agressions subies";
            break;
    }

    //array_multisort($sort_list, SORT_DESC,$list);
    array_multisort($sort_list, SORT_DESC, $sort_list1, SORT_DESC, $sort_list2, SORT_DESC, $sort_list3, SORT_DESC, $sort_list4, SORT_DESC, $sort_list5, SORT_DESC, $list);
    $counter = 1;
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
      <td class=\"tab_titre\">" . $ranking_name . "</td>
    </tr>";
    foreach ($list as $element) {
        if ($counter == 1) {
            $suffix = "_1";
        } else {
            if ($counter == count($list)) {
                $suffix = "_last";
            } else {
                $suffix = "";
            }
        }
        print "<td class=\"tab_pos$suffix\">" . $counter++ . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Coach'] . "</td>";
        if ($element['Team'] == "") {
            $element['Team'] = "&nbsp;";
        }
        print "<td class=\"tab_result$suffix\">" . $element['Team'] . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Race'] . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Value'] . "</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}

function rank_team_display($tid, $rid, $rank_type, $round_max) {
    $tour = new tournament($tid);
    $text = "";
    $rounds = $tour->getRounds();

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
    $round = new round($rid);

    $teams = $tour->getTeams();

    $list = array();
    $sort_list = array();
    $sort_list1 = array();
    $sort_list2 = array();
    $sort_list3 = array();
    $sort_list4 = array();
    $sort_list5 = array();

    foreach ($teams as $team) {

        $element = array();
        $element['Name'] = $team->name;
        $value = 0;
        $value1 = 0;
        $value2 = 0;
        $value3 = 0;
        $value4 = 0;
        $value5 = 0;

        $coachs = $team->getCoachs();
        foreach ($coachs as $coach) {
            $value+= $tour->getValueByCoach($coach->cid, $rid, $rank_type, $round_max);
            $value1+=$tour->getRankingValue($coach->cid, $rid, $tour->rank1, $round_max);
            $value2+=$tour->getRankingValue($coach->cid, $rid, $tour->rank2, $round_max);
            $value3+=$tour->getRankingValue($coach->cid, $rid, $tour->rank3, $round_max);
            $value4+=$tour->getRankingValue($coach->cid, $rid, $tour->rank4, $round_max);
            $value5+=$tour->getRankingValue($coach->cid, $rid, $tour->rank5, $round_max);
        }

        $element['Value'] = $value;
        $element['Value1'] = $value1;
        $element['Value2'] = $value2;
        $element['Value3'] = $value3;
        $element['Value4'] = $value4;
        $element['Value5'] = $value5;

        array_push($list, $element);

        array_push($sort_list, $element['Value']);
        array_push($sort_list1, $element['Value1']);
        array_push($sort_list2, $element['Value2']);
        array_push($sort_list3, $element['Value3']);
        array_push($sort_list4, $element['Value4']);
        array_push($sort_list5, $element['Value5']);
    }

    switch ($rank_type) {
        case tournament::C_TD_POS:
            $ranking_name = "Touchdowns marqués";
            break;
        case tournament::C_TD_NEG:
            $ranking_name = "Touchdowns encaissés";
            break;
        case tournament::C_CAS_POS:
            $ranking_name = "Sorties réalisées";
            break;
        case tournament::C_CAS_NEG:
            $ranking_name = "Sorties subies";
            break;
        case tournament::C_FOUL_POS:
            $ranking_name = "Agrressions réussies";
            break;
        case tournament::C_FOUL_NEG:
            $ranking_name = "Agressions subies";
            break;
    }

    //array_multisort($sort_list, SORT_DESC,$list);
    array_multisort($sort_list, SORT_DESC, $sort_list1, SORT_DESC, $sort_list2, SORT_DESC, $sort_list3, SORT_DESC, $sort_list4, SORT_DESC, $sort_list5, SORT_DESC, $list);
    $counter = 1;
    print "<br><div id=\"titre\">$ranking_name</div><div id=\"soustitre\">$text</div>";
    echo "<table
 style=\"border-color: black; width: 100%; text-align: left; margin-left: auto; margin-right: auto;text-align:center;\"
 border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
  <tbody>
    <tr>
      <td class=\"tab_titre\">N°</td>
      <td class=\"tab_titre\">Clan</td>
      <td class=\"tab_titre\">" . $ranking_name . "</td>
    </tr>";
    foreach ($list as $element) {
        if ($counter == 1) {
            $suffix = "_1";
        } else {
            if ($counter == count($list)) {
                $suffix = "_last";
            } else {
                $suffix = "";
            }
        }
        print "<td class=\"tab_pos$suffix\">" . $counter++ . "</td>";
        print "<td class=\"tab_result$suffix\">" . $element['Name'] . "</td>";
        if ($element['Team'] == "") {
            $element['Team'] = "&nbsp;";
        }
        print "<td class=\"tab_result$suffix\">" . $element['Value'] . "</td>";
        print "</tr>";
    }
    print "</tbody>
</table>";
}

function details_html($tour,$link) {

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
      <td class=\"tab_result\">".$tour->Parameters->teammates." joueurs</td>
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
            <td class=\"tab_result\">".$tour->Parameters->victory_team." points</td>
            </tr>
            <tr>
            <td class=\"tab_result\">Nul d'équipe</td>
            <td class=\"tab_result\">".$tour->Parameters->draw_team." points</td>
            </tr>
            <tr>
            <td class=\"tab_result\">Défaite d'équipe</td>
            <td class=\"tab_result\">".$tour->Parameters->lost_team." points</td>
            </tr>";
            /*<tr>
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
            </tr>";*/
            } else {
                print "<tr>
            <td colspan =\"2\" rowspan=\"3\" class=\"tab_pos\">Système de points</td>
            <td class=\"tab_result\">Cumul des points des joueurs</td>
            </tr>
                <tr>
            <td class=\"tab_result\">Prime à la victoire d'équipe</td>
            <td class=\"tab_result\">".$tour->Parameters->team_victory_points." points</td>
            </tr>
            <tr>
            <td class=\"tab_result\">Prime au nul d'équipe</td>
            <td class=\"tab_result\">".$tour->Parameters->team_draw_points." points</td>
            </tr>";
            }
        }
        print"<td class=\"tab_pos\" rowspan=\"5\" colspan=\"2\">Systèmes de classements individuel</td>";
    } else {
        print"<td class=\"tab_pos\" rowspan=\"5\" colspan=\"2\">Systèmes de classements</td>";
    }
    $span=6;
    if ($tour->Parameters->use_large_victory)
            {
        $span=$span+1;
    }
    if ($tour->Parameters->use_little_loss)
            {
        $span=$span+1;
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
            <td class=\"tab_pos\" rowspan=\"".$span."\" colspan=\"2\">Valeur des points</td>
            </tr>";
            if ($tour->Parameters->use_large_victory)
            {
            print "<tr>
            <td class=\"tab_result\">Grande victoire (" . $tour->Parameters->large_victory_gap . " tds)</td>
            <td class=\"tab_result\">".$tour->Parameters->large_victory." points</td>
            </tr>";
            }
            print "<tr>
            <td class=\"tab_result\">Victoire</td>
            <td class=\"tab_result\">".$tour->Parameters->victory." points</td>
            </tr>
            <tr>
            <td class=\"tab_result\">Nul</td>
            <td class=\"tab_result\">".$tour->Parameters->draw." points</td>
            </tr>";
            
            if ($tour->Parameters->use_little_loss)
            {
            print "<tr><td class=\"tab_result\">Petite défaire (" . $tour->Parameters->little_loss_gap . " tds)</td>
            <td class=\"tab_result\">".$tour->Parameters->little_lost." points</td>
            </tr>";
            }
            print "<tr>
            <td class=\"tab_result\">Défaite</td>
            <td class=\"tab_result\">".$tour->Parameters->lost." points</td>
            </tr>";

            
            print "<tr>
            <td class=\"tab_result\">Abandon</td>
            <td class=\"tab_result\">".$tour->Parameters->conceeded." points</td>
            </tr>";
            
            print "<tr>
            <td class=\"tab_result\">Refus</td>
            <td class=\"tab_result\">".$tour->Parameters->refused." points</td>
            </tr>";
            
            //Add criterias display
            print "<tr><td class=\"tab_pos\" colspan=\"2\" rowspan=\"".(count($tour->Parameters->Criterias)+1)."\">Critères</td>";           
            print "</tr>";
            
            foreach($tour->Parameters->Criterias as $crit)
            {
                 print "<tr>
            <td class=\"tab_result\">$crit->Name</td>
            <td class=\"tab_result\">pour ".$crit->Points_For." points<br>
            contre ".$crit->Points_Against." points</td>";
                if ($tour->Parameters->byteam)
                {
                    print "<td class=\"tab_result\">pour (Equipe) ".$crit->Points_Team_For." points<br>
                            contre (Equipe)".$crit->Points_Team_Against." points</td>";
                }
            print "</tr>";
            }
            
            // Ajouter les bonus de tables
            if ($tour->Parameters->table_bonus)
            {
                 print "<tr>
            <td class=\"tab_pos\">Bonus de table</td>
            <td class=\"tab_result\">x ".$tour->Parameters->table_bonus_coeff."</td>
            </tr>";
            }
            
            if ($tour->Parameters->table_bonus_per_round)
            {
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

     
    generate_tour_menu($tour_id,$link);

    $tour = new tournament($tour_id,$link);
    //$rounds = $tour->global $db_host, $db_name, $db_passwd, $db_prefix, $db_user;

     
    //getRounds();

    $r = -1;

    if (isset($_GET['details'])) {
        details_html($tour,$link);
    }

    /*if (isset($_GET['round'])) {
        $r = $_GET['round'];
    }

    $round_max = round::C_UNIQUE;
    if (isset($_GET['total'])) {
        if ($_GET['total'] == 1) {
            $round_max = round::C_MAX;
        }
    }

    if ($_GET['rank'] == 'minus') {
        general_display($tour_id, $r, $round_max, 1);
    }
    if ($_GET['rank'] == 'general') {
        general_display($tour_id, $r, $round_max, 0);
    }
    if ($_GET['rank_team'] == 'general') {
        general_team_display($tour_id, $r, $round_max);
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
    }*/
    
    mysql_close($link);
}

?>
