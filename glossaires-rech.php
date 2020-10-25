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

    $query = stripslashes($terme);

if ('1' == $type) {
    $types = "nom LIKE '%$query%'";
}
if ('2' == $type) {
    $types = "definition LIKE '%$query%'";
}
if ('3' == $type) {
    $types = "nom LIKE '%$query%' OR definition LIKE '%$query%'";
}

if ('' == $debut) {
    $debut = 0;
}
    if ($idcat > 0) {
        $andidcat = "AND idcat='$idcat'";
    } else {
        $andidcat = '';
    }
[$nbe] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('glossaires') . " WHERE $types AND affiche='O' " . $andidcat . ' ORDER BY nom'));

// // OpenTable();
echo '<B>' . _GLOSSAIRE . "</B><BR>\n";

MenuGlo();
$result = $xoopsDB->query('SELECT id, idcat, nom, definition, lien FROM ' . $xoopsDB->prefix('glossaires') . " WHERE $types AND affiche='O' " . $andidcat . " ORDER BY nom LIMIT $debut,$nb_affichage");

    $top = 1;
    $topsuivant = "glossaires-rech.php?terme=$terme&type=$type&idcat=$idcat";
    $nrows = $xoopsDB->getRowsNum($result);

  echo '<P><B>' . _RESULT . '</B>';

if (0 == $nrows) {
    echo '<P><CENTER>' . _NORESPOND . " <FONT COLOR=\"#FF0000\">$terme</FONT></CENTER><P><BR><BR>";
} else {
    if (1 == $top) {
        $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('glossaires_cat') . " WHERE idcat='$idcat'");

        [$tempidcat, $tempnomcat] = $xoopsDB->fetchRow($result_cat);

        if ('' != $terme) {
            if ($idcat > 0) {
                $affnomcat = ' ' . _INTHISCAT . " <font color=\"#FF0000\"><b>$tempnomcat</b></font>";
            } else {
                $affnomcat = ' ' . _INALLCATS;
            }
        }

        if ('' == $terme) {
            if ($idcat > 0) {
                $terme = $nomcat;

                $affnomcat = ' ' . _THISCAT . " <font color=\"#FF0000\"><b>$tempnomcat</b></font>";
            } else {
                $terme = _ALLCATS;
            }
        }

        echo '<P><font color="#FF0000"><b> ' . $nbe . '</b></font> ' . _DEFFOR . "  <font color=\"#FF0000\"><b>$terme</b></font>" . $affnomcat . '<p>';

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

        echo '</center><br>';

        while (list($glo_id, $glo_idcat, $glo_nom, $glo_definition, $glo_lien) = $xoopsDB->fetchRow($result)) {
            $glo_nom = $myts->displayTarea($glo_nom);

            $glo_definition = $myts->displayTarea($glo_definition);

            $commD = "<a href=\"glossaires-comm.php?sid=$glo_id\"><img src=\"images/comm-glo.gif\" border=0 alt=\"" . _COMMADD . '" width=15 height=11></a>&nbsp;';

            $imprD = "<a href=\"glossaires-p-f.php?op=ImprDef&sid=$glo_id\" target=\"_blank\"><img src=\"images/print.gif\" border=0 Alt=\"" . _PRINT . '" width=15 height=11></a>&nbsp;';

            $envD = "<a href=\"glossaires-p-f.php?op=EnvDef&sid=$glo_id\" target=\"_blank\"><img src=\"images/friend.gif\" border=0 Alt=\"" . _FRIENDSEND . '" width=15 height=11></a>';

            if (1 == $anocomm || $xoopsUser) {
                echo "$commD ";
            }

            echo "$imprD $envD -  " . _CAT . '';

            $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('glossaires_cat') . " WHERE idcat='$glo_idcat'");

            [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

            echo "&nbsp;$nomcat<br><B>$glo_nom</B> : $glo_definition";

            if ($glo_lien) {
                echo '<BR>+ ' . _LINKSASS2 . " <A HREF=\"$glo_lien\" TARGET=\"_blank\">$glo_lien</A>";
            }

            [$nbbs] = $xoopsDB->fetchRow($xoopsDB->query('SELECT count(id) as nbbs FROM ' . $xoopsDB->prefix('glossaires_comm') . " WHERE def='$glo_id'"));

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

        echo '</center>';

        echo '<P><HR>';
    }
}

  echo '<FORM ACTION="glossaires-rech.php" METHOD=POST>';
  echo '<B>' . _LOOKBY . '<BR>';
  echo '<CENTER>' . _TERME2 . "<BR><INPUT TYPE=\"text\" CLASS=textbox NAME=\"terme\" SIZE=40 value=\"$query\"><BR><BR>" . _LOOKFOR . '</B><BR>
<SELECT NAME="type">
<OPTION  VALUE="1"> ' . _TERME . '
<OPTION  VALUE="2"> ' . _DEF2 . '
<OPTION  VALUE="3"> ' . _TERMEANDDEF . '
</SELECT>
<br><br>';
// recherche par catégorie ou toutes les catégories
    echo "<BR><center><B class='bgyellow'>" . _LOOKFOR . ' ' . _CATS . '</b><BR>';
// recherche des catégories
    $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('glossaires_cat') . ' ');
// affichage des catégories
    echo '<select name="idcat">';
    echo "<option value='0'>0 : " . _ALL . ' </option>';
    while (list($idcat, $nomcat) = $xoopsDB->fetchRow($result_cat)) {
        $idcat = $myts->displayTarea($idcat);

        $nomcat = $myts->displayTarea($nomcat);

        echo "<option value='$idcat'>$idcat : $nomcat </option>";
    }
    echo '</select><br><br>

<INPUT TYPE="submit" VALUE="Go">
</CENTER>';
  echo '</FORM><P>';

  echo '<B>' . _LOOKFORL . '</B><P>';
LettreGlo();
     echo "<P>\n\n";
MenuGlo();
    // // CloseTable();
Copyright();
    require XOOPS_ROOT_PATH . '/footer.php';
