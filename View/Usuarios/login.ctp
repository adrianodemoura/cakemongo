<?php ?>
<style>
	#login
	{
		border: 1px solid #ccc;
		width: 300px;
		margin: 50px auto;
		padding: 10px;
	}
	#login label
	{
		display: block;
		width: 110px;
		float: left;
		text-align: right;
		line-height: 24px;
	}
	#login .submit
	{
		text-align: center;
		padding: 10px 0px 0px 0px;;
	}
	#login .submit input
	{
		width: 100px;
		height: 30px;
	}
	#login #UsuarioLogin,
	#login #UsuarioSenha
	{
		width: 100px;
		margin: 0px 0px 0px 5px;
	}
	#login .linha
	{
		height: 30px;
		line-height: 30px;
	}
</style>
<div id='login'>
	<?php
		echo $this->Form->create('Usuario',array('action'=>'login'));
		echo $this->Form->input('login',array('div'=>array('class'=>'linha'),'class'=>'logIn'))."\n";
		echo $this->Form->input('senha',array('div'=>array('class'=>'linha'),'class'=>'logIn','type'=>'password'))."\n";
		echo $this->Form->end('Entrar');
		$this->viewVars['onRead'] .= "\t".'$("#UsuarioLogin").focus()'."\n";
	?>
</div>
