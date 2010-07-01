<?php
require_once 'settings.php';
require_once 'lib/class_tournament.php';
require_once 'lib/class_coach.php';
require_once 'lib/class_round.php';
require_once 'lib/class_match.php';

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
$globaldata ="";

$tournament_open=false;
$current_tournament=0;
$coaches=array();
$current_round_number=0;
$current_round=null;

// Fonction associée à l’événement début d’élément
function startElement($parser, $name, $attrs) {
    global $depth;
    global $stack;
    global $current_tournament;
    global $coaches;
    global $current_round_number;
    global $current_round;

    for ($i = 0; $i < $depth[$parser]; $i++) {print " ";}
    array_push($stack,$name);

    $depth[$parser]++;

    if ($name=='TOURNAMENT') {
        $tournament_open=true;
    }
    if ($name=='PARAMETERS') {
        $id=tournament::add($attrs['NAME'],$attrs['ORGANIZER'],'','',
            $attrs['LARGE_VICTORY'],$attrs['VICTORY'],$attrs['DRAW'],$attrs['LITTLE_LOST'],$attrs['LOST'],
            $attrs['RANK1'],$attrs['RANK2'],$attrs['RANK3'],$attrs['RANK4'],$attrs['RANK5'],
            $attrs['BONUS_POS_TD'],$attrs['BONUS_NEG_TD'],$attrs['BONUS_POS_FOUL'],$attrs['BONUS_NEG_FOUL'],$attrs['BONUS_POS_SOR'],$attrs['BONUS_NEG_SOR']);

        if ($id>=0) {
            $current_tournament=new tournament($id);
            print "PARAMETERS: Upload complete - ".$attrs['NAME']."<br>";
        }
        else {
            $current_tournament=null;
        }
    }
    if ($name=='COACH') {
        if ($current_tournament!=null) {
            $cid=coach::add($attrs['NAME'],$current_tournament->tid, $attrs['TEAM'],$attrs['ROSTER'],$attrs['NAF'],$attrs['RANK']);
            $coaches[$attrs['NAME']]=$cid;
            if ($cid>=0) {
                print "COACH: Upload complete - ".$attrs['NAME']."<br>";
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

   /* print "Début de l'élément : ".$name."\n -- ";
    print "profondeur : ".$depth[$parser]." -- Attributs de l'élément : ";
*/
//affichage des attributs de l'élément
  /*  while (list ($key, $val) = each ($attrs)) {echo "$key => $val";}
    print " ";*/    

}

// Fonction associée à l’événement fin d’élément
function endElement($parser, $name) {

    global $depth;
    global $stack;
    global $globaldata;
    global $current_tournament;
    global $db_host,$db_name,$db_passwd,$db_prefix,$db_user;

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
   /* for ($i = 0; $i < $depth[$parser]-1; $i++) {print " ";}
    print "Fin de l'élément : ".$name." avec la valeur :".$globaldata." -- ";

    print "profondeur : ".$depth[$parser]." ";*/

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
    if (!($fp = @fopen($file, "r"))) { echo "fichier non trouvé";return FALSE; }
    //Transformation du parseur en un tableau
    if (!is_array($parser_file)) { settype($parser_file, "array"); }
    $parser_file[$xml_parser] = $file;

    return array($xml_parser, $fp);

}


function upload_file($filename) {
    $status = true;

    $savepath=$_SERVER["DOCUMENT_ROOT"].'/tourho/tmp/';
    if  ($filename['type']=="text/xml") {

    // Appel à la fonction de création et d'initialisation du parseur
        if (!(list($xml_parser, $fp) = new_xml_parser($filename['tmp_name']))) { die("Impossible d'ouvrir le document XML"); }
        // Traitement de la ressource XML
        while ($data = fread($fp, 4096)) {

            if (!xml_parse($xml_parser, $data, feof($fp))) { die(sprintf("Erreur XML : %s à la ligne %d\n",
                    xml_error_string(xml_get_error_code($xml_parser)),
                    xml_get_current_line_number($xml_parser)));
            }
        }

        // Libération de la ressource associée au parser
        xml_parser_free($xml_parser);
    }

}
?>