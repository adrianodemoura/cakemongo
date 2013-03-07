<?php
?>
<style>
	.tab
	{
		margin: 10px auto;
		font-size: 20px;
	}
	.tab th
	{
		background-color: #ddd;
	}
	.tab .ativa
	{
		background-color: #eee;
	}
</style>
<table class='tab' border='1px'>
	<tr>
		<th width='300px'>Sexo</th>
		<th width='100px'>Total</th>
	</tr>
<?php $t=0; $l=0; foreach($this->data as $_sexo => $_tot) : $t += $_tot; $l++;?>
	<tr <?php if ($l%2) :?>class="ativa" <?php endif  ?> >
		<td><?= $sexos[$_sexo] ?></td>
		<td align='center'><?= number_format($_tot,0,',','.') ?></td>
	</tr>
<?php endforeach ?>
	<tr>
		<td>Total</td>
		<td align='center'><?= number_format($t,0,',','.'); ?></td>
	</tr>

</table>
