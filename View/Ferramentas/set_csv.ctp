<?php
	if ($t>0)
	{
		$this->viewVars['onRead'] .= 
		"\t".
		'setTimeout(function(){ window.location="'.$this->base.'/ferramentas/set_csv" ,1000 });'.
		"\n";
	}
?>
<br /><br />
<center>O arquivo <?= $arq ?> foi importado com sucesso !!!</center>
<br /><br />
<?php $tot=0; if (isset($lista)) : ?>
<table align='center' border='1'>
	<tr>
		<th align='center'>Loop</th>
		<th style='width: 100px;'>Bloco</th>
		<th style='width: 100px;'>Registros</th>
	</tr>
<?php foreach($lista as $_l => $_arrProp) : ?>
	<tr>
		<td align='center'><?= ($_l+1) ?></td>
		<?php foreach($_arrProp as $_loop => $_reg) : ?>

		<td align='center'><?= number_format($_loop,0,',','.') ?></td>
		<td align='center'><?= number_format($_reg,0,',','.') ?></td>
		
		<?php $tot = $tot+$_reg; endforeach ?>
		
	</tr>
<?php endforeach ?>
	<tr>
		<td align='center' colspan='2'>Total</td>
		<td align='center'><?= number_format($tot,0,',','.') ?></td>
	</tr>
</ul>

<?php endif ?>
