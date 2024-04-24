# TRIGGERS
-Todas definidas no banco de dados "inventario" especificado em mysqlConnection.php

## trigger_sala
-Os computadores devem ter as expressões regulares especificadas nessa trigger em seus nomes (é necessário renomear computadores dos laboratórios de acordo).
-Uma sala presente na tabela 'Sala' no "inventario" --> Sala(nome, qtd_maquinas) <-- tem seu valor qtd_maquinas incrementado.

## trigger_sala_update
-Quando o nome de um computador é atualizado, ex: l07-m1 para l06-m20, o número de máquinas em cada sala precisa ser recontado. 

## Trigger Software
-No OCS, cada software é dado por (name_id, pub_id) que são chaves estrangeiras, ex: (1,2) = ("notepad", "microsoft"). 
-Antes de inserir um novo software em "inventario", trigger_software busca os valores de nome e pulisher no banco de dados do ocs(neste caso, ocsweb).
