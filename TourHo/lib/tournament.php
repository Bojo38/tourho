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

function tournament_html($tour_id) {

    generate_tour_menu($tour_id);

    $tour=new tournament($tour_id);
    $rounds=$tour->getRounds();

    if ($_GET['rank'] == 'general') {
        if (isset($_GET['round'])) {
            general_display($tour_id,count($rounds));
        }
        else {
            general_display($tour_id,$_GET['round']);
        }
    }
    if ($_GET['rank'] == 'td_pos') {
        if (isset($_GET['round'])) {
            td_pos_display($tour_id,count($rounds));
        }
        else {
            td_pos_display($tour_id,$_GET['round']);
        }
    }
    if ($_GET['rank'] == 'td_neg') {
        if (isset($_GET['round'])) {
            td_neg_display($tour_id,count($rounds));
        }
        else {
            td_neg_display($tour_id,$_GET['round']);
        }
    }
    if ($_GET['rank'] == 'cas_pos') {
        if (isset($_GET['round'])) {
            cas_pos_display($tour_id,count($rounds));
        }
        else {
            cas_pos_display($tour_id,$_GET['round']);
        }
    }
    if ($_GET['rank'] == 'cas_neg') {
        if (isset($_GET['round'])) {
            cas_neg_display($tour_id,count($rounds));
        }
        else {
            cas_neg_display($tour_id,$_GET['round']);
        }
    }
    if ($_GET['rank'] == 'foul_pos') {
        if (isset($_GET['round'])) {
            foul_pos_display($tour_id,count($rounds));
        }
        else {
            foul_pos_display($tour_id,$_GET['round']);
        }
    }
    if ($_GET['rank'] == 'foul_neg') {
        if (isset($_GET['round'])) {
            foul_neg_display($tour_id,count($rounds));
        }
        else {
            foul_neg_display($tour_id,$_GET['round']);
        }
    }

    if ($_GET['rank'] == 'matchs') {
        if (isset($_GET['round'])) {
            match_display($_GET['round']);
        }
    }

}
?>
