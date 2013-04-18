<?php
	if ($this->Session->check('Popular'))
	{
		$this->viewVars['onRead'] .= "\t".'setTimeout(function(){ document.location.href="'.$this->here.'" },1000);'."\t";
	}
?>
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
	label
	{
		display: block;
		float: left;
		width: 190px;
		text-align: right;
		margin: 0px 5px 0px 0px;
		line-height: 24px;
		font-weight: bold;
	}
	.divLabel
	{
		height: 30px;
		line-height: 24px;
	}
</style>

<div id='popular'>
	<?php if (!$arrProp || empty($arrProp)) : ?>
	<?php echo $this->Form->create('Popular', array('onsubmit'=>'return validaForm();','name'=>'ImportaCsv','id'=>'ImportaCsv','type' => 'file')); ?>
	<?php
		echo $this->Form->input('model', 	array('label'=>'* Model a ser populado: ','div'=>array('class'=>'model'),'type' => 'text')).'<br />';
		echo $this->Form->input('total', 	array('label'=>'* Total de documentos: ','div'=>array('class'=>'total'),'type' => 'text','default'=>100));
		echo $this->Form->input('loop', 	array('label'=>'* Loop: ','div'=>array('class'=>'loop'),'type' => 'text','default'=>10));
	?>
	<?php echo $this->Form->end('Enviar');  ?>
	<?php else : ?>
	<div class='divLabel'><label>Model: </label><?= $arrProp['Model'] ?></div>
	<div class='divLabel'><label>Loop: </label><?= $arrProp['loop'] ?></div>
	<div class='divLabel' style='color: green;'><label>Total: </label><?= number_format($arrProp['total'],0,',','.') ?></div>
	<div class='divLabel'><label>Feito: </label><b><?= number_format($arrProp['feito'],0,',','.') ?></b></div>
	<br />
	<div class='divLabel'><label>Próximo Documento: </label><?= number_format($arrProp['id'],0,',','.') ?></div>
	<div class='divLabel'><label>Início: </label><?= date('d/m/Y H:i:s',$arrProp['inicio']) ?></div>
	<?php if (isset($arrProp['fim'])) : ?>
	<div class='divLabel'><label>Fim: </label><?= date('d/m/Y H:i:s',$arrProp['fim']) ?></div>
	<div class='divLabel'><label>Tempo Corrido: </label><?= date('i:s',$arrProp['fim']-$arrProp['inicio']) ?> minutos</div>
	<?php else : ?>
	<div class='divLabel' style='color: red;'><center>Aguenta a mão aí, que eu tô populando !!!</center></div>
	<?php endif ?>
	
	<?php endif ?>
</div>

