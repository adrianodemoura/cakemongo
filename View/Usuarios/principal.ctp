<?php ?>
<style>
	#principal
	{
		margin: 10px auto;
		width: 470px;
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
</style>
<br /><br />
<center>Acesso Rápido</center>
<br />
<div id='principal'>
	
<ul>
<?php
	echo $this->Html->getLinkLI('/usuarios/listar','Usuários','logo_usuarios.png');
	echo $this->Html->getLinkLI('/ajuda/sobre_mim','Sobre Mim','sobre_mim.png');
	echo $this->Html->getLinkLI('/urls/listar','Permissões de Acesso','logo_urls.png');
	echo $this->Html->getLinkLI('/acessos/listar','Meus Acessos','logo_acessos.png');
	echo $this->Html->getLinkLI('/perfis/listar','Perfis','logo_perfis.png');
	echo $this->Html->getLinkLI('/ferramentas/index','Ferramentas','logo_ferramentas.png');
	echo $this->Html->getLinkLI('/relatorios/index','Relatórios','logo_relatorios.png');
	echo $this->Html->getLinkLI('/configuracoes/index','Configurações','logo_configuracoes.png');
	echo $this->Html->getLinkLI('/usuarios/meus_dados','Meus Dados','logo_perfil.png');
?>
</ul>
</div>
