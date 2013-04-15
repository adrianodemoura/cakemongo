<?php 
	// valor do campo id
	$id = isset($this->data[$modelClass][$primaryKey]) ? $this->data[$modelClass][$primaryKey] : 0;

	// url
	if (!isset($url))
	{
		$url = Router::url('/',true); 
		if (!empty($this->plugin)) $url .= strtolower($this->plugin).'/';
		$url .= strtolower($this->name).'/salvar';
		$this->Html->css('menu_listar', null, array('inline' => false));
		$this->viewVars['onRead'] .= "\t".'$(".formEditar").click(function() { $(".divMenuLista").fadeOut(); });'."\n";
		if ($id)
		{
			// botão novo
			$this->viewVars['onRead'] .= "\t".'$("#btNovo").click(function() { document.location.href="'.$this->base.'/'.strtolower($this->name).'/editar/0" });'."\n";
		}
		// botão salvar
		$this->viewVars['onRead'] .= "\t".'$("#btSalvar").click(function() { $("#form'.$modelClass.'").submit(); });'."\n";
		if ($id)
		{
			// botão novo
			$this->viewVars['onRead'] .= "\t".'$("#btExcluir").click(function() { if (confirm("Tem certeza em excluir este registro?")) document.location.href="'.$this->base.'/'.strtolower($this->name).'/excluir/'.$id.'" });'."\n";
		}
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
	if (empty($id)) // removendo criado e modificado na inclusão
	{
		if (in_array($modelClass.'.criado',$this->viewVars['edicaoCampos']))
		{
			unset($this->viewVars['edicaoCampos'][array_search($modelClass.'.criado',$this->viewVars['edicaoCampos'])]);
			unset($this->viewVars['edicaoCampos'][array_search($modelClass.'.modificado',$this->viewVars['edicaoCampos'])]);
			$ul = count($this->viewVars['edicaoCampos']);
			if ($this->viewVars['edicaoCampos'][$ul-1]=='-' || $this->viewVars['edicaoCampos'][$ul-1]=='#')
			{
				unset($this->viewVars['edicaoCampos'][$ul-1]);
			}
			echo 'oi';
		}
	}

	// se o campo modificado está vazio, carca fora do campos de edição
	if ($this->viewVars['edicaoCampos'][array_search($modelClass.'.modificado',$this->viewVars['edicaoCampos'])])
	{
		if (isset($schema['modificado']) && !isset($this->data[$modelClass]['modificado']))
		{
			unset($this->viewVars['edicaoCampos'][array_search($modelClass.'.modificado',$this->viewVars['edicaoCampos'])]);
		}
	}

	// primeiro focu
	if (isset($focus)) $this->viewVars['onRead'] .= "\t".'$("#'.$this->Form->domId($focus).'").focus();'."\n";

?>

<div class='barra'>
	<?php if ($id && isset($opcMenuLista)) echo $this->element('menu_lista',array('id'=>$id)) ?>
	<div class='divBotoes'>
		<?php if ($this->Html->getLink('/'.strtolower($this->name).'/salvar') && !isset($soLeitura)) : ?>
		<?php if ($id) : ?>
		<input type='button' name='btNovo' 	id='btNovo' class='botoes' title='Clique aqui para incluir um novo registro '/>
		<input type='button' name='btExcluir'  id='btExcluir' class='botoes' onclick='return false;' title='Clique aqui para excluir o registro '/>
		<?php endif ?>
		<input type='button' name='btSalvar' id='btSalvar' class='botoes' title='Clique aqui para salvar '/>
		<?php endif ?>
		<?php if ($id) : ?>
		<input type='button' name='btAtuali' id='btAtuali' class='botoes' title='Clique aqui para Atualizar '/>
		<?php endif ?>
		<input type='button' name='btFechar' id='btFechar' class='botoes' title='Clique aqui para salvar' />
	</div>

	<?php if ($id && isset($vizinhos)) echo $this->element('paginacao_edicao'); ?>

</div>

<div id='divForm<?= $modelClass ?>' class='formEditar'>

<div class='campos'>
	<form id='form<?= $modelClass ?>' method='post' action='<?= $url ?>'>
	<?php
	echo $this->Form->text($modelClass.'.'.$primaryKey,array('type'=>'hidden'));
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
				if (isset($this->viewVars[$p['input']['options']]))
				{
					$p['input']['type']		= 'select';
					$p['input']['options']	= $this->viewVars[$p['input']['options']];
				} else
				{
					$p['input']['type'] 	= 'text';
					$o = $p['input']['options'];
				}
			}

			// opções de relacionamento por multiplos valores
			if (isset($p['tipo']) && $p['tipo']=='multiplo')
			{
				$m = isset($p['input']['options']) ? $p['input']['options'] : array();

				// procura opções na view
				if (!empty($m)) $p['input']['options'] = $m;

				// procura opções no cache
				if (is_string($p['input']['options'])) $p['input']['options'] = Cache::read($m);

				unset($p['input']['type']);
				$p['input']['multiple'] = 'checkbox';
			}

			// se é text mas ainda tem options, entronsi removi o bicho.
			if (isset($p['input']['type']) && $p['input']['type']=='text') unset($p['input']['options']);

			// se desligou tudo
			if (isset($soLeitura)) $p['input']['readonly'] = 'readonly';
	
			// escrevendo o input padrão do registro
			if (isset($p['tipo']) && in_array($p['tipo'],array('data','datatempo')) && !isset($p['input']['readonly']))
			{
				$vlr = $this->data[$a['0']][$a['1']];
				$p['input']['type'] 	= 'select';
				$p['input']['options'] 	= $dias;
				$p['input']['value'] 	= substr($vlr,0,2);
				echo $this->Form->input($a['0'].'.'.$a['1'].'.dia',$p['input']);

				$p['input']['label'] 	= false;
				$p['input']['options'] 	= $meses;
				$p['input']['value'] 	= substr($vlr,3,2);
				echo $this->Form->input($a['0'].'.'.$a['1'].'.mes',$p['input']);

				$p['input']['value'] 	= substr($vlr,6,4);
				$p['input']['options'] 	= $anos;
				echo $this->Form->input($a['0'].'.'.$a['1'].'.ano',$p['input']);
				
				if ($p['tipo']=='datatempo')
				{
					$p['input']['value'] 	= substr($vlr,11,2);
					$p['input']['options'] 	= $horas;
					echo $this->Form->input($a['0'].'.'.$a['1'].'.hora',$p['input']);
					
					$p['input']['value'] 	= substr($vlr,14,2);
					$p['input']['options'] 	= $minutos;
					echo $this->Form->input($a['0'].'.'.$a['1'].'.minu',$p['input']);
				}
			} else echo $this->Form->input($a['0'].'.'.$a['1'],$p['input']);
			
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
	//debug($this->data);
	//debug($this->validationErrors);
?>
