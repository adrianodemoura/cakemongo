<?php

?>
<style>
	#erroAction
	{
		color: red;
		line-height: 26px;
	}
	#erroAction strong
	{
		font-size: 28px;
	}
</style>
<div id='erroAction'>
	<br />
	<center>
		Não foi possível localizar o método<br ?><strong><?= $action ?></strong><br /> 
		no controlador<br /><strong><?= $controller ?></strong>
	</center>
</div>
