<?php ?>
<script>
	function validaForm()
	{
		if ($("#PopularModel").val()=='')
		{
			$("#PopularModel").focus();
			alert('O Campo Model é de preenchimento obrigatório');
			return false;
		}
		if ($("#PopularTotal").val()=='')
		{
			$("#PopularTotal").focus();
			alert('O Campo Total é de preenchimento obrigatório');
			return false;
		}
		return true;
	}
</script>
<style>
	#popular
	{
		margin: 50px auto;
		width: 600px;
	}
</style>

<div id='popular'>
	<?php if (!$this->Session->read('Popular')) : ?>
	<?php echo $this->Form->create('Popular', array('onsubmit'=>'return validaForm();','name'=>'ImportaCsv','id'=>'ImportaCsv','type' => 'file')); ?>
	<?php
		echo $this->Form->input('model', 	array('label'=>'* Model a ser populado: ','div'=>array('class'=>'model'),'type' => 'text')).'<br />';
		echo $this->Form->input('total', 	array('label'=>'* Total de documentos: ','div'=>array('class'=>'total'),'type' => 'text','default'=>100));
		echo $this->Form->input('loop', 	array('label'=>'* Loop: ','div'=>array('class'=>'loop'),'type' => 'text','default'=>10));
	?>
	<?php echo $this->Form->end('Enviar');  ?>
	<?php else : ?>
	<?php
		debug($this->Session->read('Popular'));
		debug($this->Session->read('Popular.feito'));
	?>
	<?php endif ?>
</div>
