<?php 
	// valor do campo id
	$id = isset($this->data[$modelClass]['_id']) ? $this->data[$modelClass]['_id'] : 0;

	// url
	if (!isset($url))
	{
		$url = Router::url('/',true); 
		if (!empty($this->plugin)) $url .= strtolower($this->plugin).'/';
		$url .= strtolower($this->name).'/salvar';
		$this->Html->css('menu_listar', null, array('inline' => false));
		$this->viewVars['onRead'] .= "\t".'$(".formEditar").click(function() { $(".divMenuLista").fadeOut(); });'."\n";
		// botão salvar
		$this->viewVars['onRead'] .= "\t".'$("#btSalvar").click(function() { $("#form'.$modelClass.'").submit(); });'."\n";
		// botão fechar
		$this->viewVars['onRead'] .= "\t".'$("#btFechar").click(function() { document.location.href="'.$this->base.'/'.strtolower($this->name).'/listar" });'."\n";
		// botão atualizar
		$this->viewVars['onRead'] .= "\t".'$("#btAtuali").click(function() 
		{ document.location.href="'.$this->base.'/'.strtolower($this->name).'/editar/'.$id.'" });'."\n";
	}

	// meta
	$this->Html->script('editar', array('inline' => false));
	$this->Html->css('editar', null, array('inline' => false));

	// campos que serão editados
	$this->viewVars['edicaoCampos'] = isset($this->viewVars['edicaoCampos']) ? $this->viewVars['edicaoCampos'] : array();

	// primeiro focu
	if (isset($focus)) $this->viewVars['onRead'] .= "\t".'$("#'.$this->Form->domId($focus).'").focus();'."\n";

?>

<div class='barra'>
	<?php if ($id && isset($opcMenuLista)) echo $this->element('menu_lista',array('id'=>$id)) ?>
	<div class='divBotoes'>
		<?php if ($this->Html->getLink('/'.strtolower($this->name).'/salvar') && !isset($soLeitura)) : ?>
		<input type='button' name='btSalvar' id='btSalvar' class='botoes' title='Clique aqui para salvar '/>
		<?php endif ?>
		<input type='button' name='btAtuali' id='btAtuali' class='botoes' title='Clique aqui para Atualizar '/>
		<input type='button' name='btFechar' id='btFechar' class='botoes' title='Clique aqui para salvar' />
	</div>

</div>

<div id='divForm<?= $modelClass ?>' class='formEditar'>

<div class='campos'>
	<form id='form<?= $modelClass ?>' method='post' action='<?= $url ?>'>
	<?php
	echo $this->Form->text($modelClass.'._id',array('type'=>'hidden'));
	foreach($this->viewVars['edicaoCampos'] as $_cmp)
	{
		if (strpos($_cmp,'.'))
		{
			$a	= explode('.',$_cmp);
			$p = isset($schema[$a['1']]) ? $schema[$a['1']] : array();
			$o = ''; // input busca
			$m = false; // input multiplos

			// div e class
			$p['input']['div']['id'] 	= 'div'.$this->Form->domId($_cmp);
			$p['input']['div']['class'] = 'editarCmp';
			$p['input']['type']			= isset($p['input']['type']) ? $p['input']['type'] : 'text';

			// nulo ?
			if (isset($p['null']) && $p['null']==false) $p['input']['label'] = '* '.$p['input']['label'];

			// label
			if (isset($p['input']['label'])) $p['input']['label'] .= ': ';

			// select
			if (isset($p['input']['options'])) $p['input']['type'] = 'select';

			// máscara
			if (isset($p['mascara'])) $this->viewVars['onRead'] .= "\t".'$("#'.$this->Form->domId($_cmp).'").mask("'.$p['mascara'].'")'."\n";

			// opções de relacionamento por busca ajax
			if (isset($p['input']['options']) && !is_array($p['input']['options']))
			{
				$p['input']['type'] 	= 'text';
				$o = $p['input']['options'];
			}

			// opções de relacionamento por multiplos valores
			if (isset($p['tipo']) && $p['tipo']=='multiplo')
			{
				$m = $p['input']['options'];

				// procura opções na view
				if (isset($this->viewVars[$m])) 
					$p['input']['options'] = $this->viewVars[$m];
				
				// procura opções no cache
				if (is_string($p['input']['options'])) 
					$p['input']['options'] = Cache::read($m);
				
				unset($p['input']['type']);
				$p['input']['multiple'] = 'checkbox';
			}

			// se é text mas ainda tem options, entronsi removi o bicho.
			if (isset($p['input']['type']) && $p['input']['type']=='text') unset($p['input']['options']);

			// se desligou tudo
			if (isset($soLeitura)) $p['input']['readonly'] = 'readonly';
	
			// escrevendo o input padrão do registro
			echo $this->Form->input($a['0'].'.'.$a['1'],$p['input']);
			
			// criando divReposta do campo para busca ajax
			if (!empty($o) && !isset($p['input']['readonly']))
			{
				echo '<div id="re'.$this->Form->domId($_cmp).'" class="divBusca">'."\n".'</div>'."\n";
				$this->viewVars['onRead'] .= "\t".'$("#'.$this->Form->domId($_cmp).'").keyup(function(e) { setBusca("'.$this->Form->domId($_cmp).'","'.$o.'",(e.keyCode ? e.keyCode : e.which)); })'."\n";
				$this->viewVars['onRead'] .= "\t".'$("#re'.$this->Form->domId($_cmp).'").click(function() 
				{ 
					var t = "";
					$("#re'.$this->Form->domId($_cmp).' li").live("click", function(event) 
					{
						$("#'.$this->Form->domId($_cmp).'").val($(this).text());
					 });
					$(this).fadeOut();
					$("#'.$this->Form->domId($_cmp).'").focus();
				})'."\n";
			}
			echo "\n\n";
			
		} elseif($_cmp=='#') echo '<div class="editarCmpQ"></div>'."\n";
		elseif($_cmp=='-') echo '<div class="editarCmpL"></div>'."\n";
	}

	?>
	</form>
</div>
</div>
<?php
	if(isset($this->validationErrors))
	{
		foreach($this->validationErrors as $_mod => $_arrCmps)
		{
			foreach($_arrCmps as $_cmp => $_erro)
			{
				$this->viewVars['onRead'] .= "\t".'$("#'.$this->Form->domId($_mod.'.'.$_cmp).'").focus();'."\n";
			}
		}
	}
?>
