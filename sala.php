<!DOCTYPE html>
<html lang="en">
 <?php 
 
 $sala_name = $_GET['sala']?>
<head>
<link rel="stylesheet" href="styleSala.css">
    <title><?php echo $sala_name?></title>
</head>

<body>
<h2><?php echo $sala_name?></h2>
        <div style="width: 100%; display: table; text-align: center; ">
        
            
        <div style="display: table-row">
            <div style="width: 600px; display: table-cell;">
                <form action="" method="post">
            <label for="maquinas">Todas as máquinas:</label>
            <select name="maquinas" id="maquinas">
            <?php
                        include_once('mysqlConnection.php');
                        $stmt1 = $conn->prepare(
                                "SELECT * FROM maquina WHERE nome_sala = :name_sala");
                        $stmt1->execute([':name_sala' => $sala_name]);
                        $maquinas = $stmt1->fetchAll();
                        foreach($maquinas as $maquina)
                        {
                    ?>
                <option value=<?php echo $maquina['id']?>> <?php echo $maquina['nome_n']?></option>
            <?php 
                        }?>
             
             </select>
             &ensp;&ensp;<input type = "submit" name = "submit" value = "ver detalhes" class="button">
             </form>  
            </div>
            <div style="display: table-cell;">
                <form action="" method="post">
            <label for="maquinas">Máquinas com Anomalias:</label>
            <select name="maquinas" id="maquinasA">
            <?php
                        $stmt2 = $conn->prepare(
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
                                    SELECT 0, count(*) AS c
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
                                    WHERE nome_sala= :nsala AND (ip_n<>ip_o OR processador_n<>processador_o OR nome_n<>nome_o OR mouse=0 OR teclado=0)
                                ) 
                                SELECT   * 
                                FROM maquina m
                                WHERE m.id in(SELECT id_maquina
                                            FROM (((SELECT * FROM countA UNION ALL SELECT * FROM countS) UNION ALL (SELECT * FROM countM UNION ALL SELECT * FROM countV)) UNION ALL SELECT * FROM countH)h
                                            )                             
                                ");
                        $stmt2->execute([':nsala' => $sala_name]);
                        $maquinasA = $stmt2->fetchAll();
                        foreach($maquinasA as $maquinaA)
                        {
                    ?>
                        <option value=<?php echo $maquinaA['id']?>> <?php echo $maquinaA['nome_n']?></option>
                <?php 
                        }?>
                
             </select>
             &ensp;&ensp;<input type = "submit" name = "submit" value = "ver detalhes" class="button">  
                    </form>
            </div>
        </div>    
        </div>


        <div>
        <?php  
        if(isset($_POST['submit'])){  
        //echo $_POST['submit'];
        //echo $_POST['maquinas'];
        if(!empty($_POST['maquinas'])) {
        //echo "maquinas not empty" ; 
            $selected = $_POST['maquinas'];  //selected = id da maquina
            ?>
            <div class="center">
                <table class="center">
                
                    <?php
                        $stmtm = $conn->prepare(
                                "SELECT * FROM maquina WHERE id = :m_id");    
                        $stmtm->execute([':m_id' => $selected]);
                        $maquina = $stmtm->fetch();

                    ?> 
                    
                    <thead><tr><th>Máquina: </th> <th><?php echo $maquina['nome_n']?></th></tr></thead>
                    <tbody>
                    <tr>
                            <td> IP: </td>
                            <td> <?php echo ($maquina['ip_n'] == $maquina['ip_o'])?$maquina['ip_n']: " <div style='color:red'> (1) Mudou de ".$maquina['ip_o']." para ".$maquina['ip_n']." <a href=/supervisor/ignorar.php?maquina=".$maquina['id']."&conf=ip&sala=".$sala_name."> ignorar </a>  </div>"?></td>
                    </tr>
                    <tr>
                            <td> Nome: </td>
                            <td id="nomemm"> <?php echo ($maquina['nome_n'] == $maquina['nome_o'])?$maquina['nome_n']: " <div style='color:red'> (1) Mudou de ".$maquina['nome_o']." para ".$maquina['nome_n']." <a href=/supervisor/ignorar.php?maquina=".$maquina['id']."&conf=nome&sala=".$sala_name."> ignorar </a>  </div>" ?></td>
                    </tr>
                    
                    <!-- select -->
                    <script type="text/javascript">
                    //Após submit - <select> reseta, para resolver...
                    //encontra indice da option correspondente a maquina selecionada
                    function findIndexfromOptionName( select, optionName ) 
                    {
		    	let options = Array.from( select.options );
		    	return options.findIndex( (opt) => opt.label == optionName );
		    }
		    	indice = findIndexfromOptionName(document.getElementById('maquinas'), document.getElementById('nomemm').innerHTML.trim());
		    	//seta a option correspondente a maquina selecionada
		    	document.getElementById('maquinas').selectedIndex = indice;
		    </script>
		    
                    <tr>
                            <td> OS: </td>
                            <td> <?php echo $maquina['os']?></td>
                    </tr>
                    <tr>
                            <td> Processador: </td>
                            <td> <?php echo ($maquina['processador_n'] == $maquina['processador_o'])?$maquina['processador_n']: "<div style='color:red'> (1) Mudou de ".$maquina['processador_o']." para ".$maquina['processador_n'] ." <a href=/supervisor/ignorar.php?maquina=".$maquina['id']."&conf=processador&sala=".$sala_name."> ignorar </a>  </div>" ?></td>
                    </tr>
                    <tr>
                            <td> Mouse: </td>
                            <td> <?php echo($maquina['mouse']>0)? "ok":"<div style='color:red'> (1) SEM MOUSE </div>"?></td>
                    </tr>
                    <tr>
                            <td> Teclado: </td>
                            <td> <?php echo($maquina['teclado']>0)? "ok":"<div style='color:red'> (1) SEM TECLADO </div>"?></td>
                    </tr>
                    <?php
                        //Deteca se há diferença entre old e new
                        $stmtmemdiff = $conn->prepare(
                            "
                            WITH diff1M AS
                                (
                                    SELECT count(velocidade) as c
                                    FROM
                                    (SELECT capacidade, velocidade, ROW_NUMBER() OVER (PARTITION BY capacidade, velocidade) AS n
                                    FROM memoria 
                                    WHERE id_maquina =:nmaq
                                    EXCEPT
                                    SELECT capacidade, velocidade, ROW_NUMBER() OVER (PARTITION BY capacidade, velocidade) AS n
                                    FROM memoria_o 
                                    WHERE id_maquina =:nmaq) h                              
                                ),
                                diff2M AS
                                (
                                    SELECT count(velocidade) as c
                                    FROM
                                    (SELECT capacidade, velocidade, ROW_NUMBER() OVER (PARTITION BY capacidade, velocidade) AS n
                                    FROM memoria_o 
                                    WHERE id_maquina =:nmaq
                                    EXCEPT
                                    SELECT capacidade, velocidade, ROW_NUMBER() OVER (PARTITION BY capacidade, velocidade) AS n
                                    FROM memoria
                                    WHERE id_maquina =:nmaq) h  
                                )
				SELECT MAX(c) as c
                                    FROM 
                                    (
                                        SELECT * FROM diff1M d1
                                        UNION
                                        SELECT * FROM diff2M d2
                                    )g
                            ");
                            $stmtmemdiff->execute([':nmaq' => $selected]);
                            $n_mudancas  = $stmtmemdiff->fetch();
                            //var_dump($n_mudancas['c']);
                        //Se houve mudancas, carrega duas tabelas
                        if($n_mudancas['c']>0){
                        $stmtmem = $conn->prepare(
                                "SELECT * FROM memoria WHERE id_maquina = :m_id");
                        $stmtmem_o = $conn->prepare(
                                "SELECT * FROM memoria_o WHERE id_maquina = :m_id");        
                        $stmtmem->execute([':m_id' => $selected]);
                        $stmtmem_o->execute([':m_id' => $selected]);
                        $memorias = $stmtmem->fetchAll();
                        $memorias_o = $stmtmem_o->fetchAll();
                        }
                        else
                        {   
                        $stmtmem = $conn->prepare(
                                "SELECT * FROM memoria WHERE id_maquina = :m_id");
                        $stmtmem->execute([':m_id' => $selected]);
                        $memorias = $stmtmem->fetchAll();
                        }       
                    ?>
                    <tr>
                            <td> Memórias: </td>
                            <?php if($n_mudancas['c']==0){ 
                                echo "<td>"?>  
                            <ul style="float: left;">                          
                                <?php foreach($memorias as $memoria)
                            {?> 
                             <li> <?php echo floor($memoria['capacidade']/1000)."GB/".$memoria['velocidade']."Mhz"?></li> 
                            <?php 
                            } ?>
                            </ul>
                            </td>
                            <?php } else{ echo "<td style='color:red'>  "?>
                            <ul style="float: left;">  
                            (<?php echo $n_mudancas['c']?>) Mudou de:                         
                                <?php foreach($memorias_o as $memoria_o)
                            {?> 
                             <li> <?php echo floor($memoria_o['capacidade']/1000)."GB/".$memoria_o['velocidade']."Mhz"?></li> 
                            <?php 
                            } ?>
                            </ul>
                            <ul style="float: left;">
                            Para:                          
                                <?php foreach($memorias as $memoria)
                            {?> 
                             <li> <?php echo floor($memoria['capacidade']/1000)."GB/".$memoria['velocidade']."Mhz"?></li> 
                            <?php 
                            } ?>
                            </ul> 
                            <ul style="float: left;">
                            <li style="padding-top: 1em"><a href=<?php echo "/supervisor/ignorar.php?maquina=".$maquina['id']."&conf=memoria&sala=".$sala_name;?>> Ignorar </a></li>
                            </ul>
                            </td>
                            <?php }?> 
                    </tr>
                    <?php
                        //Detecta se há diferença entre old e new
                        $stmtmemdiffA = $conn->prepare(
                            "
                            WITH diff1A AS
                                (
                                    SELECT count(nome) as c
                                    FROM
                                    (SELECT nome, capacidade, ROW_NUMBER() OVER (PARTITION BY nome, capacidade) AS n
                                    FROM armazenamento 
                                    WHERE id_maquina =:nmaq
                                    EXCEPT
                                    SELECT nome, capacidade, ROW_NUMBER() OVER (PARTITION BY nome, capacidade) AS n
                                    FROM armazenamento_o 
                                    WHERE id_maquina =:nmaq) h                              
                                ),
                                diff2A AS
                                (
                                    SELECT count(nome) as c
                                    FROM
                                    (SELECT nome, capacidade, ROW_NUMBER() OVER (PARTITION BY nome, capacidade) AS n
                                    FROM armazenamento_o 
                                    WHERE id_maquina =:nmaq
                                    EXCEPT
                                    SELECT nome, capacidade, ROW_NUMBER() OVER (PARTITION BY nome, capacidade) AS n
                                    FROM armazenamento
                                    WHERE id_maquina =:nmaq) h  
                                )
				                SELECT MAX(c) as c
                                    FROM 
                                    (
                                        SELECT * FROM diff1A d1
                                        UNION
                                        SELECT * FROM diff2A d2
                                    )g
                            ");
                            $stmtmemdiffA->execute([':nmaq' => $selected]);
                            $n_mudancasA  = $stmtmemdiffA->fetch();
                            //var_dump($n_mudancas['c']);
                        //Se houve mudancas, carrega duas tabelas
                        if($n_mudancasA['c']>0){
                        $stmtarm = $conn->prepare(
                                "SELECT * FROM armazenamento WHERE id_maquina = :m_id");
                        $stmtarm_o = $conn->prepare(
                                "SELECT * FROM armazenamento_o WHERE id_maquina = :m_id");        
                        $stmtarm->execute([':m_id' => $selected]);
                        $stmtarm_o->execute([':m_id' => $selected]);
                        $armazenamentos = $stmtarm->fetchAll();
                        $armazenamentos_o = $stmtarm_o->fetchAll();
                        }
                        else
                        {   
                        $stmtarm = $conn->prepare(
                                "SELECT * FROM armazenamento WHERE id_maquina = :m_id");
                        $stmtarm->execute([':m_id' => $selected]);
                        $armazenamentos = $stmtarm->fetchAll();
                        }       
                    ?>
                    <tr>
                            <td> Armazenamentos: </td>
                            <?php if($n_mudancasA['c']==0){ 
                                echo "<td>"?>  
                            <ul style="float: left;">                           
                                <?php foreach($armazenamentos as $armazenamento)
                            {?> 
                             <li> <?php echo floor($armazenamento['capacidade']/1000)."GB/".$armazenamento['nome']?></li> 
                            <?php 
                            } ?>
                            </ul>
                            </td>
                            <?php } else{ echo "<td style='color:red'>  "?>
                            <ul style="float: left;">  
                            (<?php echo $n_mudancasA['c']?>) Mudou de                        
                                <?php foreach($armazenamentos_o as $armazenamento_o)
                            {?> 
                             <li> <?php echo floor($armazenamento_o['capacidade']/1000)."GB/".$armazenamento_o['nome']?></li> 
                            <?php 
                            } ?>
                            </ul> 
                            <ul style="float: left;">
                            Para:                         
                                <?php foreach($armazenamentos as $armazenamento)
                            {?> 
                             <li> <?php echo floor($armazenamento['capacidade']/1000)."GB/".$armazenamento['nome']?></li> 
                            <?php 
                            } ?>
                            </ul> 
                            <ul style="float: left;">
                           <li style="padding-top: 1em"> <a href=<?php echo "/supervisor/ignorar.php?maquina=".$maquina['id']."&conf=armazenamento&sala=".$sala_name;?>> Ignorar </a></li>
                           </ul>
                            </td>
                            <?php }?>
                    </tr>
                    <?php
                        //Detecta se há diferença entre old e new
                        $stmtmemdiffV = $conn->prepare(
                            "
                            WITH diff1V AS
                                (
                                    SELECT count(nome) as c
                                    FROM
                                    (SELECT nome, ROW_NUMBER() OVER (PARTITION BY nome) AS n
                                    FROM video 
                                    WHERE id_maquina =:nmaq
                                    EXCEPT
                                    SELECT nome, ROW_NUMBER() OVER (PARTITION BY nome) AS n
                                    FROM video_o 
                                    WHERE id_maquina =:nmaq) h                              
                                ),
                                diff2V AS
                                (
                                    SELECT count(nome) as c
                                    FROM
                                    (SELECT nome, ROW_NUMBER() OVER (PARTITION BY nome) AS n
                                    FROM video_o 
                                    WHERE id_maquina =:nmaq
                                    EXCEPT
                                    SELECT nome, ROW_NUMBER() OVER (PARTITION BY nome) AS n
                                    FROM video
                                    WHERE id_maquina =:nmaq) h  
                                )
			    SELECT MAX(c) as c
                            FROM 
                            (
                             SELECT * 
                             FROM diff1V d1
                             UNION
                             SELECT * 
                             FROM diff2V d2
                             )g
                            ");
                            $stmtmemdiffV->execute([':nmaq' => $selected]);
                            $n_mudancasV  = $stmtmemdiffV->fetch();
                            //var_dump($n_mudancas['c']);
                        //Se houve mudancas, carrega duas tabelas
                        if($n_mudancasV['c']>0){
                        $stmtvid = $conn->prepare(
                                "SELECT * FROM video WHERE id_maquina = :m_id");
                        $stmtvid_o = $conn->prepare(
                                "SELECT * FROM video_o WHERE id_maquina = :m_id");        
                        $stmtvid->execute([':m_id' => $selected]);
                        $stmtvid_o->execute([':m_id' => $selected]);
                        $videos = $stmtvid->fetchAll();
                        $videos_o = $stmtvid_o->fetchAll();
                        }
                        else
                        {   
                        $stmtvid = $conn->prepare(
                                "SELECT * FROM video WHERE id_maquina = :m_id");
                        $stmtvid->execute([':m_id' => $selected]);
                        $videos = $stmtvid->fetchAll();
                        }       
                    ?>
                    <tr>
                            <td> Placas de vídeo: </td>
                            <?php if($n_mudancasV['c']==0){ 
                                echo "<td>"?>  
                            <ul>                           
                                <?php foreach($videos as $video)
                            {?> 
                             <li> <?php echo $video['nome']?></li> 
                            <?php 
                            } ?>
                            </ul>
                            </td>
                            <?php } else{ echo "<td style='color:red;'> "?>
                            
                            <ul style="float: left;">  
                            (<?php echo $n_mudancasV['c']?>) Mudou de:                         
                                <?php foreach($videos_o as $video_o)
                            {?> 
                             <li> <?php echo $video_o['nome']?></li> 
                            <?php 
                            } ?>
                            </ul> 
                            <ul style="float: left;">  
                            Para:                         
                                <?php foreach($videos as $video)
                            {?> 
                             <li> <?php echo $video['nome']?></li> 
                            <?php 
                            } ?>
                            </ul> 
			    <ul style="float: left;">  
                            <li style="padding-top: 1em"><a href=<?php echo "/supervisor/ignorar.php?maquina=".$maquina['id']."&conf=video&sala=".$sala_name;?>> Ignorar </a></li>
                            </ul>

                            </td>
                            <?php }?>
                    </tr>
                    <tr>
                            <td> Softwares: </td>
                            <td> <a href=<?php echo "/supervisor/software.php?maquina=".$maquina['id']."&conf=video&sala=".$sala_name;?>>Ver</a></td>
                    </tr>
                    </tbody>
                </table>
        </div>
        
        <?php
        } else {  
            echo 'Please select the value.';  
        }  
        }  
        ?>  
        </div>
        <p style="text-align:center; font-size: 1.5em; font-family: Arial, Helvetica, sans-serif;"><a href="/supervisor/salas.php" class="button2">Voltar</a></p>
</body>
 
</html>
