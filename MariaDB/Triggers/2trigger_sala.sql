DELIMITER //

CREATE OR REPLACE TRIGGER insere_maquina
BEFORE INSERT
ON maquina
FOR EACH ROW
BEGIN
    SET NEW.nome_o = NEW.nome_n;
    SET NEW.ip_o = NEW.ip_n;
    SET NEW.processador_o = NEW.processador_n;
    
    IF NEW.nome_n LIKE "%cca06%"
    THEN 
    SET NEW.nome_sala = "CCA06";
    UPDATE sala SET qtd_maquinas = qtd_maquinas+1 WHERE nome = "CCA06";
    END IF;
    
    IF NEW.nome_n LIKE "%l07%"
    THEN 
    SET NEW.nome_sala = "L07";
    UPDATE sala SET qtd_maquinas = qtd_maquinas+1 WHERE nome = "L07";
    END IF;
    
    IF NEW.nome_n LIKE "%cca05%"
    THEN 
    SET NEW.nome_sala = "CCA05";
    UPDATE sala SET qtd_maquinas = qtd_maquinas+1 WHERE nome = "CCA05";
    END IF;
    
    IF NEW.nome_n LIKE "%uepg-71%"
    THEN 
    SET NEW.nome_sala = "CCA05";
    UPDATE sala SET qtd_maquinas = qtd_maquinas+1 WHERE nome = "CCA05";
    END IF;
    
    IF NEW.nome_n LIKE "%lab06%"
    THEN 
    SET NEW.nome_sala = "LAB06";
    UPDATE sala SET qtd_maquinas = qtd_maquinas+1 WHERE nome = "LAB06";
    END IF;
END;
//

DELIMITER ;
