DROP PROCEDURE IF EXISTS `SANSBOURSE`;
DELIMITER $$
CREATE  PROCEDURE `SANSBOURSE`(IN `indic` INT, IN `yearr` INT)
    NO SQL
BEGIN
    DECLARE cursor_sexe VARCHAR(255) DEFAULT "";
    DECLARE c_se VARCHAR(255) DEFAULT "";
    DECLARE cursor_statuts VARCHAR(255) DEFAULT "";
    DECLARE c_st VARCHAR(255) DEFAULT "";
    DECLARE cursor_categorie VARCHAR(255) DEFAULT "";
    DECLARE c_cat VARCHAR(255) DEFAULT "";
    DECLARE cursor_structure VARCHAR(255) DEFAULT "";
    DECLARE c_str VARCHAR(255) DEFAULT "";
    DECLARE cursor_corps VARCHAR(255) DEFAULT "";
    DECLARE c_cor VARCHAR(255) DEFAULT "";
    DECLARE cursor_indic VARCHAR(255) DEFAULT "";
    DECLARE result VARCHAR(255) DEFAULT "";

    DECLARE row_count INT;
    DECLARE val INT;
    DECLARE count_se INT;
    DECLARE count_st INT;
    DECLARE count_cat INT;
    DECLARE count_str INT;
    DECLARE count_cor INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE cursor_e CURSOR FOR SELECT agent_formations.sexe,agent_formations.status,agent_formations.corps,agent_formations.categorie,agent_formations.structure FROM mise_en_stages INNER JOIN agent_formations ON agent_formations.id=mise_en_stages.id_agent where mise_en_stages.annee_stage=yearr COLLATE utf8mb4_unicode_ci and mise_en_stages.isBoursier=0 COLLATE utf8mb4_unicode_ci;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN cursor_e;
        read_loop: LOOP
            FETCH cursor_e INTO cursor_sexe, cursor_statuts,cursor_corps, cursor_categorie, cursor_structure;
            IF done THEN
                LEAVE read_loop;
            END IF;
            
            SELECT wording INTO cursor_indic FROM indicators WHERE id=indic COLLATE utf8mb4_unicode_ci;
            SET count_se = 0;
            WHILE count_se < 2 DO
                IF ( count_se = 0 ) THEN
                    SET c_se = cursor_sexe;
                ELSEIF ( count_se = 1 ) THEN
                    SET c_se = "null";
                End IF;
            	SET count_st = 0;
                WHILE  count_st < 2 DO
                    IF ( count_st = 0 ) THEN
                        SET c_st = cursor_statuts;
                    ELSEIF ( count_st = 1 ) THEN
                        SET c_st = "null";
                    End IF;
                	SET count_cat  = 0;
                    WHILE count_cat < 2 DO
                        IF ( count_cat = 0 ) THEN
                            SET c_cat = cursor_categorie;
                        ELSEIF ( count_cat = 1 ) THEN
                            SET c_cat = "null";
                        End IF;
						SET count_str = 0;
                        WHILE  count_str < 2 DO
                            IF ( count_str = 0 ) THEN
                                SET c_str = cursor_structure;
                            ELSEIF ( count_str = 1 ) THEN
                                SET c_str = "null";
                            End IF;
                        	SET count_cor = 0;
                            WHILE  count_cor < 2 DO
                                IF ( count_cor = 0 ) THEN
                                    SET c_cor = cursor_corps;
                                ELSEIF ( count_cor = 1 ) THEN
                                    SET c_cor = "null";
                                End IF;
                                SELECT MD5(CONCAT(cursor_indic,c_se,c_st,c_cat,c_str,c_cor))  INTO  result;
                                SELECT COUNT(*) INTO row_count FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci AND annee=yearr COLLATE utf8mb4_unicode_ci;

                                IF ( row_count = 0 ) THEN
                                    INSERT INTO aggregat_values (hash_value,annee,value_statistic)
                                    VALUES (result,yearr, 1);
                                    INSERT INTO aggregat_test (hash_,indic,sexe) VALUES (CONCAT(cursor_indic,c_se,c_st,c_cat,c_str,c_cor),1,result) ;
                                ELSE
                                    SELECT value_statistic INTO val FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci AND annee=yearr COLLATE utf8mb4_unicode_ci;
                                    SET val = val + 1;
                                    INSERT INTO aggregat_test (hash_,indic,sexe) VALUES (CONCAT(cursor_indic,c_se,c_st,c_cat,c_str,c_cor),val,result) ;
                                    UPDATE aggregat_values SET value_statistic = val WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
                                END IF;
                            	SET count_cor = count_cor + 1;
                            END WHILE;
                        	SET count_str = count_str + 1;
                        END WHILE;
                    	SET count_cat = count_cat + 1;
                    END WHILE;
                    SET count_st = count_st + 1;
                END WHILE;
                SET count_se = count_se + 1;
            END WHILE;
            
            
        END LOOP;
    CLOSE cursor_e;
End$$
DELIMITER ;




DROP PROCEDURE IF EXISTS `RETOURDESTAGE`;
DELIMITER $$
CREATE  PROCEDURE `RETOURDESTAGE`(IN `indic` INT, IN `yearr` INT)
    NO SQL
BEGIN
    DECLARE cursor_sexe VARCHAR(255) DEFAULT "";
    DECLARE c_se VARCHAR(255) DEFAULT "";
    DECLARE cursor_statuts VARCHAR(255) DEFAULT "";
    DECLARE c_st VARCHAR(255) DEFAULT "";
    DECLARE cursor_categorie VARCHAR(255) DEFAULT "";
    DECLARE c_cat VARCHAR(255) DEFAULT "";
    DECLARE cursor_structure VARCHAR(255) DEFAULT "";
    DECLARE c_str VARCHAR(255) DEFAULT "";
    DECLARE cursor_corps VARCHAR(255) DEFAULT "";
    DECLARE c_cor VARCHAR(255) DEFAULT "";
    DECLARE cursor_indic VARCHAR(255) DEFAULT "";
    DECLARE result VARCHAR(255) DEFAULT "";

    DECLARE row_count INT;
    DECLARE val INT;
    DECLARE count_se INT;
    DECLARE count_st INT;
    DECLARE count_cat INT;
    DECLARE count_str INT;
    DECLARE count_cor INT;
    
    DECLARE done INT DEFAULT FALSE;
    DECLARE cursor_e CURSOR FOR SELECT agent_formations.sexe,agent_formations.status,agent_formations.corps,agent_formations.categorie,agent_formations.structure FROM retour_de_stages INNER JOIN agent_formations ON agent_formations.id=retour_de_stages.id_agent where retour_de_stages.annee_rs=yearr COLLATE utf8mb4_unicode_ci;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN cursor_e;
        read_loop: LOOP
            FETCH cursor_e INTO cursor_sexe, cursor_statuts,cursor_corps, cursor_categorie, cursor_structure;
            IF done THEN
                LEAVE read_loop;
            END IF;
            
            SELECT wording INTO cursor_indic FROM indicators WHERE id=indic COLLATE utf8mb4_unicode_ci;
            SET count_se = 0;
            WHILE count_se < 2 DO
                IF ( count_se = 0 ) THEN
                    SET c_se = cursor_sexe;
                ELSEIF ( count_se = 1 ) THEN
                    SET c_se = "null";
                End IF;
            	SET count_st = 0;
                WHILE  count_st < 2 DO
                    IF ( count_st = 0 ) THEN
                        SET c_st = cursor_statuts;
                    ELSEIF ( count_st = 1 ) THEN
                        SET c_st = "null";
                    End IF;
                	SET count_cat  = 0;
                    WHILE count_cat < 2 DO
                        IF ( count_cat = 0 ) THEN
                            SET c_cat = cursor_categorie;
                        ELSEIF ( count_cat = 1 ) THEN
                            SET c_cat = "null";
                        End IF;
						SET count_str = 0;
                        WHILE  count_str < 2 DO
                            IF ( count_str = 0 ) THEN
                                SET c_str = cursor_structure;
                            ELSEIF ( count_str = 1 ) THEN
                                SET c_str = "null";
                            End IF;
                        	SET count_cor = 0;
                            WHILE  count_cor < 2 DO
                                IF ( count_cor = 0 ) THEN
                                    SET c_cor = cursor_corps;
                                ELSEIF ( count_cor = 1 ) THEN
                                    SET c_cor = "null";
                                End IF;
                                SELECT MD5(CONCAT(cursor_indic,c_se,c_st,c_cat,c_str,c_cor))  INTO  result;
                                SELECT COUNT(*) INTO row_count FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci AND annee=yearr COLLATE utf8mb4_unicode_ci;

                                IF ( row_count = 0 ) THEN
                                    INSERT INTO aggregat_values (hash_value,annee,value_statistic)
                                    VALUES (result,yearr, 1);
                                ELSE
                                    SELECT value_statistic INTO val FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci AND annee=yearr COLLATE utf8mb4_unicode_ci;
                                    SET val = val + 1;
                                    UPDATE aggregat_values SET value_statistic = val WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
                                END IF;
                            	SET count_cor = count_cor + 1;
                            END WHILE;
                        	SET count_str = count_str + 1;
                        END WHILE;
                    	SET count_cat = count_cat + 1;
                    END WHILE;
                    SET count_st = count_st + 1;
                END WHILE;
                SET count_se = count_se + 1;
            END WHILE;
            
            
        END LOOP;
    CLOSE cursor_e;
End$$
DELIMITER ;


DROP PROCEDURE IF EXISTS `MISEENSTAGE`;
DELIMITER $$
CREATE  PROCEDURE `MISEENSTAGE`(IN `indic` INT, IN `yearr` INT)
    NO SQL
BEGIN
    DECLARE cursor_sexe VARCHAR(255) DEFAULT "";
    DECLARE c_se VARCHAR(255) DEFAULT "";
    DECLARE cursor_statuts VARCHAR(255) DEFAULT "";
    DECLARE c_st VARCHAR(255) DEFAULT "";
    DECLARE cursor_categorie VARCHAR(255) DEFAULT "";
    DECLARE c_cat VARCHAR(255) DEFAULT "";
    DECLARE cursor_structure VARCHAR(255) DEFAULT "";
    DECLARE c_str VARCHAR(255) DEFAULT "";
    DECLARE cursor_corps VARCHAR(255) DEFAULT "";
    DECLARE c_cor VARCHAR(255) DEFAULT "";
    DECLARE cursor_indic VARCHAR(255) DEFAULT "";
    DECLARE result VARCHAR(255) DEFAULT "";

    DECLARE row_count INT;
    DECLARE val INT;
    DECLARE count_se INT;
    DECLARE count_st INT;
    DECLARE count_cat INT;
    DECLARE count_str INT;
    DECLARE count_cor INT;
    
    DECLARE done INT DEFAULT FALSE;
    DECLARE cursor_e CURSOR FOR SELECT agent_formations.sexe,agent_formations.status,agent_formations.corps,agent_formations.categorie,agent_formations.structure FROM mise_en_stages INNER JOIN agent_formations ON agent_formations.id=mise_en_stages.id_agent where mise_en_stages.annee_stage=yearr COLLATE utf8mb4_unicode_ci;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN cursor_e;
        read_loop: LOOP
            FETCH cursor_e INTO cursor_sexe, cursor_statuts,cursor_corps, cursor_categorie, cursor_structure;
            IF done THEN
                LEAVE read_loop;
            END IF;
            
            SELECT wording INTO cursor_indic FROM indicators WHERE id=indic COLLATE utf8mb4_unicode_ci;
            SET count_se = 0;
            WHILE count_se < 2 DO
                IF ( count_se = 0 ) THEN
                    SET c_se = cursor_sexe;
                ELSEIF ( count_se = 1 ) THEN
                    SET c_se = "null";
                End IF;
            	SET count_st = 0;
                WHILE  count_st < 2 DO
                    IF ( count_st = 0 ) THEN
                        SET c_st = cursor_statuts;
                    ELSEIF ( count_st = 1 ) THEN
                        SET c_st = "null";
                    End IF;
                	SET count_cat  = 0;
                    WHILE count_cat < 2 DO
                        IF ( count_cat = 0 ) THEN
                            SET c_cat = cursor_categorie;
                        ELSEIF ( count_cat = 1 ) THEN
                            SET c_cat = "null";
                        End IF;
						SET count_str = 0;
                        WHILE  count_str < 2 DO
                            IF ( count_str = 0 ) THEN
                                SET c_str = cursor_structure;
                            ELSEIF ( count_str = 1 ) THEN
                                SET c_str = "null";
                            End IF;
                        	SET count_cor = 0;
                            WHILE  count_cor < 2 DO
                                IF ( count_cor = 0 ) THEN
                                    SET c_cor = cursor_corps;
                                ELSEIF ( count_cor = 1 ) THEN
                                    SET c_cor = "null";
                                End IF;
                                SELECT MD5(CONCAT(cursor_indic,c_se,c_st,c_cat,c_str,c_cor))  INTO  result;
                                SELECT COUNT(*) INTO row_count FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci AND annee=yearr COLLATE utf8mb4_unicode_ci;

                                IF ( row_count = 0 ) THEN
                                    INSERT INTO aggregat_values (hash_value,annee,value_statistic)
                                    VALUES (result,yearr, 1);
                                ELSE
                                    SELECT value_statistic INTO val FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci AND annee=yearr COLLATE utf8mb4_unicode_ci;
                                    SET val = val + 1;
                                    UPDATE aggregat_values SET value_statistic = val WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
                                END IF;
                            	SET count_cor = count_cor + 1;
                            END WHILE;
                        	SET count_str = count_str + 1;
                        END WHILE;
                    	SET count_cat = count_cat + 1;
                    END WHILE;
                    SET count_st = count_st + 1;
                END WHILE;
                SET count_se = count_se + 1;
            END WHILE;
            
            
        END LOOP;
    CLOSE cursor_e;
End$$
DELIMITER ;


DROP PROCEDURE IF EXISTS `AVECBOURSE`;

DELIMITER $$
CREATE  PROCEDURE `AVECBOURSE`(IN `indic` INT, IN `yearr` INT)
    NO SQL
BEGIN
    DECLARE cursor_sexe VARCHAR(255) DEFAULT "";
    DECLARE c_se VARCHAR(255) DEFAULT "";
    DECLARE cursor_statuts VARCHAR(255) DEFAULT "";
    DECLARE c_st VARCHAR(255) DEFAULT "";
    DECLARE cursor_categorie VARCHAR(255) DEFAULT "";
    DECLARE c_cat VARCHAR(255) DEFAULT "";
    DECLARE cursor_structure VARCHAR(255) DEFAULT "";
    DECLARE c_str VARCHAR(255) DEFAULT "";
    DECLARE cursor_corps VARCHAR(255) DEFAULT "";
    DECLARE c_cor VARCHAR(255) DEFAULT "";
    DECLARE cursor_indic VARCHAR(255) DEFAULT "";
    DECLARE result VARCHAR(255) DEFAULT "";

    DECLARE row_count INT;
    DECLARE val INT;
    DECLARE count_se INT;
    DECLARE count_st INT;
    DECLARE count_cat INT;
    DECLARE count_str INT;
    DECLARE count_cor INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE cursor_e CURSOR FOR SELECT agent_formations.sexe,agent_formations.status,agent_formations.corps,agent_formations.categorie,agent_formations.structure FROM mise_en_stages INNER JOIN agent_formations ON agent_formations.id=mise_en_stages.id_agent where mise_en_stages.annee_stage=yearr COLLATE utf8mb4_unicode_ci and mise_en_stages.isBoursier=1 COLLATE utf8mb4_unicode_ci;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN cursor_e;
        read_loop: LOOP
            FETCH cursor_e INTO cursor_sexe, cursor_statuts,cursor_corps, cursor_categorie, cursor_structure;
            IF done THEN
                LEAVE read_loop;
            END IF;
            
            SELECT wording INTO cursor_indic FROM indicators WHERE id=indic COLLATE utf8mb4_unicode_ci;
            SET count_se = 0;
            WHILE count_se < 2 DO
                IF ( count_se = 0 ) THEN
                    SET c_se = cursor_sexe;
                ELSEIF ( count_se = 1 ) THEN
                    SET c_se = "null";
                End IF;
            	SET count_st = 0;
                WHILE  count_st < 2 DO
                    IF ( count_st = 0 ) THEN
                        SET c_st = cursor_statuts;
                    ELSEIF ( count_st = 1 ) THEN
                        SET c_st = "null";
                    End IF;
                	SET count_cat  = 0;
                    WHILE count_cat < 2 DO
                        IF ( count_cat = 0 ) THEN
                            SET c_cat = cursor_categorie;
                        ELSEIF ( count_cat = 1 ) THEN
                            SET c_cat = "null";
                        End IF;
						SET count_str = 0;
                        WHILE  count_str < 2 DO
                            IF ( count_str = 0 ) THEN
                                SET c_str = cursor_structure;
                            ELSEIF ( count_str = 1 ) THEN
                                SET c_str = "null";
                            End IF;
                        	SET count_cor = 0;
                            WHILE  count_cor < 2 DO
                                IF ( count_cor = 0 ) THEN
                                    SET c_cor = cursor_corps;
                                ELSEIF ( count_cor = 1 ) THEN
                                    SET c_cor = "null";
                                End IF;
                                SELECT MD5(CONCAT(cursor_indic,c_se,c_st,c_cat,c_str,c_cor))  INTO  result;
                                SELECT COUNT(*) INTO row_count FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci AND annee=yearr COLLATE utf8mb4_unicode_ci;

                                IF ( row_count = 0 ) THEN
                                    INSERT INTO aggregat_values (hash_value,annee,value_statistic)
                                    VALUES (result,yearr, 1);
                                    INSERT INTO aggregat_test (hash_,indic,sexe) VALUES (CONCAT(cursor_indic,c_se,c_st,c_cat,c_str,c_cor),1,result) ;
                                ELSE
                                    SELECT value_statistic INTO val FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci AND annee=yearr COLLATE utf8mb4_unicode_ci;
                                    SET val = val + 1;
                                    INSERT INTO aggregat_test (hash_,indic,sexe) VALUES (CONCAT(cursor_indic,c_se,c_st,c_cat,c_str,c_cor),val,result) ;
                                    UPDATE aggregat_values SET value_statistic = val WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
                                END IF;
                            	SET count_cor = count_cor + 1;
                            END WHILE;
                        	SET count_str = count_str + 1;
                        END WHILE;
                    	SET count_cat = count_cat + 1;
                    END WHILE;
                    SET count_st = count_st + 1;
                END WHILE;
                SET count_se = count_se + 1;
            END WHILE;
            
            
        END LOOP;
    CLOSE cursor_e;
End$$
DELIMITER ;


DROP PROCEDURE IF EXISTS `ALLDONE`;

DELIMITER $$
CREATE PROCEDURE `ALLDONE`()
    NO SQL
BEGIN
    DECLARE c_indic VARCHAR(255) DEFAULT "";
    DECLARE c_annee VARCHAR(255) DEFAULT "";
    DECLARE length INT DEFAULT 0;
    DECLARE counter INT DEFAULT 0;

    SELECT COUNT(*) FROM aggregat_inputs INTO length;
    SET counter=0;
    WHILE counter<length DO
    	SELECT indic, annee INTO c_indic, c_annee FROM aggregat_inputs LIMIT counter,1;
        CALL  AVECBOURSE(c_indic,c_annee);
        CALL  MISEENSTAGE(c_indic,c_annee);
        CALL  SANSBOURSE(c_indic,c_annee);
        CALL  RETOURDESTAGE(c_indic,c_annee);
        SET counter = counter + 1;
    END WHILE;
End$$
DELIMITER ;

DROP PROCEDURE IF EXISTS `CONTROLLER`;
DELIMITER $$
CREATE  PROCEDURE `CONTROLLER`()
    NO SQL
BEGIN
    DECLARE c_indic VARCHAR(255) DEFAULT "";
    DECLARE c_annee VARCHAR(255) DEFAULT "";
    DECLARE length INT DEFAULT 0;
    DECLARE counter INT DEFAULT 0;

    SELECT COUNT(*) FROM aggregat_inputs INTO length;
    SET counter=0;
    WHILE counter<length DO
    	SELECT indic, annee INTO c_indic, c_annee FROM aggregat_inputs LIMIT counter,1;
        IF ( c_indic = 225) THEN 
            CALL  AVECBOURSE(c_indic,c_annee);
        ELSEIF ( c_indic = 226 ) THEN
            CALL  MISEENSTAGE(c_indic,c_annee);
        ELSEIF ( c_indic = 251 ) THEN
            CALL  SANSBOURSE(c_indic,c_annee);
        ELSEIF ( c_indic = 252 ) THEN
            CALL  RETOURDESTAGE(c_indic,c_annee);
        ELSE           
            CALL  ALLDONE();
        END IF;
        SET counter = counter + 1;
    END WHILE;
End$$
DELIMITER ;



DROP PROCEDURE IF EXISTS `FILLINDICATORS`;
DELIMITER $$
CREATE  PROCEDURE `FILLINDICATORS`(IN `îndic` INT)
    NO SQL
BEGIN
    DECLARE row_count INT;
    DECLARE val INT;
    DECLARE done INT DEFAULT FALSE;
    DECLARE cursor_e CURSOR FOR SELECT id from levelofdisintegration where id_type in (85,87,122,124,125);
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN cursor_e;
        read_loop: LOOP
            FETCH cursor_e INTO val;
            IF done THEN
                LEAVE read_loop;
            END IF;
            
            SELECT COUNT(*) INTO row_count FROM indicators_level WHERE id_level=val COLLATE utf8mb4_unicode_ci and id_indicator=indic COLLATE utf8mb4_unicode_ci;
            
            IF ( row_count != 0 ) THEN
                ITERATE read_loop;
            END IF;
            
            INSERT INTO indicators_level (id_level,id_indicator) VALUES (val,indic) ;
            
        END LOOP;
    CLOSE cursor_e;
End$$
DELIMITER ;



DROP TABLE IF EXISTS `annee`;
CREATE TABLE annee (
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    value varchar(255)
);


DROP PROCEDURE IF EXISTS `FILLANNEE`;
DELIMITER $$
CREATE  PROCEDURE `FILLANNEE`()
    NO SQL
BEGIN
    DECLARE row_count INT DEFAULT 2000;
    WHILE  row_count < 2101 DO
            INSERT INTO annee (value) VALUES (row_count) ;
            SET row_count = row_count + 1;
    END WHILE;
    INSERT INTO annee (value) VALUES ("null") ;
End$$
DELIMITER ;

DROP TRIGGER IF EXISTS `CALLFILL`;
CREATE TRIGGER `CALLFILL` AFTER INSERT ON `aggregat_start`
 FOR EACH ROW CALL CONTROLLER();








CALL FILLINDICATORS(226);
CALL FILLINDICATORS(225);
CALL FILLINDICATORS(251);
CALL FILLINDICATORS(252);
CALL FILLANNEE();





 

UPDATE `indicators` SET `wording` = 'Effectifs des agents civils de l’Etat ayant bénéficié de décision de mise en stage' WHERE `indicators`.`id` = 226;

INSERT INTO `indicators` (`id`, `wording`, `id_subdomain`, `created_at`, `updated_at`) VALUES (NULL, "Effectifs des agents civils de l’Etat n'ayant pas bénéficié de bourses", '31', NULL, NULL);
INSERT INTO `indicators` (`id`, `wording`, `id_subdomain`, `created_at`, `updated_at`) VALUES (NULL, "Effectifs des agents civils de l’Etat ayant bénéficié de décision de retour de stage", '31', NULL, NULL);
INSERT INTO `levelofdisintegration` (`id`, `wording`, `id_type`, `created_at`, `updated_at`) VALUES (NULL, 'null', '85', NULL, NULL);
INSERT INTO `levelofdisintegration` (`id`, `wording`, `id_type`, `created_at`, `updated_at`) VALUES (NULL, 'null', '87', NULL, NULL);
INSERT INTO `levelofdisintegration` (`id`, `wording`, `id_type`, `created_at`, `updated_at`) VALUES (NULL, 'null', '125', NULL, NULL);
INSERT INTO `levelofdisintegration` (`id`, `wording`, `id_type`, `created_at`, `updated_at`) VALUES (NULL, 'null', '122', NULL, NULL);
INSERT INTO `levelofdisintegration` (`id`, `wording`, `id_type`, `created_at`, `updated_at`) VALUES (NULL, 'null', '124', NULL, NULL);





