CakeMongo

1 - Requisitos
mongoDB vrs. 2.4.1
PHP vrs. 5.1
Memcached vrs. 1.4 (opcional)

2 - Instalação

Coloque este código fonte no seu servidor web, algo como /var/www/cakemongo

Acesse http://localhost/cakemongo, e seguia a instruções que são basicamente:

Edite o arquivo APP/Config/core.php, e mude a linha:

Configure::write('login0800',false);

para

Configure::write('login0800',true);

Desta forma não será necessário login para acessar o sistema, então será possível
criar o usuário administrador bem como os perfis de usuários que o sistema deve possuir.

Depois do usuário administrador e perfis criados, edita novamente o arquivo APP/Config/core.php e 
altere "login0800" para false. 

Execute log-off e diverta-se.

