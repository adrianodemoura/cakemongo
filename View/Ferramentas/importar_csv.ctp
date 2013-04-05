<?php
	$this->viewVars['onRead'] .= "\t".'$("#ImportaModel").focus();'."\n";
?>
<style>
	#importar
	{
		width: 600px;
		margin: 0px auto;
		border: 1px solid #ccc;
		padding: 20px 10px 10px 10px;
	}
	p
	{
		width: 600px;
		margin: 0px auto;
	}
	#importar label
	{
		width: 200px;
		text-align: right;
		margin: 0px 5px 0px 0px;
		display: block;
		float: left;
		line-height: 24px;
	}
	#ImportaArquivo
	{
	}
	.arquivo
	{
		padding: 10px;
		text-align: center;
	}
	.submit
	{
		text-align: center;
		padding: 10px;
	}
	#logo_csv
	{
		float: left;
		margin: 0px auto;
	}
	.msg
	{
		color: green;
		font-weight: bold;
	}
</style>
<script>
	function validaForm()
	{
		if ($("#ImportaModel").val()=='')
		{
			alert('O Model é de preenchimento obrigatório');
			$("#ImportaModel").focus();
			return false;
		}
		if ($("#ImportaLimite").val()=='')
		{
			alert('O Bloco/Limite é de preenchimento obrigatório');
			$("#ImportaLimite").focus();
			return false;
		}
		if ($("#ImportaArquivo").val()=='')
		{
			alert('O Arquivo é de preenchimento obrigatório');
			$("#ImportaArquivo").focus();
			return false;
		}
		return true;
	}
</script>
<br /><br />
<center>Escolha o arquivo a ser importado</center>
<br />
<p>
OBS:<br />
- O nome do arquivo deve ser o nome do documento a ser importado.<br />
- A primeira linha do arquivo deve conter os nome de cada atributo (coluna).<br />
- Atributos devem estar entre ";" (ponto e virgula)<br />
- Atributos do tipo array devem estar entre "{" (chaves).<br />
- Atributos do tipo data devem estar no formato brasileiro dd/mm/AAAA (dia,mês e ano).<br />
</p><br />
<div id='importar' class='redonda'>
	<?php if (isset($msg)) : ?>
	<p class='msg'>
	<?= $msg ?>
	</p>
	<?php endif ?>
	<img id='logo_csv' src='<?= $this->base ?>/img/logo_csv.png' />

	<?php
	echo $this->Form->create('Importa', array('onsubmit'=>'return validaForm();','name'=>'ImportaCsv','id'=>'ImportaCsv','type' => 'file'));

	echo $this->Form->input('model', 	array('label'=>'* Model a ser populado: ','div'=>array('class'=>'model'),'type' => 'text')).'<br />';
	echo $this->Form->input('limite', 	array('label'=>'* Bloco/Limite: ','div'=>array('class'=>'limite'),'type' => 'text','default'=>$limite));
	echo $this->Form->input('arquivo', array('label'=>'* arquivo: ','div'=>array('class'=>'arquivo'),'type' => 'file'));

	echo $this->Form->end('Enviar'); 
	?>
</div>
