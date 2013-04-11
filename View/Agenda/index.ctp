<?php
	$dias = $this->Html->getDiaSemanas();
	$this->viewVars['onRead'] .= "\t".'$("#AgendaMes").change(function() 
	{ 
		var mes = $(this).val(); 
		var ano = $("#AgendaAno").val(); 
		var url = "'.Router::url('/',true).'agenda/index/"+mes+"/"+ano;
		document.location.href=url;
	});'.";\n";
	$this->viewVars['onRead'] .= "\t".'$("#AgendaAno").change(function() 
	{ 
		var mes = $("#AgendaMes").val();
		var ano = $(this).val();
		var url = "'.Router::url('/',true).'agenda/index/"+mes+"/"+ano;
		document.location.href=url;
	});'."\n";
	/*$this->viewVars['onRead'] .= "\t".'$("td").click(function(e)
	{
		var dia = $(this).attr("id");
		if (dia)
		{
			var mes = $("#AgendaMes").val();
			var ano = $("#AgendaAno").val();
			var url = "'.Router::url('/',true).'agenda/editar/"+dia+"/"+mes+"/"+ano;
			document.location.href=url;
		}
	});'."\n";*/
	
?>
<style>
	.agenda
	{
		margin: 10px auto;
		width: 1000px;
	}
	.navAgenda
	{
		margin: 0px 0px 5px 10px;
	}
	.navAgenda ul
	{
		list-style: none;
		margin: 0px;
		padding: 0px;
	}
	.navAgenda ul li
	{
		display: inline-block;
		width: 50px;
		height: 20px;
		text-align: center;
		line-height: 20px;
		padding: 0px 5px 0px 5px;
	}
	.navAgenda ul li a
	{
		display: block;
		color: #000;
		background-color: #eee;
	}
	.navAgenda ul li a:hover
	{
		background-color: #B5D5FC;
	}
	.tabAgenda
	{
		margin: 0px auto;
	}
	.tabAgenda tr th
	{
		height: 20px;
		background-color: #C3D9FF;
		border: 1px solid #ccc;
		color: #666687;
	}
	.tabAgenda tr td
	{
		width: 150px;
		height: 90px;
		border: 1px solid #ccc;
		vertical-align: top;
	}
	.tabAgenda tr td:hover
	{
		cursor: pointer;
		background-color: #eee;
	}
	#nomeMes
	{
		width: 150px;
		text-align: left;
	}
	.hoje
	{
		background-color: #B5D5FC;
	}
	.celulaDia
	{
		color: #333;
	}
</style>

<div id='agenda' class='agenda'>

<div class='navAgenda'>
	<ul>
		<li><a href='<?= $linkA ?>'> < </a></li>
		<?php if ($mes!=(int) date('m') || $ano!=(int) date('Y')) : ?>
		<li><a href='<?= $linkH ?>'> Hoje </a></li>
		<?php else: ?>
		<li style='background-color: #eee; color: #ccc;'>Hoje</li>
		<?php endif ?>
		<li><a href='<?= $linkP ?>'> > </a></li>
		<li><?php echo $this->Form->input('Agenda.mes',array('value'=>$mes,'label'=>false,'type'=>'select','options'=>$meses)) ?></li>
		<li><?php echo $this->Form->input('Agenda.ano',array('value'=>$ano,'label'=>false,'type'=>'select','options'=>$anos)) ?></li>
		<li id='nomeMes'><span><?= utf8_encode(strftime("%B de %Y", strtotime("$ano-$mes-$dia"))); ?></span></li>
	</ul>
</div>

<table class='tabAgenda' cellpadding='0' cellspacing='0'>
	<tr>
<?php foreach($dias as $_n => $_nome) : ?>
	<th><?= $_nome ?></th>
<?php endforeach ?>
	</tr>

<?php foreach($calendario as $_semana => $_arrDias) : ?>
	<tr>
<?php foreach($_arrDias as $_idS => $_dia) : ?>
	<?php if ($_dia) : ?>
	<td	<?php if ($_dia==date('d') && $mes==date('m')) echo "class='hoje'" ?> id='<?= $_dia ?>'>
	<span class='celulaDia'><?= $_dia ?></span>
	</td>
	<?php else : ?>
	<td class='hojeNao'>-</td>
	<?php endif ?>

<?php endforeach ?>
	</tr>
<?php endforeach ?>

</table>

</div>
