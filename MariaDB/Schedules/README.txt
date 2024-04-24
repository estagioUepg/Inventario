# Tarefas
-Ambas devem ser definidas dentro do Banco de Dados gerado pelo OCS (durante instalação do OCS Inventory)
-O banco de dados "inventario" deve ser criado. É o mesmo banco de dados especificado no arquivo 'Inventario/mysqlConnection.php'

## Insere Maquinas
- Busca novas máquinas nas tabelas do OCS e insere nas tabelas de "Invetario". Atualiza máquinas já presentes em "Invenaio"

## Insere Restante
- Deleta recursos de cada máquina e reinsere. Ex: Cada máquina possui duas entradas: Video_n e Video_o. Video_n é atualizado contendo as novas placas de vídeo da máquina, que são comparadas às placas de vídeo em Video_o para identificar inconsistências.
