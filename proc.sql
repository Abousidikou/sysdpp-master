DROP PROCEDURE `ALLDONE`;
CREATE DEFINER=`mysql`@`localhost` PROCEDURE `ALLDONE`() NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN
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
End


DROP PROCEDURE `AVECBOURSE`;
CREATE DEFINER=`mysql`@`localhost` PROCEDURE `AVECBOURSE`(IN `indic` INT, IN `yearr` INT) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN
    DECLARE cursor_sexe VARCHAR(255) DEFAULT "";
    DECLARE cursor_statuts VARCHAR(255) DEFAULT "";
    DECLARE cursor_categorie VARCHAR(255) DEFAULT "";
    DECLARE cursor_structure VARCHAR(255) DEFAULT "";
    DECLARE cursor_corps VARCHAR(255) DEFAULT "";
    DECLARE cursor_indic VARCHAR(255) DEFAULT "";
    DECLARE result VARCHAR(255) DEFAULT "";

    DECLARE row_count INT;
    DECLARE val INT;
    
    DECLARE done INT DEFAULT FALSE;
    DECLARE cursor_e CURSOR FOR SELECT agent_formations.sexe,agent_formations.status,agent_formations.corps,agent_formations.categorie,agent_formations.structure FROM mise_en_stages INNER JOIN agent_formations ON agent_formations.id=mise_en_stages.id_agent where mise_en_stages.nature_bourse != "null" COLLATE utf8mb4_unicode_ci;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN cursor_e;
        read_loop: LOOP
            FETCH cursor_e INTO cursor_sexe, cursor_statuts,cursor_corps, cursor_categorie, cursor_structure;
            IF done THEN
                LEAVE read_loop;
            END IF;
            
            SELECT wording INTO cursor_indic FROM indicators WHERE id=indic COLLATE utf8mb4_unicode_ci;
            SELECT MD5(CONCAT(cursor_indic,cursor_sexe,cursor_statuts,cursor_categorie,cursor_structure,cursor_corps))  INTO  result;

            SELECT COUNT(*) INTO row_count FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci;

            IF (row_count != 1) THEN
                INSERT INTO aggregat_values (hash_value,annee,value_statistic)
                VALUES (result,yearr, 0);
            ELSE
                SELECT value_statistic INTO val FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
                SET val = val + 1;
                UPDATE aggregat_values SET value_statistic = val WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
            END IF;
            
        END LOOP;
    CLOSE cursor_e;
End



DROP PROCEDURE `CONTROLLER`;
CREATE DEFINER=`mysql`@`localhost` PROCEDURE `CONTROLLER`() NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN
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
        ELSEIF ( c_indic = 226 ) THEN
            CALL  RETOURDESTAGE(c_indic,c_annee);
        ELSE           
            CALL  ALLDONE();
        END IF;
        SET counter = counter + 1;
    END WHILE;
End



DROP PROCEDURE `MISEENSTAGE`;
CREATE DEFINER=`mysql`@`localhost` PROCEDURE `MISEENSTAGE`(IN `indic` INT, IN `yearr` INT) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN
    DECLARE cursor_sexe VARCHAR(255) DEFAULT "";
    DECLARE cursor_statuts VARCHAR(255) DEFAULT "";
    DECLARE cursor_categorie VARCHAR(255) DEFAULT "";
    DECLARE cursor_structure VARCHAR(255) DEFAULT "";
    DECLARE cursor_corps VARCHAR(255) DEFAULT "";
    DECLARE cursor_indic VARCHAR(255) DEFAULT "";
    DECLARE result VARCHAR(255) DEFAULT "";

    DECLARE row_count INT;
    DECLARE val INT;
    
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
            SELECT MD5(CONCAT(cursor_indic,cursor_sexe,cursor_statuts,cursor_categorie,cursor_structure,cursor_corps))  INTO  result;

            SELECT COUNT(*) INTO row_count FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci;

            IF (row_count != 1) THEN
                INSERT INTO aggregat_values (hash_value,annee,value_statistic)
                VALUES (result,yearr, 0);
            ELSE
                SELECT value_statistic INTO val FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
                SET val = val + 1;
                UPDATE aggregat_values SET value_statistic = val WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
            END IF;
            
        END LOOP;
    CLOSE cursor_e;
End



DROP PROCEDURE `RETOURDESTAGE`;
CREATE DEFINER=`mysql`@`localhost` PROCEDURE `RETOURDESTAGE`(IN `indic` INT, IN `yearr` INT) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN
    DECLARE cursor_sexe VARCHAR(255) DEFAULT "";
    DECLARE cursor_statuts VARCHAR(255) DEFAULT "";
    DECLARE cursor_categorie VARCHAR(255) DEFAULT "";
    DECLARE cursor_structure VARCHAR(255) DEFAULT "";
    DECLARE cursor_corps VARCHAR(255) DEFAULT "";
    DECLARE cursor_indic VARCHAR(255) DEFAULT "";
    DECLARE result VARCHAR(255) DEFAULT "";

    DECLARE row_count INT;
    DECLARE val INT;
    
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
            SELECT MD5(CONCAT(cursor_indic,cursor_sexe,cursor_statuts,cursor_categorie,cursor_structure,cursor_corps))  INTO  result;

            SELECT COUNT(*) INTO row_count FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci;

            IF (row_count != 1) THEN
                INSERT INTO aggregat_values (hash_value,annee,value_statistic)
                VALUES (result,yearr, 0);
            ELSE
                SELECT value_statistic INTO val FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
                SET val = val + 1;
                UPDATE aggregat_values SET value_statistic = val WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
            END IF;
            
        END LOOP;
    CLOSE cursor_e;
End





DROP PROCEDURE `SANSBOURSE`;
CREATE DEFINER=`mysql`@`localhost` PROCEDURE `SANSBOURSE`(IN `indic` INT, IN `yearr` INT) NOT DETERMINISTIC NO SQL SQL SECURITY DEFINER BEGIN
    DECLARE cursor_sexe VARCHAR(255) DEFAULT "";
    DECLARE cursor_statuts VARCHAR(255) DEFAULT "";
    DECLARE cursor_categorie VARCHAR(255) DEFAULT "";
    DECLARE cursor_structure VARCHAR(255) DEFAULT "";
    DECLARE cursor_corps VARCHAR(255) DEFAULT "";
    DECLARE cursor_indic VARCHAR(255) DEFAULT "";
    DECLARE result VARCHAR(255) DEFAULT "";

    DECLARE row_count INT;
    DECLARE val INT;
    
    DECLARE done INT DEFAULT FALSE;
    DECLARE cursor_e CURSOR FOR SELECT agent_formations.sexe,agent_formations.status,agent_formations.corps,agent_formations.categorie,agent_formations.structure FROM mise_en_stages INNER JOIN agent_formations ON agent_formations.id=mise_en_stages.id_agent where mise_en_stages.nature_bourse = "null" COLLATE utf8mb4_unicode_ci;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    OPEN cursor_e;
        read_loop: LOOP
            FETCH cursor_e INTO cursor_sexe, cursor_statuts,cursor_corps, cursor_categorie, cursor_structure;
            IF done THEN
                LEAVE read_loop;
            END IF;
            
            SELECT wording INTO cursor_indic FROM indicators WHERE id=indic COLLATE utf8mb4_unicode_ci;
            SELECT MD5(CONCAT(cursor_indic,cursor_sexe,cursor_statuts,cursor_categorie,cursor_structure,cursor_corps))  INTO  result;

            SELECT COUNT(*) INTO row_count FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci;

            IF (row_count != 1) THEN
                INSERT INTO aggregat_values (hash_value,annee,value_statistic)
                VALUES (result,yearr, 0);
            ELSE
                SELECT value_statistic INTO val FROM aggregat_values WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
                SET val = val + 1;
                UPDATE aggregat_values SET value_statistic = val WHERE hash_value=result COLLATE utf8mb4_unicode_ci;
            END IF;
            
        END LOOP;
    CLOSE cursor_e;
End