USE pokedex;

DROP TABLE IF EXISTS pokemon;

CREATE TABLE pokemon
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    pokedex_nr  INT(3) ZEROFILL UNIQUE NOT NULL,
    name        VARCHAR(32)                                                                                                                                NOT NULL NOT NULL,
    caught      BOOL                                                                                                                                                         DEFAULT false    NOT NULL,
    type_1      VARCHAR(255) NOT NULL,
    type_2      VARCHAR(255) DEFAULT NULL
);