<?php ?>
<style>
	.tdA
	{
		text-align: right;
		width: 200px;
		padding-right: 5px;
	}
</style>
<br /><br /><br /><br />
<table align='center' width='500' border='0px'>
	<tr>
		<td class='tdA'>Login:</td>
		<td><?= $this->Session->read('Usuario.login') ?></td>
	</tr>
	<tr>
		<td class='tdA'>Nome:</td>
		<td><?= $this->Session->read('Usuario.nome') ?></td>
	</tr>
	
	<tr>
		<td class='tdA'>Total de Acessos:</td>
		<td><?= $this->Session->read('Usuario.acessos') ?></td>
	</tr>
	
	<tr>
		<td class='tdA'>Perfil:</td>
		<td><?= $this->Session->read('Usuario.perfil') ?></td>
	</tr>
	
	<tr>
		<td class='tdA'>Ultimo IP:</td>
		<td><?= $this->Session->read('Usuario.ultimo_ip') ?></td>
	</tr>
	
	<tr>
		<td class='tdA'>Ultimo Acesso:</td>
		<td><?= $this->Session->read('Usuario.ultimo') ?></td>
	</tr>
	
</table>
