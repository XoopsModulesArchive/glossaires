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

function Propdef($lettre, $affiche, $logname, $idcat, $terme, $def, $lien, $mess)
{
    global $xoopsConfig, $xoopsDB, $xoopsUser;

    $myts = MyTextSanitizer::getInstance();

    if ('' != $terme && '' != $def) {
        $terme = $myts->addSlashes($terme);

        $def = $myts->addSlashes($def);

        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('glossaires') . " (id, idcat, lettre, nom, definition, affiche, lien) VALUES ('', '$idcat', '$lettre', '$terme', '$def', '$affiche', '$lien')");

        if ($xoopsUser) {
            $result = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uname='$logname'");

            [$adrs] = $xoopsDB->fetchRow($result);
        } else {
            $adrs = $xoopsConfig['adminmail'];
        }

        if (1 == $mess) {
            $message = '' . _PROBY . " $logname $adrs\n\n" . _SUBTERM . " $terme\n" . _DEF3 . " $def";

            $subject = '' . _PRODEFBY . " $terme";

            mail((string)$adminmail, (string)$subject, $message, "From: $adrs");
        }

        redirect_header('index.php', 1, '' . _YOSUBREG . '');

        exit();
    }

    redirect_header('index.php?pa=Propose', 3, '' . _NOTERMEDEF . '');

    exit();
}

function Demdef($terme, $affiche, $mess, $logname)
{
    global $xoopsConfig, $xoopsDB, $xoopsUser;

    $myts = MyTextSanitizer::getInstance();

    if ('' != $terme) {
        $terme = $myts->addSlashes($terme);

        $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('glossaires') . " (id, lettre, nom, definition, affiche) VALUES ('', '' , '$terme', '', '$affiche')");

        if ($xoopsUser) {
            $result = $xoopsDB->query('select email from ' . $xoopsDB->prefix('users') . " where uname='$logname'");

            [$adrs] = $xoopsDB->fetchRow($result);
        } else {
            $adrs = $xoopsConfig['adminmail'];
        }

        if (1 == $mess) {
            $message = '' . _ASKFROM . " $logname $adrs\n\n" . _DEMTERM . " $terme";

            $subject = '' . _ASKDEF . " $terme";

            mail((string)$adminmail, (string)$subject, $message, "From: $adrs");
        }

        redirect_header('index.php', 1, '' . _YOREQREG . '');

        exit();
    }

    redirect_header('index.php?pa=Demande', 3, '' . _NOTERME . '');

    exit();
}

    switch ($pa) {
        case 'Propdef':
                      Propdef($lettre, $affiche, $logname, $idcat, $terme, $def, $lien, $mess);
                      break;
        case 'demdef':
                      Demdef($terme, $affiche, $mess, $logname);
                      break;
        default:
    redirect_header('index.php', 1, '' . _RETURNGLO . '');
                      break;
        }
