<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
require 'lib/menu.php';
require 'lib/upload.php';
require 'lib/tournament.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="js/lib.js" type="text/javascript"></script>
        <link rel="stylesheet" href="css/lib.css" type="text/css">
        <title>R&eacute;sultats de tournois</title>
    </head>
    <body>        
        <div id="titre">R&eacute;sultats de tournois</div><br>
        <?php
        generate_menu();
        if (isset($_GET['upload'])) {
            if ($_GET['upload'] == 'html') {
                upload_html();
            }
            if ($_GET['upload'] == 'action') {
                upload_file($_FILES['userfile']);
            }
        }
        if (isset($_GET['tournament'])) {
            tournament_html($_GET['tournament']);
        }
        ?>

    </body>
</html>
