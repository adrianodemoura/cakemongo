<?php 
	$this->Html->script('editar', array('inline' => false));
	$this->Html->css('editar', null, array('inline' => false));

	$this->viewVars['edicaoCampos'] = isset($this->viewVars['edicaoCampos']) ? $this->viewVars['edicaoCampos'] : array();
	$this->viewVars['onRead'] .= "\t".'$("#btExcluir").click(function() { $("#form'.$modelClass.'").submit(); });'."\n";
	$this->viewVars['onRead'] .= "\t".'$("#btFechar").click(function() { document.location.href="'.$this->base.'/'.strtolower($this->name).'" });'."\n";
?>

<div id='barra'>
	<input type='button' name='btExcluir' id='btExcluir' value='Excluir' class='botoes' />
	<input type='button' name='btFechar'  id='btFechar' value='Fechar' class='botoes' />
</div>

<div id='divForm<?= $modelClass ?>' class='formEditar'>

	<center><span>Você tem certeza de excluir este registro ?</span></center>

	<?php
	
	echo $this->Form->create($modelClass, array('id'=>'form'.$modelClass,'type' => 'post'));
	echo $this->Form->text('_id',array('type'=>'hidden'));
	
	foreach($this->viewVars['edicaoCampos'] as $_cmp)
	{
		$a	= explode('.',$_cmp);
		$p = isset($schema[$a['1']]) ? $schema[$a['1']] : array();
		$p['input']['div']['id'] 	= 'div'.$this->Form->domid($_cmp);
		$p['input']['div']['class'] = 'editarCmp';
		$p['input']['readonly'] 	= 'readonly';

		// máscara
		if (isset($p['mascara'])) $this->viewVars['onRead'] .= "\t".'$("#'.$this->Form->domid($_cmp).'").mask("'.$p['mascara'].'")'."\n";

		echo $this->Form->input($a['1'],$p['input']);
	}

	echo $this->Form->end();
	?>

</div>
<?php //debug($this->data); ?>
