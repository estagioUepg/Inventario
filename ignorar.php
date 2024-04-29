<!DOCTYPE html>
<html lang="en">
 <?php 
 
 $maquina= $_GET['maquina']; $conf= $_GET['conf']; $sala= $_GET['sala'];?>

<head>
<link rel="stylesheet" href="styleSala.css">
    <title><?php echo $conf." alterado"?></title>
</head>

<body>
                    <?php
                        include_once('mysqlConnection.php'); 
                        $a=1;
                        if($conf=="ip"){
                            echo "<div style='font-family:Arial; font-size: 1.2em; padding-top:10px;'> Atualizando IP... </div>";
                            $stmtvid = $conn->prepare(
                                "UPDATE maquina 
                                SET ip_o = ip_n 
                                WHERE id = :m_id");
                                $stmtvid->execute([':m_id' => $maquina]);
                        }
                        if($conf=="nome"){
                            echo "<div style='font-family:Arial; font-size: 1.2em; padding-top:10px;'> Atualizando nome... </div>";
                            $stmtvid = $conn->prepare(
                                "UPDATE maquina 
                                SET nome_o = nome_n 
                                WHERE id = :m_id");
                                $stmtvid->execute([':m_id' => $maquina]);
                        }
                        if($conf=="processador"){
                            echo "<div style='font-family:Arial; font-size: 1.2em; padding-top:10px;'> Atualizando processador... </div>";
                            $stmtvid = $conn->prepare(
                                "UPDATE maquina 
                                SET processador_o = processador_n 
                                WHERE id = :m_id");
                                $stmtvid->execute([':m_id' => $maquina]);
                        }
                        if($conf=="memoria"){
                            echo "<div style='font-family:Arial; font-size: 1.2em; padding-top:10px;'> Atualizando memória... </div>";
                            $stmtdel = $conn->prepare(
                                "DELETE FROM memoria_o 
                                WHERE id_maquina = :m_id");
                                $stmtdel->execute([':m_id' => $maquina]);
                            $stmtfill = $conn->prepare(
                                "INSERT INTO memoria_o
                                SELECT *
                                FROM memoria
                                WHERE  id_maquina = :m_id
                                "
                            );
                            $stmtfill->execute([':m_id' => $maquina]);

                        }
                        if($conf=="video"){
                            echo "<div style='font-family:Arial; font-size: 1.2em; padding-top:10px;'> Atualizando vídeo... </div>";
                            $stmtdel = $conn->prepare(
                                "DELETE FROM video_o 
                                WHERE id_maquina = :m_id");
                                $stmtdel->execute([':m_id' => $maquina]);
                            $stmtfill = $conn->prepare(
                                "INSERT INTO video_o
                                SELECT *
                                FROM video
                                WHERE  id_maquina = :m_id
                                "
                            );
                            $stmtfill->execute([':m_id' => $maquina]);

                        }
                        if($conf=="armazenamento"){
                            echo "<div style='font-family:Arial; font-size: 1.2em; padding-top:10px;'> Atualizando armazenamento... </div>";
                            $stmtdel = $conn->prepare(
                                "DELETE FROM armazenamento_o 
                                WHERE id_maquina = :m_id");
                                $stmtdel->execute([':m_id' => $maquina]);
                            $stmtfill = $conn->prepare(
                                "INSERT INTO armazenamento_o
                                SELECT *
                                FROM armazenamento
                                WHERE  id_maquina = :m_id
                                "
                            );
                            $stmtfill->execute([':m_id' => $maquina]);

                        }
                        if($conf=="software"){
                            echo "<div style='font-family:Arial; font-size: 1.2em; padding-top:10px;'> Atualizando software... </div>";
                            $stmtdel = $conn->prepare(
                                "DELETE FROM software_o 
                                WHERE id_maquina = :m_id");
                                $stmtdel->execute([':m_id' => $maquina]);
                            $stmtfill = $conn->prepare(
                                "INSERT INTO software_o

                                SELECT *
                                FROM software
                                WHERE  id_maquina = :m_id
                                "
                            );
                            $stmtfill->execute([':m_id' => $maquina]);

                        }

                    ?>
    
    <form action="/supervisor/sala.php?sala=<?php echo $sala; ?>" method="post">
    <input type="hidden" value=<?php echo $maquina ?> name='maquinas' />
    <input type = "submit" name = "submit" value = "Voltar" class="button">
</form>
</div>
</body>
 
</html>
