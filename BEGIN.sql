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
End