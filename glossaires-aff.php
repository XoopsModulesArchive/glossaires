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
include 'header.php';
require XOOPS_ROOT_PATH . '/modules/glossaires/glossaires-config.php';
require XOOPS_ROOT_PATH . '/modules/glossaires/function.php';
require XOOPS_ROOT_PATH . '/header.php';
$myts = MyTextSanitizer::getInstance();

if (isset($_GET['debut'])) {
    $debut = (int)$_GET['debut'];
} else {
    $debut = '';
}

if ('' == $debut) {
    $debut = 0;
}
if ($nom) {
    [$nbe] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('glossaires') . " WHERE affiche='O' AND nom='$nom' ORDER BY nom"));
} else {
    [$nbe] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('glossaires') . " WHERE affiche='O' AND lettre='$lettre' ORDER BY nom"));
}
echo '<center><h2><B>' . _GLOSSAIRE . "</B></h2></center>\n";

if ($nom) {
    $TableRep = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('glossaires') . " WHERE affiche='O' AND nom='$nom'");
} else {
    $TableRep = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('glossaires') . " WHERE affiche='O' AND lettre='$lettre' ORDER BY nom LIMIT $debut,$nb_affichage");
}
$top = 1;
$topsuivant = "glossaires-aff.php?rechercher&lettre=$lettre";
$NombreEntrees = $xoopsDB->getRowsNum($TableRep);

if ('autre' == $lettre) {
    $lettre = _OTHERS;
}

if (0 == $NombreEntrees) {
    echo '<center>' . _NONEDEF . " <FONT COLOR=\"#FF0000\">$lettre</FONT></center>";
} else {
    MenuGlo();

    if (1 == $top) {
        LettreGlo();

        if ($nom) {
            echo '<P><font color="#FF0000"> ' . $nbe . '</font> ' . _DEFFOR . "  <font color=\"#FF0000\"><b>$nom</b></font><p>";
        } else {
            echo '<P><font color="#FF0000"> ' . $nbe . '</font> ' . _DEFFOR . "  <font color=\"#FF0000\"><b>$lettre</b></font><p>";
        }

        while (list($glo_id, $glo_idcat, $glo_lettre, $glo_nom, $glo_definition, $glo_affiche, $glo_lien) = $xoopsDB->fetchRow($TableRep)) {
            $glo_nom = $myts->displayTarea($glo_nom);

            $glo_definition = $myts->displayTarea($glo_definition);

            $commD = "<a href=\"glossaires-comm.php?sid=$glo_id\"><img src=\"images/comm-glo.gif\" border=0 alt=\"" . _COMMADD . '" width=15 height=11></a>&nbsp;';

            $imprD = "<a href=\"glossaires-p-f.php?op=ImprDef&sid=$glo_id\" target=\"_blank\"><img src=\"images/print.gif\" border=0 Alt=\"" . _PRINT . '" width=15 height=11></a>&nbsp;';

            $envD = "<a href=\"glossaires-p-f.php?op=EnvDef&sid=$glo_id\" target=\"_blank\"><img src=\"images/friend.gif\" border=0 Alt=\"" . _FRIENDSEND . '" width=15 height=11></a>';

            if (1 == $anocomm || $xoopsUser) {
                echo "$commD ";
            }

            echo "$imprD $envD - " . _CAT . '';

            $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('glossaires_cat') . " WHERE idcat='$glo_idcat'");

            [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

            echo "&nbsp;<b>$nomcat<br>$glo_nom</B> : $glo_definition";

            if ($glo_lien) {
                echo '<BR>+ ' . _LINKSASS2 . " <A HREF=\"$glo_lien\" TARGET=\"_blank\">$glo_lien</A>";
            }

            [$nbbs] = $xoopsDB->fetchRow($xoopsDB->query('SELECT count(id) as nbbs FROM ' . $xoopsDB->prefix('glossaires_comm') . " WHERE def='$glo_id' AND affiche='O'"));

            if ($nbbs > 0) {
                echo "<BR>+ <a href=\"glossaires-comm.php?op=LirComm&sid=$glo_id\">$nbbs " . _COM . '</a>';
            }

            if ($xoopsUser) {
                if ($xoopsUser->isAdmin()) {
                    echo "<BR>[ <A HREF=\"admin/mod-def.php?pa=modif&id=$glo_id\">" . _MODIFY . "</A> | <A HREF=\"admin/suppr-def.php?id=$glo_id\">" . _SUPPR . '</A> ]';
                }
            }

            echo '<br><P>';
        }
    }

    echo '<center>';

    if (1 == $top) {
        echo '<P><center>';

        if (0 != $debut) {
            echo '&lt;&lt; <a href="' . $topsuivant . '&debut=';

            $resultat = ($debut - $nb_affichage);

            if ($resultat < 0) {
                $resultat = 0;
            }

            echo "$resultat\">" . _DEFBACK . '</a> &lt;&lt; ';
        }

        if (($debut + $nb_affichage) < $nbe) {
            echo '&gt;&gt; <a href="' . $topsuivant . '&debut=';

            $final = ($debut + $nb_affichage);

            if ($final >= $nbe) {
                $final = ($nbe - $nb_affichage);
            }

            echo "$final\">" . _DEFNEXT . '</a> &gt;&gt;';
        }
    }
}

echo '<P><BR>';
LettreGlo();
echo "<P>\n\n";
MenuGlo();
Copyright();
require XOOPS_ROOT_PATH . '/footer.php';
