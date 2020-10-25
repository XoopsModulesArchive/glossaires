#
# Structure des tables du module Glossaires
#
# Cette version est issue du module glossaire
# elle inclue la possibilité de créer des catégories
# permettant de construire plusieurs glossaires.
#
#
#

CREATE TABLE glossaires (
    id         INT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    idcat      INT(2)              NOT NULL,
    lettre     VARCHAR(8)          NOT NULL,
    nom        LONGTEXT            NOT NULL,
    definition LONGTEXT            NOT NULL,
    affiche    VARCHAR(5)          NOT NULL,
    lien       VARCHAR(255)        NOT NULL,
    PRIMARY KEY (id),
    UNIQUE id (id)
);


CREATE TABLE glossaires_cat (
    idcat  INT(2) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    nomcat VARCHAR(50)        NOT NULL,
    PRIMARY KEY (idcat),
    UNIQUE idcat (idcat)
);

CREATE TABLE glossaires_comm (
    id          INT(10) DEFAULT '0' NOT NULL AUTO_INCREMENT,
    def         VARCHAR(10)         NOT NULL,
    auteur      VARCHAR(25)         NOT NULL,
    date        VARCHAR(25)         NOT NULL,
    commentaire TEXT                NOT NULL,
    url         VARCHAR(100)        NOT NULL,
    affiche     VARCHAR(5)          NOT NULL,
    PRIMARY KEY (id),
    UNIQUE id (id)
);
