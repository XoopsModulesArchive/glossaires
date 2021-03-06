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
require XOOPS_ROOT_PATH . '/modules/glossaires/function.php';

xoops_cp_header();
$myts = MyTextSanitizer::getInstance();

echo '<B>' . _ADMIN . ' ' . $xoopsConfig['sitename'] . '</B><p>';

echo '<CENTER>[ ' . _ADMIN2 . ' | <A HREF="../index.php">' . _SEELIST . '</A> | <A HREF="mod-cat.php">' . _MOVALCAT . '</A> | <A HREF="ajout-def.php">' . _ADDDEF . '</A> | <A HREF="pref.php">' . _CONFGLO . '</A> ]</CENTER>';

echo '<P><BR><B>' . _WAITDEFVALID . '</B><BR>';

$propodef = $xoopsDB->query('SELECT id, idcat, lettre, nom FROM ' . $xoopsDB->prefix('glossaires') . " WHERE affiche='N' order by nom");
$propo = $xoopsDB->getRowsNum($propodef);

if (0 == $propo) {
    echo '' . _NODEFWAIT . '<P>';
} else {
    echo '' . _THEREIS . " <FONT COLOR=\"#FF0000\">$propo</FONT> " . _WAITDEF . '<p>';

    echo '<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=1>
    <TR>
      <TD WIDTH=20><CENTER><B>' . _NUM . '</B></CENTER></TD>
      <TD WIDTH=20><CENTER><B>' . _CAT2 . '</B></CENTER></TD>
      <TD WIDTH=20><CENTER><B>' . _LETTRE2 . '</B></CENTER></TD>
      <TD WIDTH=150><B>' . _TERME4 . '</B></TD>
      <TD><CENTER><B>' . _OPTION . '</B></CENTER></TD>
    </TR>';

    while (list($texte_id, $texte_idcat, $lettre, $texte_nom) = $xoopsDB->fetchRow($propodef)) {
        $texte_nom = $myts->displayTarea($texte_nom);

        $result_cat = $xoopsDB->query('SELECT idcat, nomcat FROM ' . $xoopsDB->prefix('glossaires_cat') . " WHERE idcat=$texte_idcat");

        [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

        echo "<tr><td><CENTER>$texte_id</CENTER></td><td><CENTER>$nomcat</CENTER></td><td><CENTER>$lettre</CENTER></td><td>&nbsp;&nbsp;$texte_nom</td><td>&nbsp;&nbsp;[ <A HREF=\"mod-def.php?pa=modif&id=" . $texte_id . '">' . _MODVAL . '</A> | <A HREF="suppr-def.php?id=' . $texte_id . '">' . _SUPPR . '</A> ]&nbsp;&nbsp;</td></tr>';
    }

    echo '</TABLE><P><BR>';
}

echo '<P><BR><B>' . _ASKWAIT . '</B><BR>';

    $propodef = $xoopsDB->query('SELECT id, idcat, nom FROM ' . $xoopsDB->prefix('glossaires') . " WHERE affiche='D' order by nom");
    $propo = $xoopsDB->getRowsNum($propodef);

if (0 == $propo) {
    echo '' . _ASKDEFWAIT . '<P>';
} else {
    echo '' . _THEREIS . " <FONT COLOR=\"#FF0000\">$propo</FONT> " . _DEFREQ . '<p>';

    echo '<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=1>
    <TR>
      <TD WIDTH=20><CENTER><B>' . _NUM . '</B></CENTER></TD>
      <TD WIDTH=20><CENTER><B>' . _CAT2 . '</B></CENTER></TD>
      <TD WIDTH=150><B>' . _TERME4 . '</B></TD>
      <TD><CENTER><B>' . _OPTION . '</B></CENTER></TD>
    </TR>';

    while (list($texte_id, $texte_idcat, $texte_nom) = $xoopsDB->fetchRow($propodef)) {
        $texte_nom = $myts->displayTarea($texte_nom);

        echo "<tr><td><CENTER>$texte_id</CENTER></td><td><CENTER>$texte_idcat</CENTER></td><td>&nbsp;&nbsp;$texte_nom</td><td>&nbsp;&nbsp;[ <A HREF=\"mod-def.php?pa=modif&id=" . $texte_id . '">' . _MODVAL . '</A> | <A HREF="suppr-def.php?id=' . $texte_id . '">' . _SUPPR . '</A> ]&nbsp;&nbsp;</td></tr>';
    }

    echo '</TABLE><P><BR>';
}

if (1 == $mode) {
    echo '<P><BR><B>' . _COMWAIT . '</B><BR>';

    $commdef = $xoopsDB->query('SELECT id, def, auteur FROM ' . $xoopsDB->prefix('glossaires_comm') . " WHERE affiche='M' order by date");

    $comm = $xoopsDB->getRowsNum($commdef);

    if (0 == $comm) {
        echo '' . _NOCOMWAIT . '<P>';
    } else {
        echo '' . _THEREIS . " <FONT COLOR=\"#FF0000\">$comm</FONT> " . _WAITCOM . '<p>';

        echo '<TABLE BORDER=1 CELLPADDING=2 CELLSPACING=1>
    <TR>
      <TD><CENTER><B>' . _TERME4 . '</B></CENTER></TD>
      <TD WIDTH=150><B>' . _AUTOR . '</B></TD>
      <TD><CENTER><B>' . _OPTION . '</B></CENTER></TD>
    </TR>';

        while (list($texte_sid, $texte_def, $texte_auteur) = $xoopsDB->fetchRow($commdef)) {
            $texte_def = $myts->displayTarea($texte_def);

            $rechdef = $xoopsDB->query('SELECT nom FROM ' . $xoopsDB->prefix('glossaires') . " WHERE id='$texte_def'");

            [$texte_nom] = $xoopsDB->fetchRow($rechdef);

            $texte_nom = $myts->displayTarea($texte_nom);

            echo "<tr><td><CENTER>$texte_nom</CENTER></td><td>&nbsp;&nbsp;$texte_auteur </td><td>&nbsp;&nbsp;[ <A HREF=\"mod-comm.php?pa=modif&id=" . $texte_sid . '">' . _MODVAL . '</A> | <A HREF="mod-comm.php?pa=supprcomm&id=' . $texte_sid . '">' . _SUPPR . '</A> ]&nbsp;&nbsp;</td></tr>';
        }

        echo '</TABLE><P><BR>';
    }
}
// CloseTable();
xoops_cp_footer();
