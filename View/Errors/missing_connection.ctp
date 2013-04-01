<?php
 require_once(APP.DS.'Config'.DS.'database.php');
 $confDB = new DATABASE_CONFIG();
?>
<style>
	#erroBanco
	{
		margin: 50px auto;
		width: 600px;
	}
	#cmdBanco
	{
		line-height: 22px;
		color: red;
	}
</style>
<div id='erroBanco'>
<center>Erro ao tentar Acessar o Banco de Dados
<p>Solicite ao Administrador do Banco de Dados a executar:</p>
</center>
<pre id='cmdBanco'>
	create database <?= $confDB->default['database'] ?>;
	grant all privileges on <?= $confDB->default['database'] ?>.* to <?= $confDB->default['login'] ?>@<?= $confDB->default['host'] ?> identified by "<?= $confDB->default['password'] ?>" with grant option;
	flush privileges;
</pre>

</div>
