<?php ?>
<style>
	.divBotoes
	{
		display: none;
	}
	#botoesM
	{
		width: 500px;
		float: left;
		margin: 0px 0px 0px 250px;
	}
	#btSalvarM
	{
		background: url(../img/bt_salvar.png) no-repeat;
	}
	#btAtualiM
	{
		background: url(../img/bt_atualizar.png) no-repeat;
	}
	#btFecharM
	{
		background: url(../img/bt_fechar.png) no-repeat;
	}
	.imgMenuLista
	{
		display: none;
	}
</style>
<div id='botoesM'>
<input type='button' name='btSalvar' id='btSalvarM' class='botoes' title='Clique aqui para salvar '/>
<input type='button' name='btAtuali' id='btAtualiM' class='botoes' title='Clique aqui para Atualizar '/>
<input type='button' name='btFechar' id='btFecharM' class='botoes' title='Clique aqui fechar' />
</div>

<?php
	// botão salvar
	$this->viewVars['onRead'] .= "\t".'$("#btSalvarM").click(function() { $("#form'.$modelClass.'").submit(); });'."\n";

	// botão atualizar
	$this->viewVars['onRead'] .= "\t".'$("#btAtualiM").click(function() 
	{ document.location.href="'.$this->base.'/'.strtolower($this->name).'/meus_dados" });'."\n";
	
	// botão fechar
	$this->viewVars['onRead'] .= "\t".'$("#btFecharM").click(function() 
	{ document.location.href="'.$this->base.'"});'."\n";

	$this->viewVars['edicaoCampos'] = array('Usuario.nome','Usuario.sexo','Usuario.aniversario','#',
	'Usuario.endereco','#',
	'Usuario.bairro','#',
	'Usuario.cidade','Usuario.cep','#',
	'Usuario.telResidencial','Usuario.telComercial','Usuario.celular','-',
	'Usuario.senha','Usuario.login','Usuario.perfil','-',
	'Usuario.modificado','Usuario.criado');
	$schema['perfil']['input']['readonly'] = 'readonly';
	$schema['login']['input']['readonly'] = 'readonly';
	$url = Router::url('/',true).'usuarios/meus_dados';
	require_once('../View/Scaffolds/editar.ctp');
?>
