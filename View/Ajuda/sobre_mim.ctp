<?php ?>
<style>
	.tdA
	{
		text-align: right;
		width: 200px;
		padding-right: 5px;
	}
	#linkA
	{
		margin: 10px auto;
		width: 600px;
	}
</style>
<br />
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
<?php
	$urls	= Cache::read('urls'.$this->Session->read('Usuario.perfil'));
	if (!empty($urls)) :
?>
<div id='linkA'>
<b>Links de Acesso: </b>
<ul>
<?php foreach($urls as $_url => $_v) :  ?>
	
	<li><a href='<?= Router::url('/',true).$_url ?>'><?= Router::url('/',true).substr($_url,1,strlen($_url)) ?></a></li>
	
<?php
	endforeach;
?>

</ul>

</div>
<?php  endif; ?>
