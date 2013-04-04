<?php 
	// descobrindo os campos indexáveis para a pesquisa
	if (!isset($camposPesquisa))
	{
		$camposPesquisa = array();
		foreach($schema as $_cmp => $_prop)
		{
			if ($_cmp != 'id' && isset($_prop['index']) && $_prop['length']>3 && !strpos($_cmp,'_id') )
			{
				array_push($camposPesquisa,$_cmp);
			}
		}
	}
	$ordem = $this->Session->read('listar.'.$chave.'.ordem');
?>
<style>
	#pesquisa
	{
		margin: 5px 0px 0px 10px;
		float: left;
		line-height: 30px;
	}
</style>

<?php if (!empty($camposPesquisa)) : ?>

<div id='pesquisa'>

	<label>Pesquisar</label>

	<select name='slPesquisa' id='slPesquisa'>
		<?php foreach($camposPesquisa as $_campo) : $selecao	= ''; $titulo = isset($schema[$_campo]['input']['label']) ? $schema[$_campo]['input']['label'] : ucfirst($_campo); ?>
		<?php if ($_campo==$ordem) $selecao='selected="selected"';	?>
		<option <?= $selecao ?>  value='<?= $_campo ?>'><?= $titulo ?></option>
		<?php endforeach ?>
	</select>

	<input type='text' name='edPesquisa' id='edPesquisa' autofocus />

	<div id="rePesquisa"></div>
	<?php 
		$this->viewVars['onRead'] .= "\t".'$("#edPesquisa").keyup(function(e){ setPesquisa("'.Router::url('/',true);
		if (!empty($this->request->params['plugin'])) $this->viewVars['onRead'] .= $this->request->params['plugin'].'/';
		$this->viewVars['onRead'] .= strtolower($this->name).'/pesquisar/'.'",(e.keyCode ? e.keyCode : e.which)); })'."\n";
	?>

</div>

<?php endif ?>
