<?php

require_once '../include/settings.php';
require_once 'lib/class_clan.php';
require_once 'lib/class_settings.php';
require_once 'lib/class_criteria.php';
require_once 'lib/class_tournament.php';
require_once 'lib/class_coach.php';
require_once 'lib/class_round.php';
require_once 'lib/class_match.php';
require_once 'lib/class_team.php';
require_once 'lib/class_group.php';
require_once 'lib/class_roster.php';
require_once 'lib/class_ranking.php';
require_once 'lib/class_position.php';


function upload_html() {
    echo "<br><br>";

    echo "<form enctype='multipart/form-data' action='index.php?upload=action' method='POST'>
                    <input type='hidden' name='MAX_FILE_SIZE' value='2560000' />
                    Envoyer le fichier: <input name='userfile' type='file' />
                    <input type='submit' value='Envoyer le fichier' />
                </form>";
}

// variable de la profondeur du parcours de l'arbre
$depth = array();
// Etat de la pile de parcours du document XML
$stack = array();
// Valeur d'un dernier élément lu
$globaldata = "";

$tournament_open = false;
$current_tournament = 0;
$coaches = array();
$current_round_number = 0;
$current_round = null;
$teid = -1;

function fill_database_from_xml(SimpleXMLElement $xml) {

    set_time_limit(0);
    foreach ($xml->children() as $element) {
        if ($element->getName() == "Parameters") {
            //print_r($element->attributes());
            $attr = $element->attributes();
            
            // Check that tournament does not exists
            $exists= tournament::exists($attr['Name']);

            if ($exists>0)
            {
                echo $attr['Name']." already exists<br>";
                exit($attr['Name']." already exists");
            }
            
            $tid = tournament::add($attr['Name'], $attr['Date'], $attr['Place']);

            //print_r($attr);

            $sid = settings::add($tid, $attr['Victory'], $attr['Large_Victory'], $attr['Draw'], $attr['Lost'], $attr['Little_Lost'], $attr['Refused'], $attr['Conceeded'], $attr['Victory_Team'], $attr['Draw_Team'], $attr['Lost_Team'], $attr['Large_Victory_Gap'], $attr['Little_Lost_Gap'], $attr['Rank1'], $attr['Rank2'], $attr['Rank3'], $attr['Rank4'], $attr['Rank5'], $attr['Rank1_Team'], $attr['Rank2_Team'], $attr['Rank3_Team'], $attr['Rank4_Team'], $attr['Rank5_Team'], $attr['ByTeam'], $attr['TeamMates'], $attr['TeamPairing'], $attr['TeamIndivPairing'], $attr['TeamVictoryPoints'], $attr['TeamDrawPoints'], $attr['TeamVictoryOnly'], $attr['GroupEnable'], $attr['Substitutes'], $attr['GameType'], $attr['ActvateClans'], $attr['AvoidFirstMatch'], $attr['AvoidMatch'], $attr['ClanTeammatesNumber'], $attr['MultiRoster'], $attr['IndivBalanced'], $attr['TeamBalanced'], $attr['UseLargeVictory'], $attr['UseLittleLost'], $attr['TableBonus'], $attr['TableBonusPerRound'], $attr['TableBonusCoef'], $attr['UseBestResultIndiv'], $attr['UseBestResultTeam'], $attr['BestResultIndiv'], $attr['BestReasultTeam'], $attr['ApplyToAnnexTeam'], $attr['ApplyToAnnexIndiv'], $attr['ExceptBestAndWorstIndiv'], $attr['ExceptBestAndWorstTeam']);

            foreach ($element->children() as $criteria) {
                $attr_c = $criteria->attributes();
                //print_r($attr_c);
                $cid = criteria::add($tid, $sid, $attr_c['Criteria'], $attr_c['PointsFor'], $attr_c['PointsAgainst'], $attr_c['PointsTeamFor'], $attr_c['PointsTeamAgainst']);
            }
        }

        if ($element->getName() == "Clan") {
            $attr = $element->attributes();
            $pic = '';
            foreach ($element->children() as $picture) {
                if ($picture->getName() == "Picture") {
                    $pic = $picture->__toString();
                }
            }
            clan::add($tid, $attr['Name'], $pic);
        }

        if ($element->getName() == "Group") {
            $attr = $element->attributes();
            $gid = group::add($tid, $attr['Name']);
            foreach ($element->children() as $roster) {
                if ($roster->getName() == "Roster") {
                    $attr_r = $roster->attributes();
                    $rid = roster::add($tid, $gid, $attr_r['Name']);
                }
            }
        }


        if ($element->getName() == "Coach") {
            $attr = $element->attributes();
            $pic = '';
            foreach ($element->children() as $picture) {
                if ($picture->getName() == "Picture") {
                    $pic = $picture->__toString();
                }
            }
            coach::add($tid, $attr['Name'], $attr['Team'], $attr['Roster'], $attr['NAF'], $attr['Rank'], $attr['Handicap'], $pic, $attr['Clan'], $attr['TeamMates']);
        }

        if ($element->getName() == "Team") {
            $attr = $element->attributes();
            $pic = '';
            foreach ($element->children() as $picture) {
                if ($picture->getName() == "Picture") {
                    $pic = $picture->__toString();
                }
            }
            $team_id = Team::add($tid, $attr['Name'], $pic, $attr['Clan']);

            foreach ($element->children() as $coach) {
                if ($coach->getName() == "Coach") {
                    $attr_c = $coach->attributes();
                    coach::set_team_id($attr_c['Name'], $tid, $team_id);
                }
            }
        }

        if ($element->getName() == "Round") {
            $attr = $element->attributes();
            $rid = round::add($tid, $attr['Date']);

            foreach ($element->children() as $child) {

                // Detects if CoachMatch or TeamMatch
                $attr_m = $child->attributes();

                if ($child->getName() == "Match") {
                    $match = $child;
                    /*                print_r($match);
                      echo "<br>";
                      print_r($attr_m);
                      echo "<br>"; */

                    $is_team_match = 0;

                    foreach ($attr_m as $a => $b) {
                        if ($a == 'Team1') {
                            $is_team_match = 1;
                            break;
                        }
                    }

                    if ($is_team_match == 1) {
                        // Team Match   
                        if ($match->getName() == 'Match') {
                            $tmid = match::addTeamMatch($tid, $rid, $attr_m['Team1'], $attr_m['Team2']);
                            foreach ($match->children() as $coachmatch) {
                                $attr_cm = $coachmatch->attributes();
                                // Insert Match
                                $mid = match::addCoachMatch($tid, $rid, $attr_cm['Coach1'], $attr_cm['Coach2'], $attr_cm['RefusedBy1'], $attr_cm['RefusedBy2'], $attr_cm['ConceededBy1'], $attr_cm['ConceededBy2'], $tmid);

                                //print_r($match);
                                // Insert values
                                foreach ($coachmatch->children() as $value) {
                                    $attr_v = $value->attributes();
                                    //print_r($value);
                                    $value = match::addValue($mid, $attr_v['Name'], $attr_v['Value1'], $attr_v['Value2']);
                                }
                            }
                        }
                    } else {
                        // Coach Match
                        if ($match->getName() == 'Match') {
                            //print_r($attr_m);
                            // Insert Match
                            $mid = match::addCoachMatch($tid, $rid, $attr_m['Coach1'], $attr_m['Coach2'], $attr_m['RefusedBy1'], $attr_m['RefusedBy2'], $attr_m['ConceededBy1'], $attr_m['ConceededBy2'], '');

                            //print_r($match);
                            // Insert values
                            foreach ($match->children() as $value) {
                                $attr_v = $value->attributes();
                                //print_r($value);
                                $value = match::addValue($mid, $attr_v['Name'], $attr_v['Value1'], $attr_v['Value2']);
                            }
                        }
                    }
                }

                if ($child->getName() == "Ranking") {
                    $ranking = $child;
                    
                    $attr_ranking=$ranking->attributes();
                    
                    //print_r($attr_ranking);
                    
                    $type='';
                    $subtype='';
                    $posneg=0;
                    $criteria='';
                    switch($attr_ranking['Name'])
                    {
                        case 'INDIVIDUAL':
                            $type='INDIVIDUAL';
                            break;
                        case 'TEAM':
                            $type='TEAM';
                            break;
                        case 'CLAN':
                            $type='CLAN';
                            break;
                        case 'GROUP':
                            $type='GROUP';
                            break;
                    }
                    
                    switch($attr_ranking['Type'])
                    {
                        case 'General':
                            $subtype='GENERAL';
                            break;
                        default:
                            $subtype='CRITERIA';
                            $criteria=$attr_ranking['Type'];
                            $posneg=($attr_ranking['Order']=='Positive');
                    }
                    $rankings=0;
                    
                    ranking::add($tid,$rid,$attr_ranking['Type'],$type,$subtype,$rankings,$criteria,$posneg);
                    
                    $positions=$ranking->children();
                    foreach ($ranking->children() as $position) {
                        $attr_rp=$position->attributes();
                        //print_r($position);
                    }
                    
                }
            }
        }
    }
}

/*
  // Fonction associée à l’événement début d’élément
  function startElement($parser, $name, $attrs) {
  global $depth;
  global $stack;
  global $current_tournament;
  global $coaches;
  global $current_round_number;
  global $current_round;
  global $teid;

  for ($i = 0; $i < $depth[$parser]; $i++) {print " ";}
  array_push($stack,$name);

  $depth[$parser]++;

  if ($name=='TOURNAMENT') {
  $tournament_open=true;
  }
  if ($name=='PARAMETERS') {
  $id=tournament::add($attrs['NAME'],$attrs['ORGANIZER'],$attrs['DATE'],$attrs['PLACE'],
  $attrs['LARGE_VICTORY'],$attrs['VICTORY'],$attrs['DRAW'],$attrs['LITTLE_LOST'],$attrs['LOST'],
  $attrs['RANK1'],$attrs['RANK2'],$attrs['RANK3'],$attrs['RANK4'],$attrs['RANK5'],
  $attrs['BONUS_POS_TD'],$attrs['BONUS_NEG_TD'],$attrs['BONUS_POS_FOUL'],$attrs['BONUS_NEG_FOUL'], $attrs['BONUS_POS_SOR'],$attrs['BONUS_NEG_SOR'],
  $attrs['VICTORY_TEAM'],$attrs['DRAW_TEAM'],$attrs['LOST_TEAM'],
  $attrs['RANK1_TEAM'],$attrs['RANK2_TEAM'],$attrs['RANK3_TEAM'],$attrs['RANK4_TEAM'],$attrs['RANK5_TEAM'],
  $attrs['BONUS_POS_TD_TEAM'],$attrs['BONUS_NEG_TD_TEAM'],$attrs['BONUS_POS_FOUL_TEAM'],$attrs['BONUS_NEG_FOUL_TEAM'], $attrs['BONUS_POS_SOR_TEAM'],$attrs['BONUS_NEG_SOR_TEAM'],
  $attrs['BYTEAM']=='true',
  $attrs['TEAMMATES'],
  $attrs['TEAMPAIRING'],
  $attrs['TEAMINDIVPAIRING'],
  $attrs['TEAMVICTORYPOINTS'],
  $attrs['TEAMVICTORYONLY']=='true');

  if ($id>=0) {
  $current_tournament=new tournament($id);
  print "PARAMETERS: Upload complete - ".$attrs['NAME']."<br>";
  }
  else {
  $current_tournament=null;
  }
  }

  if ($name=='TEAM') {
  if ($current_tournament!=null) {
  $teid=team::add($attrs['NAME'],$current_tournament->tid);
  $teams[$attrs['NAME']]=$teid;
  if ($teid>=0) {
  print "TEAM: Upload complete - ".$attrs['NAME']."<br>";
  }
  }
  }

  if ($name=='COACH') {
  if ($current_tournament!=null) {
  if ($teid>=0) {
  coach::affect_team($attrs['NAME'],$current_tournament->tid,$teid);
  }
  else {
  $cid=coach::add($attrs['NAME'],$current_tournament->tid, $attrs['TEAM'],$attrs['ROSTER'],$attrs['NAF'],$attrs['RANK']);
  $coaches[$attrs['NAME']]=$cid;
  if ($cid>=0) {
  print "COACH: Upload complete - ".$attrs['NAME']."<br>";
  }
  }

  }
  }

  if ($name=='ROUND') {
  if ($current_tournament!=null) {
  $current_round_number++;
  $rid=round::add($attrs['DATE'],$current_tournament->tid,'',$current_round_number);
  if ($rid>=0) {
  $current_round=new round($rid);
  print "ROUND: Upload complete - $current_round_number<br>";
  }
  else {
  $current_round=null;
  }
  }
  }

  if ($name=='MATCH') {
  if ($current_round!=null) {
  $mid=match::add($current_round->rid,
  $coaches[$attrs['COACH1']],$coaches[$attrs['COACH2']],
  $attrs['TD1'],$attrs['TD2'],
  $attrs['SOR1'],$attrs['SOR2'],
  $attrs['FOUL1'],$attrs['FOUL2']);
  if ($mid>=0) {
  print "MATCH: Upload complete - round ".$current_round_number." ".$attrs['COACH1']." vs ".$attrs['COACH2']."<br>";
  }
  }
  }
  //affichage des attributs de l'élément

  }

  // Fonction associée à l’événement fin d’élément
  function endElement($parser, $name) {

  global $depth;
  global $stack;
  global $globaldata;
  global $current_tournament;
  global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;
  global $teid;

  if ($name=='TOURNAMENT') {
  $tournament_open=false;

  $link = mysql_connect($db_host, $db_user, $db_passwd)  or die("Impossible de se connecter : " . mysql_error());
  mysql_select_db($db_name,$link);
  $query="SELECT date from ".$db_prefix."round where f_tid=$current_tournament->tid order by date";
  $result=mysql_query($query);
  $r = mysql_fetch_assoc($result);
  $query="UPDATE `".$db_prefix."tournament` SET `date` = '".$r['date']."' WHERE `tid` =$current_tournament->tid ";
  $result=mysql_query($query);

  print "DONE.";
  }

  if ($name=='TEAM') {
  $teid=-1;
  }

  $depth[$parser]--;

  array_pop($stack);

  }

  // Fonction associée à l’événement données textuelles
  function characterData($parser, $data) {
  global $globaldata;
  $globaldata = $data;
  }

  // Fonction associée à l’événement de détection d'un appel d'entité externe
  function externalEntityRefHandler($parser,
  $openEntityNames,
  $base,
  $systemId,
  $publicId) {

  if ($systemId) { if (!list($parser, $fp) = new_xml_parser($systemId)) {

  printf("Impossible d'ouvrir %s à %s\n",
  $openEntityNames,
  $systemId);
  return FALSE;

  }

  while ($data = fread($fp, 4096)) {

  if (!xml_parse($parser, $data, feof($fp))) {

  printf("Erreur XML : %s à la ligne %d lors du traitement de l'entité %s\n",
  xml_error_string(xml_get_error_code($parser)),
  xml_get_current_line_number($parser),
  $openEntityNames);

  xml_parser_free($parser);

  return FALSE;

  }

  }

  xml_parser_free($parser);

  return TRUE; } return FALSE;

  }

  // Fonction de création du parser et d'affectation
  // des fonctions aux gestionnaires d'événements
  function new_xml_parser($file) {

  global $parser_file;
  //création du parseur
  $xml_parser = xml_parser_create();
  //Activation du respect de la casse du nom des éléments XML
  xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 1);
  //Déclaration des fonctions à rattacher au gestionnaire d'événement
  xml_set_element_handler($xml_parser, "startElement", "endElement");
  xml_set_character_data_handler($xml_parser, "characterData");
  xml_set_external_entity_ref_handler($xml_parser, "externalEntityRefHandler");

  //Ouverture du fichier
  if (!($fp = @fopen($file, "r")))
  {
  echo "fichier non trouvé";
  return FALSE;
  }



  //Transformation du parseur en un tableau
  if (!is_array($parser_file))
  {
  settype($parser_file, "array");
  }
  $parser_file[$xml_parser] = $file;

  return array($xml_parser, $fp);

  } */

function upload_file($filename) {
    $status = true;

    $savepath = $_SERVER["DOCUMENT_ROOT"] . '/tmp/';
    if ($filename['type'] == "text/xml") {

        // Appel à la fonction de création et d'initialisation du parseur
        //if (!(list($xml_parser, $fp) = new_xml_parser($filename['tmp_name']))) { die("Impossible d'ouvrir le document XML"); }

        if (file_exists($filename['tmp_name'])) {
            $xml = simplexml_load_file($filename['tmp_name']);
            //print_r($xml);

            fill_database_from_xml($xml);
        } else {
            exit('Echec lors de l\'ouverture du fichier XML.');
        }

        // Traitement de la ressource XML
        /* while ($data = fread($fp, 4096)) {

          if (!xml_parse($xml_parser, $data, feof($fp))) { die(sprintf("Erreur XML : %s à la ligne %d\n",
          xml_error_string(xml_get_error_code($xml_parser)),
          xml_get_current_line_number($xml_parser)));
          }
          }

          // Libération de la ressource associée au parser
          xml_parser_free($xml_parser); */
    }

    echo "<br><br>";

    echo "<form enctype='multipart/form-data' action='index.php' method='POST'>
                    <input type='submit' value='Retour' />
                </form>";
}

?>