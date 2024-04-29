<!DOCTYPE html>
<html lang="en">
 <?php 
 
 $maquina = $_GET['maquina'];
 $sala = $_GET['sala'];
 include_once('mysqlConnection.php');
 ?>


<head>
<link rel="stylesheet" href="styleSala.css">
    <title><?php echo $maquina.": Softwares"?></title>
</head>

<body>
<h2> Softwares</h2>

<?php 
	//Softwares recem instalados
	$stmt1 = $conn->prepare("SELECT * FROM software WHERE id_maquina = :maquina AND id NOT IN 
				(SELECT id FROM software_o WHERE id_maquina = :maquina)
				ORDER BY nome");
	$stmt1->execute([':maquina' => $maquina]);
	$softwares_instalados = $stmt1->fetchAll();
	
	//Softwares recem removidos
	$stmt2 = $conn->prepare("SELECT * FROM software_o WHERE id_maquina = :maquina AND id NOT IN 
				(SELECT id FROM software WHERE id_maquina = :maquina)
				ORDER BY nome");
	$stmt2->execute([':maquina' => $maquina]);
	$softwares_removidos = $stmt2->fetchAll();
	
	//Softwares mantidos
	$stmt3 = $conn->prepare("SELECT * FROM software WHERE id_maquina = :maquina AND id IN 
				(SELECT id FROM software_o WHERE id_maquina = :maquina)
				ORDER BY nome");
	$stmt3->execute([':maquina' => $maquina]);
	$softwares_mantidos = $stmt3->fetchAll();			
	
?>
<div style="padding-top:20px;">   

	<?php echo 
	"<a href=/supervisor/ignorar.php?maquina=".$maquina."&conf=software&sala=".$sala."> 
	ignorar 
	</a>" 
	?>
</div>

<table>
	
	<p style="padding-left:1.2em;background-color: #04AA6D;">Softwares recém-instalados</p>
	<thead> 
	<tr> 
	<th> Nome</th>
	<th> Data da instalação </th>
	<th>Pasta</th>
	<th>Proprietário</th>
	</tr>
	</thead>
	
	<tbody>
	 <?php
            foreach($softwares_instalados as $software_i)
            {
          ?>
                <tr>
		<td> <?php echo $software_i['nome']?></td>
		<td> <?php echo $software_i['data_instalacao']?></td>
		<td> <?php echo $software_i['pasta']?></td>
		<td> <?php echo $software_i['proprietario']?></td>
		</tr>
          <?php 
             }
          ?>
         
         </tbody>    
</table>
<br><br>
<table>
	
	<p style="padding-left:1.2em;background-color: #04AA6D;">Softwares recém-removidos</p>
	<thead> 
	<tr> 
	<th> Nome</th>
	<th> Data da instalação </th>
	<th>Pasta</th>
	<th>Proprietário</th>
	</tr>
	</thead>
	
	<tbody>
	 <?php
            foreach($softwares_removidos as $software_r)
            {
          ?>
                <tr>
		<td> <?php echo $software_r['nome']?></td>
		<td> <?php echo $software_r['data_instalacao']?></td>
		<td> <?php echo $software_r['pasta']?></td>
		<td> <?php echo $software_r['proprietario']?></td>
		</tr>
          <?php 
             }
          ?>
         
         </tbody>    
</table>

<br><br>
<table>
	
	<p style="padding-left:1.2em; background-color: #04AA6D;">Softwares mantidos</p>
	<thead> 
	<tr> 
	<th> Nome</th>
	<th> Data da instalação </th>
	<th>Pasta</th>
	<th>Proprietário</th>
	</tr>
	</thead>
	
	<tbody>
	 <?php
            foreach($softwares_mantidos as $software_m)
            {
          ?>
                <tr>
		<td> <?php echo $software_m['nome']?></td>
		<td> <?php echo $software_m['data_instalacao']?></td>
		<td> <?php echo $software_m['pasta']?></td>
		<td> <?php echo $software_m['proprietario']?></td>
		</tr>
          <?php 
             }
          ?>
         
         </tbody>    
</table>
</body>
 
 
 
 
 
 <div style="padding-top:20px;">   
 <a href="/supervisor/sala.php?sala=<?php echo $sala; ?>" class="button2">VOLTAR</a>
 </div>
</html>
