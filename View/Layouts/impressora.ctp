<?php ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" type="text/css" href="<?= Router::url('/',true); ?>css/default.css" />

	<?php echo $scripts_for_layout; ?>

	 <meta http-equiv="Content-Type" content="text/html;charset=utf-8" /> 
	 <style>
		 body
		 {
			 margin: 0px;
			 padding: 0px;
		 }
	 </style>
</head>
<body onload='window.print();'>

<?php  echo $content_for_layout; ?>

</body>
</html>
