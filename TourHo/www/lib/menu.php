<?php
require_once 'lib/class_tournament.php';
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function generate_menu() {
    $years=tournament::getYearList();
    echo "<div class=\"menu\">
            <ul id=\"nav\" class=\"dropdown dropdown-horizontal\">
                <li><a href=\"index.php?upload=html\">Charger un tournoi....</a></li>";
    foreach ($years as $key => $value) {
        print "<li><span class=\"dir\">Ann&eacute;e $key</span>";
        $tours=tournament::getToursByYear($key);
        print "<ul>";
        foreach ($tours as $keyt => $valuet) {
            print '<li><a href="index.php?tournament='.$valuet.'">'.$keyt.'</a></li>';
        }
        print"</ul>";
        print"</li>";
    }
    echo "</ul>
        </div>";
}
?>
