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

function MenuGlo()
{
    echo '<CENTER> [ <A HREF="index.php">' . _WELCOMEGLO . '</A> | <A HREF="index.php?pa=Propose">' . _PRODEF . '</A> | <A HREF="index.php?pa=Demande">' . _ASKDEF . "</A> ]</CENTER><P>\n";
}

function Copyright()
{
    echo "<br><DIV ALIGN=\"center\"><FONT SIZE=1 class='bnewst'>" . _MODULEORIG . '<br>' . _GLOSSAIRE . ' 2.2 ' . _FOR . ' Xoops 2.X ' . _CREATBY . ' <A HREF="mailto:webmaster@toplenet.com">Martialito</A> ' . _DE . ' <A HREF="http://www.toplenet.com" TARGET="_blank">http://www.toplenet.com</A></FONT></DIV>';
}

function LettreGlo()
{
    echo '<CENTER>[  ';

    $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
];

    $num = count($alphabet) - 1;

    $counter = 0;

    while (list(, $ltr) = each($alphabet)) {
        echo "<a href=\"glossaires-aff.php?lettre=$ltr\">$ltr</a>";

        if ($counter == round($num / 2)) {
            echo " ]\n<br>\n[ ";
        } elseif ($counter != $num) {
            echo "&nbsp;|&nbsp;\n";
        }

        $counter++;
    }

    echo ' | <a href="glossaires-aff.php?lettre=autre">' . _OTHERS . "</a> ]</CENTER>\n\n";
}

function LettreGloAj($texte_lettre)
{
    echo "<SELECT NAME=\"lettre\">\n";

    if ($texte_lettre) {
        echo "<OPTION VALUE=\"$texte_lettre\"> $texte_lettre";
    }

    $alphabet = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
];

    $num = count($alphabet) - 1;

    $counter = 0;

    while (list(, $ltr) = each($alphabet)) {
        echo "<OPTION VALUE=\"$ltr\"> $ltr\n";

        $counter++;
    }

    echo '<OPTION VALUE="autre"> ' . _OTHERS . '';

    echo "</SELECT>\n\n";
}

function NouvDef()
{
    global $xoopsConfig, $xoopsDB, $xoopsUser;

    include 'glossaires-config.php';

    $myts = MyTextSanitizer::getInstance();

    echo '<P>';

    [$nbrs] = $xoopsDB->fetchRow($xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('glossaires') . " WHERE affiche='O'"));

    if (0 != $nbrs) {
        echo "<center><B class='bnewst'>10 " . _LASTDEF . '</B><BR><BR></center>';

        echo '<hr>';

        $TableRep = $xoopsDB->query('SELECT id, idcat, lettre, nom, definition, affiche, lien FROM ' . $xoopsDB->prefix('glossaires') . " WHERE affiche='O' ORDER BY id DESC", 10, 0);

        while (list($glo_id, $glo_idcat, $glo_lettre, $glo_nom, $glo_definition, $glo_affiche, $glo_lien) = $xoopsDB->fetchRow($TableRep)) {
            $glo_nom = $myts->displayTarea($glo_nom);

            $allowbbcode = 1;

            $allowhtml = 0;

            $allowsmileys = 1;

            $glo_definition = $myts->displayTarea($glo_definition, $allowhtml, $allowsmileys, $allowbbcode);

            $commD = "<a href=\"glossaires-comm.php?sid=$glo_id\" target=\"_top\"><img src=\"images/comm-glo.gif\" border=0 alt=\"" . _COMMADD . '" width=15 height=11></a>&nbsp;';

            $imprD = "<a href=\"glossaires-p-f.php?op=ImprDef&sid=$glo_id\" target=\"_blank\"><img src=\"images/print.gif\" border=0 Alt=\"" . _PRINT . '" width=15 height=11></a>&nbsp;';

            $envD = "<a href=\"glossaires-p-f.php?op=EnvDef&sid=$glo_id\" target=\"_blank\"><img src=\"images/friend.gif\" border=0 Alt=\"" . _FRIENDSEND . '" width=15 height=11></a>';

            $result_cat = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('glossaires_cat') . " where idcat=$glo_idcat");

            [$idcat, $nomcat] = $xoopsDB->fetchRow($result_cat);

            echo '<center>' . _CAT . "<B>&nbsp;$nomcat<br><br>\" $glo_nom \"</B></center><br><table align='center'><tr><td>$glo_definition</td></tr></table>";

            if ($glo_lien) {
                echo '<BR>+ ' . _LINKSASS2 . " <A HREF=\"$glo_lien\" TARGET=\"_blank\">$glo_lien</A>";
            }

            [$nbbs] = $xoopsDB->fetchRow($xoopsDB->query('SELECT count(id) as nbbs FROM ' . $xoopsDB->prefix('glossaires_comm') . " WHERE def='$glo_id' AND affiche='O'"));

            if ($nbbs > 0) {
                echo "<BR>+ <a href=\"glossaires-comm.php?op=LirComm&sid=$glo_id\">$nbbs " . _COM . '</a>';
            }

            echo '<center>';

            if (1 == $anocomm || $xoopsUser) {
                echo "<br>$commD ";
            }

            echo "$imprD $envD";

            if ($xoopsUser) {
                if ($xoopsUser->isAdmin()) {
                    echo "<BR><BR>[ <A HREF=\"admin/mod-def.php?pa=modif&id=$glo_id\">" . _MODIFY . "</A> | <A HREF=\"admin/suppr-def.php?id=$glo_id\">" . _SUPPR . '</A> ]';
                }
            }

            echo '</center>';

            echo '<hr>';
        }
    }
}
