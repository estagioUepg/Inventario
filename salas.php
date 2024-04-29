<!DOCTYPE html>
<html lang="en">
 
<head>
<link rel="stylesheet" href="styleSalas.css">
    <title>Listagem de salas</title>
</head>
 
<body>
        <div>
            <h2>Monitoramento de Salas</h2>
            <table>
                <thead>
                    <tr>
                        <th>Sala</th>
                        <th>Qtd_maquinas</th>
                        <th>Anomalias</th>
                    </tr>
                </thead>
 
                <tbody>
                    <?php
                        include_once('mysqlConnection.php');
                        $a=1;
                        $stmt1 = $conn->prepare(
                                "SELECT * FROM sala");
                        $stmt1->execute();
                        $salas = $stmt1->fetchAll();
                        foreach($salas as $sala)
                        {
                    ?>
                    <tr>
                        <td>
                            <a href="/supervisor/sala.php?sala=<?php echo $sala['nome']; ?>"><?php echo $sala['nome']; ?></a>
                        </td>
                        <td>
                            <?php echo $sala['qtd_maquinas']; //faltando trigger?>
                        </td>
                        <?php 
                         $stmt_anomalias = $conn->prepare(
                            "
                            WITH diff1M AS
                            (SELECT id_maquina, count(velocidade) as c 
FROM  ( 
	SELECT velocidade, capacidade, id_maquina, ROW_NUMBER() OVER (PARTITION BY velocidade, capacidade, id_maquina) AS n 
	FROM memoria WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala) 
	EXCEPT 
	SELECT velocidade, capacidade, id_maquina, ROW_NUMBER() OVER (PARTITION BY velocidade, capacidade, id_maquina) AS n 
	FROM memoria_o WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala) 
	)h 
GROUP BY id_maquina),
                            diff2M AS
                            (SELECT id_maquina, count(velocidade) as c 
FROM  ( 
	SELECT velocidade, capacidade, id_maquina, ROW_NUMBER() OVER (PARTITION BY velocidade, capacidade, id_maquina) AS n 
	FROM memoria_o WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala) 
	EXCEPT 
	SELECT velocidade, capacidade, id_maquina, ROW_NUMBER() OVER (PARTITION BY velocidade, capacidade, id_maquina) AS n 
	FROM memoria WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala) 
	)h 
GROUP BY id_maquina),
                            countM AS
                            (
                                SELECT id_maquina, MAX(c) as c
                                FROM 
                                (
                                    SELECT * FROM diff1M d1
                                    UNION
                                    SELECT * FROM diff2M d2
                                ) g
                                GROUP BY id_maquina
                            ),
                            diff1V AS
                            (
                                SELECT id_maquina, count(nome) as c
				FROM  ( SELECT nome, id_maquina, ROW_NUMBER() OVER (PARTITION BY nome, id_maquina) AS n
					FROM video
					WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala)
					EXCEPT
					SELECT nome, id_maquina, ROW_NUMBER() OVER (PARTITION BY nome, id_maquina) AS n
					FROM video_o
					WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala)
      					)h
				GROUP BY id_maquina
                            
                            ),
                            diff2V AS
                            (
                                SELECT id_maquina, count(nome) as c
				FROM  ( SELECT nome, id_maquina, ROW_NUMBER() OVER (PARTITION BY nome, id_maquina) AS n
					FROM video_o
					WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala)
					EXCEPT
					SELECT nome, id_maquina, ROW_NUMBER() OVER (PARTITION BY nome, id_maquina) AS n
					FROM video
					WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala)
      					)h
				GROUP BY id_maquina
                            
                            ),
                            countV AS
                            (
                                SELECT id_maquina, MAX(c) as c
                                FROM 
                                (
                                    SELECT * FROM diff1V d1
                                    UNION
                                    SELECT * FROM diff2V d2
                                ) g
                                GROUP BY id_maquina
                            ),
                            diff1A AS
                            (SELECT id_maquina, count(nome) as c 
FROM  ( 
	SELECT nome, capacidade, id_maquina, ROW_NUMBER() OVER (PARTITION BY nome, capacidade, id_maquina) AS n 
	FROM armazenamento WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala) 
	EXCEPT 
	SELECT nome, capacidade, id_maquina, ROW_NUMBER() OVER (PARTITION BY nome, capacidade, id_maquina) AS n 
	FROM armazenamento_o WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala) 
	)h 
GROUP BY id_maquina),
                            diff2A AS
                            (SELECT id_maquina, count(nome) as c 
FROM  ( 
	SELECT nome, capacidade, id_maquina, ROW_NUMBER() OVER (PARTITION BY nome, capacidade, id_maquina) AS n 
	FROM armazenamento WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala) 
	EXCEPT 
	SELECT nome, capacidade, id_maquina, ROW_NUMBER() OVER (PARTITION BY nome, capacidade, id_maquina) AS n 
	FROM armazenamento_o WHERE id_maquina IN (SELECT id FROM maquina WHERE nome_sala=  :nsala) 
	)h 
GROUP BY id_maquina),
                            countA AS
                            (
                                SELECT id_maquina, MAX(c) as c
                                FROM 
                                (
                                    SELECT * FROM diff1A d1
                                    UNION
                                    SELECT * FROM diff2A d2
                                ) g
                                GROUP BY id_maquina
                            ),
                            countS AS
                            (
                                SELECT id_maquina, 0 AS c
                                FROM software um
                                WHERE id_maquina IN (SELECT id 
                                                    FROM maquina 
                                                    WHERE nome_sala=  :nsala
                                                    ) 
                                AND um.id NOT IN (SELECT id 
                                                  FROM software_o 
                                                  WHERE id_maquina IN (SELECT id 
                                                                       FROM maquina 
                                                                       WHERE nome_sala=  :nsala
                                                                       )
                                                  )
                                GROUP BY id_maquina                  
                            ),
                            countH AS
                            (
                                SELECT id as id_maquina,( (CASE WHEN ip_n=ip_o THEN 0 ELSE 1 END)+(CASE WHEN processador_n=processador_o THEN 0 ELSE 1 END)+(CASE WHEN nome_n=nome_o THEN 0 ELSE 1 END)+(CASE WHEN mouse>0 THEN 0 ELSE 1 END)+(CASE WHEN teclado>0 THEN 0 ELSE 1 END) ) as c
                                FROM maquina
                                WHERE nome_sala= :nsala
                            )
                            SELECT COALESCE(SUM(c),0) AS numero_anomalias
                            FROM (((SELECT * FROM countA UNION ALL SELECT * FROM countS) UNION ALL (SELECT * FROM countM UNION ALL SELECT * FROM countV)) UNION ALL SELECT * FROM countH)h
                            " );
                            $stmt_anomalias->execute([
                                ':nsala' => $sala['nome']
                              ]);
                              $num_anom = $stmt_anomalias->fetch();
                             // var_dump($num_anom);
                         ?>
                        <td>
                           <?php     echo $num_anom[0]   ?>
                        </td>
 
                      </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php  $stmtAt = $conn->prepare("SELECT last_executed FROM INFORMATION_SCHEMA.events where event_name like '%restante%'");
                        $stmtAt->execute();
                        $lastT = $stmtAt->fetch();?>
        <div style="padding:2em">
        Última atualização em: <?php echo $lastT['last_executed']?>
        </div>
</body>
 
</html>
