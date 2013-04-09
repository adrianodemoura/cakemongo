<?php 
	// TUDO EM UTF-8
	header('Content-Type: text/html; charset=utf-8');
	
	ob_start();

	// SE NÃO ESTÁ LOGADO, CARCA FORA
	if($this->name!='Usuario' && $this->action!='login' && !$this->Session->check('Usuario'))
	{
		if($this->name!='Ferramentas' && !in_array($this->action,array('instalabd','instalatb')))
		{
			if ($this->name!='CakeError') die('<script>document.location.href="'.Router::url('/',true).'usuarios/login"</script>');
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	 <meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	<?php echo $scripts_for_layout; ?>
</head>
<body>

<?php  echo $content_for_layout; ?>

</body>
</html>
