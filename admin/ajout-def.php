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

function AjDef($idcat, $lettre, $nom, $definition, $affiche, $lien)
{
    global $xoopsConfig, $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $nom = $myts->addSlashes($nom);

    $definition = $myts->addSlashes($definition);

    if ('' == $lettre) {
        echo '<P><BR><CENTER><FONT COLOR="#FF0000">' . _OOPSLETTRE . '</FONT></CENTER>';
    } else {
        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('glossaires') . " VALUES ('', '$idcat', '$lettre', '$nom', '$definition', '$affiche', '$lien')");

        redirect_header('index.php', 1, _ENRDEF);

        exit();
    }
}

function FormDef($sid)
{
    global $xoopsConfig, $xoopsModule, $xoopsDB;

    $myts = MyTextSanitizer::getInstance();

    $glossaires_cat = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('glossaires_cat') . '');

    $NombreEntrees = $xoopsDB->getRowsNum($glossaires_cat);

    if (0 == $NombreEntrees) {
        redirect_header('ajout-cat.php', 3, _NOCATREDIR);
    } else {
        xoops_cp_header();

        echo '<B>' . _ADMIN . ' ' . $xoopsConfig['sitename'] . '</B><p>';

        echo '<CENTER>[ <A HREF="index.php">' . _ADMIN2 . '</A> | <A HREF="../index.php">' . _SEELIST . '</A> | ' . _ADDDEF . ' ]</CENTER>';

        echo '<br><B>' . _ADDDEF . "</B>
<P>
<FORM ACTION='ajout-def.php?pa=AjDef' METHOD=POST>
<INPUT TYPE=\"hidden\" NAME=\"affiche\" VALUE=\"O\">
<TABLE BORDER=0 CELLPADDING=5>
	<tr>
	  <td>" . _CAT . ' 
	  </td>
	  <td>';

        // recherche des catégories

        $result = $xoopsDB->query('select idcat, nomcat from ' . $xoopsDB->prefix('glossaires_cat') . ' ');

        // affichage des catégories

        echo '<select name="idcat" size="1">';

        while (list($idcat, $nomcat) = $xoopsDB->fetchRow($result)) {
            $idcat = $myts->displayTarea($idcat);

            $nomcat = $myts->displayTarea($nomcat);

            echo '<option value=' . $idcat . "> $idcat : $nomcat </option>";
        }

        echo '</select>';

        echo '</td>
	</tr>
    <TR>
      <TD ALIGN="LEFT">' . _LETTRE . ' </TD>
      <TD>';

        LettreGloAj();

        echo '</TD>
    </TR>
    <TR>
      <TD ALIGN="LEFT">' . _TERME2 . " </TD>
      <TD><INPUT TYPE='text' NAME='nom' SIZE=50></TD>
    </TR>
    <TR>
      <TD ALIGN=\"LEFT\">" . _DEF3 . ' </TD>
      <TD>';

        $allowbbcode = 1;

        $allowhtml = 1;

        $allowsmileys = 1;

        require_once XOOPS_ROOT_PATH . '/include/xoopscodes.php';

        if (1 == $allowbbcode) {
            xoopsCodeTarea('definition');
        } else {
            echo "<textarea id='definition' name='definition' wrap='virtual' cols='50' rows='10'></textarea><br>";
        }

        if (1 == $allowsmileys) {
            xoopsSmilies('definition');
        }

        echo '</TD>
    </TR>
    <TR>
      <TD ALIGN="LEFT">' . _LINKSASS2 . " </TD>
      <TD><INPUT TYPE='text' NAME='lien' SIZE=50><BR><FONT SIZE=1>Ex. : http://www." . _NAMESIT . ".com</FONT></TD>
    </TR>
    <TR>
      <TD ALIGN=\"CENTER\" COLSPAN=2><CENTER><INPUT TYPE='submit' VALUE='" . _ADD . "'></CENTER></TD>
    </TR>
</TABLE>
</FORM>";

        // CloseTable();

        xoops_cp_footer();
    }
}

switch ($pa) {
    case 'AjDef':
    AjDef($idcat, $lettre, $nom, $definition, $affiche, $lien);
    break;
    default:
    FormDef();
    break;
}
