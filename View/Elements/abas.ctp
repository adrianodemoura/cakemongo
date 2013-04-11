<?php 
	if ($this->Html->getLink('/acessos/listar')) 	$abas['Acessos'] 	= Router::url('/',true).'acessos/listar';
	if ($this->Html->getLink('/agenda/listar')) 	$abas['Agenda'] 	= Router::url('/',true).'agenda/listar';
	if ($this->Html->getLink('/avisos/listar')) 	$abas['Avisos'] 	= Router::url('/',true).'avisos/listar';
	if ($this->Html->getLink('/cidades/listar')) 	$abas['Cidades'] 	= Router::url('/',true).'cidades/listar';
	if ($this->Html->getLink('/perfis/listar'))		$abas['Perfis'] 	= Router::url('/',true).'perfis/listar';
	if ($this->Html->getLink('/urls/listar'))		$abas['Permissões'] = Router::url('/',true).'urls/listar';
	if ($this->Html->getLink('/usuarios/listar'))	$abas['Usuários'] 	= Router::url('/',true).'usuarios/listar';
?>

<div class='abas'>

	<ul>
		<?php foreach($abas as $_tit => $_url) : ?>
			<li <?php if (strpos($_url,strtolower($this->name))) echo 'class="ativa"' ?> ><a href='<?= $_url ?>'><?= $_tit ?></a></li>
		<?php endforeach ?>
	</ul>

</div>
