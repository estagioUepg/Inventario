DELIMITER //

CREATE OR REPLACE TRIGGER insere_software
BEFORE INSERT
ON software
FOR EACH ROW

BEGIN

    DECLARE nome TEXT;
    DECLARE proprietario TEXT;
    
    SELECT name
    INTO nome
    FROM ocsweb.software_name
    WHERE id = NEW.name_id; 
    
    SET NEW.nome = nome;
    
    SELECT publisher
    INTO proprietario
    FROM ocsweb.software_publisher
    WHERE id = NEW.pub_id; 
    
    SET NEW.proprietario = proprietario;
END;
//

DELIMITER ;
