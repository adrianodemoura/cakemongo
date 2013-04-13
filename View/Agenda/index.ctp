<?php
	$dias = $this->Html->getDiaSemanas();
	$this->viewVars['onRead'] .= "\t".'$("#AgendaMmes").change(function() 
	{ 
		var mes = $(this).val(); 
		var ano = $("#AgendaAano").val(); 
		var url = "'.Router::url('/',true).'agenda/index/"+mes+"/"+ano;
		document.location.href=url;
	});'.";\n";
	$this->viewVars['onRead'] .= "\t".'$("#AgendaAano").change(function() 
	{ 
		var mes = $("#AgendaMmes").val();
		var ano = $(this).val();
		var url = "'.Router::url('/',true).'agenda/index/"+mes+"/"+ano;
		document.location.href=url;
	});'."\n";
	$this->viewVars['onRead'] .= "\t".'$(".celNovo").click(function() 
	{ 
		var id = $(this).attr("id").replace("celNovo","");
		id = id.replace("id","");
		setEvento(""+id+"");
	});'."\n";
	$this->viewVars['onRead'] .= "\t".'$("#formEvento").submit(function() 
	{ 
		if ($("#AgendaEvento").val()=="")
		{
			alert("Evento inválido !!!");
			$("#AgendaEvento").focus();
			return false;
		}
		if ($("#AgendaHora").val()=="0")
		{
			alert("Hora inválida !!!");
			$("#AgendaHora").focus();
			return false;
		}
		if ($("#AgendaMinu").val()=="0")
		{
			alert("Minuto inválido !!!");
			$("#AgendaMinu").focus();
			return false;
		}
		return true;
	});'."\n";
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
		font-size: 10px;
	}
	.tabAgenda tr td div
	{
		float: left;
	}
	#nomeMes
	{
		width: 150px;
		text-align: left;
		margin: 0px 0px 0px 10px;
		text-transform: uppercase;
		display: none;
	}
	.hoje
	{
		background-color: #B5D5FC;
	}
	.celulaDia
	{
		color: #333;
		display: block;
		float: right;
		/*background-color: #ddd;*/
		width: 30px;
		text-align: center;
	}
	.celulaDia a
	{
			display: block;
	}
	.celulaDia a:hover
	{
		background-color: #B5D5FC;
	}
	#evento
	{
		background-color: #fff;
		width: 600px;
		height: 200px;
		border: 1px solid #ccc;
		display: none;
		position: absolute;
		top: 30%;
		left: 45%;
		margin-left: -200px;
		z-index: 1001;
		border-radius: 8px; /* CSS 3 */
		-o-border-radius: 8px; /* Opera */
		-icab-border-radius: 8px; /* iCab */
		-khtml-border-radius: 8px; /* Konqueror */
		-moz-border-radius: 8px; /* Firefox */
		-webkit-border-radius: 8px; /* Safari */
	}
	#evento label
	{
		display: block;
		float: left;
		width: 90px;
		text-align: right;
		margin: 0px 5px 0px 0px;
		line-height: 24px;
	}
	#evento #AgendaEvento,
	#evento #AgendaId
	{
		width: 400px;
	}
	#evento #evSalvar,
	#evento #evExcluir
	{
		border: none;
		padding: 5px;
		background-color: #eee;
		text-align: center;
		margin: 0px auto;
		cursor: pointer;
	}
	#evFechar
	{
		width: 20px;
		display: block;
		/*background-color: #B5D5FC;*/
		width: 100%;
		text-align: right;
		cursor: pointer;
		height: 46px;
		line-height: 40px;
	}
	#evCampos
	{
		padding: 10px;
	}
	.agId
	{
		display: none;
	}
	.agHora
	{
		display: block;
		width: 40px;
		text-align: center;
	}
	#evBotoes
	{
		text-align: center;
	}
	#AgendaDia,
	#AgendaMes,
	#AgendaAno
	{
		width: 50px;
	}
	.agEvento
	{
		display: none;
	}
	.celNovo
	{
		display: table;
		width: 100%;
		height: 85%;
	}
	.celNovo:hover
	{
		background-color: #eee;
		cursor: pointer;
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
		<li style='width: 100px;'><?php echo $this->Form->input('Agenda.mmes',array('value'=>$mes,'label'=>false,'type'=>'select','options'=>$meses)) ?></li>
		<li><?php echo $this->Form->input('Agenda.aano',array('value'=>$ano,'label'=>false,'type'=>'select','options'=>$anos)) ?></li>
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
<?php foreach($_arrDias as $_idS => $_arrProp) : ?>
	<?php if ($_arrProp['dia']) : ?>
	<td	<?php if ($_arrProp['dia']==date('d') && $mes==date('m') && $ano==date('Y')) echo "class='hoje'" ?>>
		<span class='celulaDia'>
			<a href='' title='Clique aqui para inserir um novo evento ...' onclick='return setEvento("0<?= $_arrProp['dia'] ?>")'><?= $_arrProp['dia'] ?></a>
		</span><br />

		<?php if (isset($_arrProp['msgs']['0']['hora'])) : ?>
			<?php foreach($_arrProp['msgs'] as $_l => $_arrCmps) : $id = $_arrCmps['id'].$_arrProp['dia']; ?>
			<div id='celula<?= $id ?>'>
				<div class='agId' id='<?= $id.'id' ?>'><?= $_arrCmps['id'] ?></div>
				<a title='<?= $_arrCmps["evento"] ?>' class='celulaA' href='' onclick="return setEvento('<?= $id ?>');">
				<div class='agHora' id='<?= $id.'hora' ?>'><?= $_arrCmps['hora'] ?></div>
				<?= substr($_arrCmps['evento'],0,10).' ...' ?>
				<div class='agEvento' id='<?= $id.'evento' ?>'><?= $_arrCmps['evento'] ?></div>
				</a>
			</div>
			<?php endforeach ?>
		<?php endif ?>

	</td>
	<?php else : ?>
	<td class='hojeNao'>-</td>
	<?php endif ?>

<?php endforeach ?>
</tr>
<?php endforeach ?>

</table>
<p class='obs'>
<center>
* Clique no dia para criar um novo evento.
</center>
</p>

</div>
<div id='evento'>
	<form id='formEvento' name='formEvento' method='post' action='<?= Router::url('/',true) ?>agenda/salvar_evento'>
	<span id='evFechar'><a href='#' onclick='$("#evento").hide(); $("#tampa").hide(); return false;'>[x] Fechar&nbsp;&nbsp;</a></span>
	<div id='evCampos'>
	<label>Quando:</label>
	<span id='evMesAno'></span>
	<?php echo $this->Form->input('Agenda.hora',array('label'=>false,'div'=>null,'options'=>$horas)) ?> horas e
	<?php echo $this->Form->input('Agenda.minu',array('label'=>false,'div'=>null,'options'=>$minutos)) ?> minutos.
	<br /><br />
	<label>Evento:</label><?php echo $this->Form->input('Agenda.evento',array('label'=>false,'div'=>null,'type'=>'text')) ?>
	<br />
	<?php echo $this->Form->input('Agenda._id',array('label'=>false,'div'=>null,'type'=>'hidden')) ?>
	<?php echo $this->Form->input('Agenda.dia',array('label'=>false,'div'=>null,'type'=>'hidden')) ?>
	<?php echo $this->Form->input('Agenda.mes',array('label'=>false,'div'=>null,'type'=>'hidden')) ?>
	<?php echo $this->Form->input('Agenda.ano',array('label'=>false,'div'=>null,'type'=>'hidden')) ?>
	</div>
	<div id='evBotoes'>
		<input type='submit' name='evSalvar' value='Salvar Evento' id='evSalvar' />
		<input type='submit' name='evExcluir' value='Excluir Evento' id='evExcluir' />
	</div>
	</form>
</div>
