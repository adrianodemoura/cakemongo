<?php

?>
<style>
	#iFer
	{
		margin: 10px 0px 0px 10px;
		width: 500px;
		line-height: 24px;
	}
</style>
<div id='iFer'>
<center><b>Ferramentas</b></center>
<ul>
	<?php echo $this->Html->getLinkLI('/ferramentas/limpar_cache','Limpar Cache') ?>
	<?php echo $this->Html->getLinkLI('/ferramentas/importar_csv','Importar CSV') ?>
</ul>

</div>
