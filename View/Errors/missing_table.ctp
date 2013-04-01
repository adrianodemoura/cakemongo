<?php ?>
<style>
	#erroTab
	{
		margin: 50px auto;
		width: 500px;
	}
	em
	{
		font-weight: bold;
</style>
<div id='erroTab'>
<center>
<h2><?php echo __d('cake_dev', 'Ausência de Tabela no Banco de Dados'); ?></h2>
</center>
<br /><br />
<p class="error">
	<strong><?php echo __d('cake_dev', 'Erro'); ?>: </strong><br />
	<?php echo __d('cake_dev', 'Tabela %1$s do Model %2$s não foi localizada no banco de dados %3$s.', '<em>' . $table . '</em>',  '<em>' . $class . '</em>', '<em>' . $ds . '</em>'); ?>
</p>
<br /><br />
<p class="notice">
	Talvez seja necessário instalar o módulo correspondente, contacte o Administrador do Sistema.
</p>

</div>
