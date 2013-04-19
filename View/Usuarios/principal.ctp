<?php ?>
<style>
	#principal
	{
		margin: 10px auto;
		width: 800px;
		border: 1px solid #ddd;
		padding: 10px;
	}
	#principal ul
	{
		margin: 0px;
		padding: 0px;
		list-style: none;
	}
	#principal ul li
	{
		display: inline-block;
		margin: 0px 5px 0px 5px;
		padding: 10px;
		height: 80px;
		width: 80px;
		vertical-align: top;
		text-align: center;
	}
	#principal ul li a
	{
		display: block;
	}
	#principal ul li:hover
	{
		background-color: #b5d5fc;
	}
	#avisos
	{
		width: 70%;
		margin: 0px auto;
	}
	#avisos h4
	{
		margin: 0px;
	}
	#avisos p
	{
		margin: 0px;
		padding: 0px;
		line-height: 20px;
	}
</style>
<br /><br />
<center>Acesso Rápido</center>
<br />
<div id='principal'>
	
<ul>
<?php
	echo $this->Html->getLinkLI('/agenda/index','Agenda','logo_agendas.png');
	echo $this->Html->getLinkLI('/avisos/listar','Avisos','logo_mensagens.png');
	echo $this->Html->getLinkLI('/configuracoes/index','Configurações','logo_configuracoes.png');
	echo $this->Html->getLinkLI('/ferramentas/index','Ferramentas','logo_ferramentas.png');
	echo $this->Html->getLinkLI('/acessos/listar','Meus Acessos','logo_acessos.png');
	if (!Configure::read('login0800')) echo $this->Html->getLinkLI('/usuarios/meus_dados','Meus Dados','logo_perfil.png');
	echo $this->Html->getLinkLI('/perfis/listar','Perfis','logo_perfis.png');
	echo $this->Html->getLinkLI('/urls/listar','Permissões de Acesso','logo_urls.png');
	echo $this->Html->getLinkLI('/relatorios/index','Relatórios','logo_relatorios.png');
	echo $this->Html->getLinkLI('/ajuda/sobre_mim','Sobre Mim','sobre_mim.png');
	echo $this->Html->getLinkLI('/usuarios/listar','Usuários','logo_usuarios.png');
?>
</ul>
</div>

<?php if (count($avisos)) : ?>
<div id='avisos'>
<h3><center>Avisos</center></h3>
<ul>
	<?php foreach($avisos as $_l => $_arrMods) : ?>
	<p>- <?= $_arrMods['Aviso']['texto'] ?></p>
	<?php endforeach ?>

</ul>
<?php endif ?>
</div>
