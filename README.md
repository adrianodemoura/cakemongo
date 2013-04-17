CakeMongo

1 - Requisitos
mongoDB vrs. 2.4.1
PHP vrs. 5.1
Memcached vrs. 1.4 (opcional)

2 - Instalação

Coloque este código fonte no seu servidor web, algo como /var/www/cakemongo

Acesse http://localhost/cakemongo, e sigua as instruções de instalação, que são basicamente:

- Criar o banco de dados, com usuário e senha
- Criar o perfil "ADMINISTRADOR"
- Criar o usuário administrador com o login "admin" e senha "admin67"

Recomendamos ainda que execute a importação do Cadastro de Cidades, acessando "Ferramentas/Importar CSV"
o CSV de cidade se encontra em APP/Docs/Csv/cidades.csv

Você também pode popular qualquer collection, acesse "Ferramentas/Popular Coleção", informe o Model (collection)
que gostaria de popular, exemplo:

Model a ser populado: Agenda
Total de documentos: 1000000
Loop: 100
* neste exemplo serão incluídas (1000.000/100=10.000) dez mil documentos por vez.

Execute o login e divirta-se.

