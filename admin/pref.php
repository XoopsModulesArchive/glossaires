<?php

#####################################################
#	Glossaires version 2.0 pour xoops 2.X
#	Copyright 2003, Martial Le Peillet
#	webmaster@toplenet.com - http://www.toplenet.com
#
#	Module Original :
#	Glossaire version 1.6 pour Xoops 1.0 RC3
#	Copyright © 2002, Pascal Le Boustouller
#
#  Licence : GPL
#  Merci de laisser ce copyright en place...
#####################################################
include 'admin_header.php';
    require XOOPS_ROOT_PATH . '/modules/glossaires/glossaires-config.php';

function Conf()
{
    global $xoopsUser, $xoopsConfig, $xoopsModule, $nb_affichage, $nouvdef, $anocomm, $mode, $mess;

    xoops_cp_header();

    // OpenTable();

    echo '<B>' . _ADMIN . ' ' . $xoopsConfig['sitename'] . '</B><p>';

    echo '<br><CENTER>[ <A HREF="index.php">' . _ADMIN2 . ' ]</A></CENTER>';

    echo '<br><B>' . _TITRECONF . '</B>';

    echo '<FORM ACTION="pref.php?pa=ConfOk" METHOD=POST>
<TABLE BORDER=0>
    <TR>
      <TD>' . _DEFPAGE . "</TD>
      <TD><INPUT TYPE=\"text\" NAME=\"f_nb_affichage\" SIZE=10 value=\"$nb_affichage\"></TD>
    </TR>
    <TR>
      <TD>" . _ANNOADDDEF . '</TD>
      <TD><SELECT NAME="f_nouvdef">';

    if (1 == $nouvdef) {
        echo '<OPTION VALUE="1" selected>  ' . _OUI . '';

        echo '<OPTION VALUE="0">  ' . _NON . '';
    } else {
        echo '<OPTION VALUE="1"> ' . _OUI . '';

        echo '<OPTION VALUE="0" selected>  ' . _NON . '';
    }

    echo ' </SELECT></TD>
    </TR>
    <TR>
      <TD>' . _ANNOADDCOMM . '</TD>
      <TD><SELECT NAME="f_anocomm">';

    if (1 == $anocomm) {
        echo '<OPTION VALUE="1" selected> ' . _OUI . '';

        echo '<OPTION VALUE="0"> ' . _NON . '';
    } else {
        echo '<OPTION VALUE="1"> ' . _OUI . '';

        echo '<OPTION VALUE="0" selected>  ' . _NON . '';
    }

    echo '</SELECT></TD>
    </TR>
    <TR>
      <TD>' . _MODECOMM . '</TD>
      <TD><SELECT NAME="f_mode">';

    if (1 == $mode) {
        echo '<OPTION VALUE="1" selected> ' . _OUI . '';

        echo '<OPTION VALUE="0">  ' . _NON . '';
    } else {
        echo '<OPTION VALUE="1"> ' . _OUI . '';

        echo '<OPTION VALUE="0" selected>  ' . _NON . '';
    }

    echo '</SELECT></TD>
    </TR>
    <TR>
      <TD>' . _MAILWEBM . '</TD>
      <TD><SELECT NAME="f_mess">';

    if (1 == $mess) {
        echo '<OPTION VALUE="1" selected> ' . _OUI . '';

        echo '<OPTION VALUE="0">  ' . _NON . '';
    } else {
        echo '<OPTION VALUE="1"> ' . _OUI . '';

        echo '<OPTION VALUE="0" selected>  ' . _NON . '';
    }

    echo '</SELECT></TD>
    </TR>
</TABLE><P>
<INPUT TYPE="submit" VALUE="' . _VALID . '">
</FORM>';

    // CloseTable();

    xoops_cp_footer();
}

function ConfOK($f_nb_affichage, $f_nouvdef, $f_anocomm, $_fmode, $f_mess)
{
    global $xoopsUser;

    $file = fopen(XOOPS_ROOT_PATH . '/modules/glossaires/glossaires-config.php', 'wb');

    $content = "<?\n";

    $content .= "#####################################################\n";

    $content .= "#	Glossaires version 2.0 pour xoops 2.X\n";

    $content .= "#	Copyright 2003, Martial Le Peillet\n";

    $content .= "#	webmaster@toplenet.com - http://www.toplenet.com\n";

    $content .= "#\n";

    $content .= "#  Glossaire version 1.6 pour Xoops 1.0 RC3\n";

    $content .= "#  Copyright © 2002, Pascal Le Boustouller\n";

    $content .= "#  Licence : GPL\n";

    $content .= "#\n";

    $content .= "#  Merci de laisser ce copyright en place...\n";

    $content .= "#####################################################\n\n";

    $content .= "# Definition per page\n";

    $content .= "# Définitions par page\n";

    $content .= "\$nb_affichage = $f_nb_affichage;\n\n";

    $content .= "# Anonymous can add definitions : yes=1 no=0\n";

    $content .= "# Les anonymes peuvent proposer des définitions : oui=1 non=0\n";

    $content .= "\$nouvdef = $f_nouvdef;\n\n";

    $content .= "# Anonymous can add comments : yes=1 no=0\n";

    $content .= "# Les anonymes peuvent proposer des commentaires : oui=1 non=0\n";

    $content .= "\$anocomm = $f_anocomm;\n\n";

    $content .= "# Moderate comments : yes=1 no=0\n";

    $content .= "# Modération des commentaires : oui=1 non=0\n";

    $content .= "\$mode = $_fmode;\n\n";

    $content .= "# Send an email to the Webmaster when definition or comment is added : yes=1 no=0\n";

    $content .= "# Envoi d'un mail au webmaster quand une définition ou un commentaire est ajouté : oui=1 non=0\n";

    $content .= "\$mess = $f_mess;\n";

    $content .= '?>';

    fwrite($file, $content);

    fclose($file);

    redirect_header('index.php', 1, _OKCONFIG);

    exit();
}

switch ($pa) {
    case 'ConfOk':
        ConfOK($f_nb_affichage, $f_nouvdef, $f_anocomm, $f_mode, $f_mess);
        break;
    default:
        Conf();
        break;
}
