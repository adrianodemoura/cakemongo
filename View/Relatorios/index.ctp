<?php

?>
<style>
	#iRel
	{
		margin: 10px 0px 0px 10px;
		width: 500px;
		line-height: 24px;
	}
</style>
<br />
<div id='iRel'>
<center><b>Relatórios</b></center>
<ul>
	<?php echo $this->Html->getLinkLI('/relatorios/rel001','Relatório Sintético de Cidade por Estado') ?>
	<?php echo $this->Html->getLinkLI('/relatorios/rel002','Relatório Sintético de Acessos por Usuário') ?>
	<?php echo $this->Html->getLinkLI('/relatorios/rel003','Relatório Sintético de Total de Usuários por Sexo') ?>
	<?php echo $this->Html->getLinkLI('/relatorios/rel004','Relatório Sintético de Total de Usuários por Perfil') ?>
</ul>

</div>
