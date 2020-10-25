<?php

#####################################################
#	Glossaires version 2.0 pour xoops 2.X
#	Copyright 2003, Martial Le Peillet
#	webmaster@toplenet.com - http://www.toplenet.com
#
#  Licence : GPL
#  Merci de laisser ce copyright en place...
#####################################################

include 'admin_header.php';
    require XOOPS_ROOT_PATH . '/modules/glossaires/glossaires-config.php';
    require XOOPS_ROOT_PATH . '/modules/glossaires/function.php';

function AjCat($nomcat)
{
    global $xoopsConfig, $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $nomcat = $myts->addSlashes($nomcat);

    $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('glossaires_cat') . " VALUES ('', '$nomcat')");

    redirect_header('index.php', 1, _ENRDEF);
}

function FormCat()
{
    global $xoopsConfig, $xoopsModule, $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    xoops_cp_header();

    echo '<B>' . _ADMINCAT . ' ' . $xoopsConfig['sitename'] . '</B><p>';

    echo '<CENTER>[ <A HREF="index.php">' . _ADMIN2 . '</A> | <A HREF="mod-cat.php">' . _MOVALCAT . '</A> ]</CENTER>';

    echo '<br><B>' . _ADDCAT . '</B>';

    $glossaires_cat = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('glossaires_cat') . '');

    $NombreEntrees = $xoopsDB->getRowsNum($glossaires_cat);

    if (0 == $NombreEntrees) {
        echo '<br>' . _NOCAT . '';
    } else {
        echo '<br>' . _LSTCAT . '<br>';
    }

    //echo "<table><tr><td>";

    $result = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('glossaires_cat') . ' ');

    while (list($idcat, $nomcat) = $xoopsDB->fetchRow($result)) {
        $idcat = $myts->displayTarea($idcat);

        $nomcat = $myts->displayTarea($nomcat);

        echo '<br>';

        echo '' . $idcat . ' : ' . $nomcat . '';
    }

    //echo "</td></tr></table>

    echo "<FORM ACTION='ajout-cat.php?pa=AjCat' METHOD=POST>
<INPUT TYPE=\"hidden\" NAME=\"affiche\" VALUE=\"O\">
<TABLE BORDER=0 CELLPADDING=5>
    <TR>
      <TD ALIGN=\"LEFT\">" . _NOMCAT . " </TD>
      <TD><INPUT TYPE='text' NAME='nomcat' SIZE=50></TD>
    </TR>
    <TR>
      <TD ALIGN=\"CENTER\"><CENTER><INPUT TYPE='submit' VALUE='" . _ADD . "'></CENTER></TD>
    </TR>
</TABLE>
</FORM>";

    xoops_cp_footer();
}

switch ($pa) {
    case 'AjCat':
    AjCat($nomcat);
    break;
    default:
    FormCat();
    break;
}
