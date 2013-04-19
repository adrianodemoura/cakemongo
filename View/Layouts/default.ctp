<?php
	if (!$this->Session->check('Usuario.login') && $this->action!='login') die('<script>document.location.href="'.$this->base.'/usuarios/login"</script>');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>

	<title><?php if (isset($titulo)) echo $titulo; ?></title>

	<?php
		echo $this->Html->meta('icon')."\n";

		echo "\t".$this->Html->css('default')."\n";
		echo "\t".$this->Html->css('menu')."\n";

		echo "\t".$this->Html->script('jquery-1.5.1.min.js')."\n";
		echo "\t".$this->Html->script('jquery.maskedinput-1.3.min.js')."\n";

		echo "\t".$this->fetch('meta')."\n";

		echo "\t".$this->fetch('css')."\n";
	?>
	<?php if (empty($this->plugin)) : ?>
		<?php $arq = ROOT.DS.APP_DIR.DS.'webroot'.DS.'css'.DS.strtolower($this->name).'.css'; ?>
		<?php if (file_exists($arq)) : ?>

	<link rel="stylesheet" type="text/css" href="<?= $this->base ?>/css/<?= strtolower($this->name) ?>.css" />
		<?php endif ?>

	<?php else : ?>
		<?php $arq = ROOT.DS.APP_DIR.DS.'Plugin'.DS.$this->plugin.DS.'webroot'.DS.'css'.DS.strtolower($this->name).'.css'; ?>
		<?php if (file_exists($arq)) : ?>

	<link rel="stylesheet" type="text/css" href="<?= $this->base.'/'.strtolower($this->plugin) ?>/css/<?= strtolower($this->name) ?>.css" />
		<?php endif ?>
	<?php endif ?>

	<script type="text/javascript" src="<?= $this->base; ?>/js/default.js"></script>
	<?php echo "\t".$this->fetch('script')."\n"; ?>
	<?php if (empty($this->plugin)) : ?>
	<?php $arq = ROOT.DS.APP_DIR.DS.'webroot'.DS.'js'.DS.strtolower($this->name).'.js'; ?>
	<?php if (file_exists($arq)) : ?>

	<script type="text/javascript" src="<?= $this->base; ?>/js/<?= strtolower($this->name) ?>.js"></script>
		<?php endif ?>
	<?php else : ?>
		<?php $arq = ROOT.DS.APP_DIR.DS.'Plugin'.DS.$this->plugin.DS.'webroot'.DS.'js'.DS.strtolower($this->name).'.js'; ?>
		<?php if (file_exists($arq)) : ?>

	<script type="text/javascript" src="<?= $this->base.'/'.strtolower($this->plugin); ?>/js/<?= strtolower($this->name) ?>.js"></script>
		<?php endif ?>
	<?php endif ?>
	
<script type="text/javascript">
var url = '<?= $this->base; ?>';
$(document).ready(function()
{
	setTimeout(function(){ $("#flashMessage").fadeOut(4000); },3000);
	$(document).click(function(e) { if (e.button==0) $('.divMenuLista').fadeOut(); return true; });

<?php if (isset($this->viewVars['onRead'])) echo $this->viewVars['onRead']; ?>
});
</script>

</head>
<body>
	<div id="corpo">
		<div id="cabecalho">
			<?php echo $this->Session->flash(); ?>
			<div id='titSistema'><a href='<?= $this->base ?>'><?= Configure::read('sistema') ?></a></div>
			
			<?php if ($this->Session->check('Usuario.login')) : ?>
			<div id='infoLogin'>
				<span id='spanNome'><?= ucwords(mb_strtolower($this->Session->read('Usuario.nome'),'utf8')) ?></span>
				<span id='spanUltimo'> | Último acesso: <?= $this->Session->read('Usuario.ultimo') ?></span>
				<span id='spanIp'> | Seu IP: <?= getenv("REMOTE_ADDR"); ?></span>
			</div>
			<?php endif ?>
			
		</div>

		<?php if ($this->Session->read('Usuario.perfil')=='ADMINISTRADOR' && !in_array($this->name,array('Urls','Usuarios')) && $this->action!='principal') : ?>
		<div id='ferAdmin'>
			<a href='<?= $this->base ?>/urls/editar/!' title='Clique aqui para definir a permissão de acesso ...'>
			<img src='<?= Router::url('/',true) ?>/img/bt_cadeado.png' border='0xp' /></a>
		</div>
		<?php endif ?>
		<?php if ($this->Session->check('Usuario.login')) echo $this->element('menu'); ?>

		<div id="conteudo">
			<?php echo $this->fetch('content'); ?>

		</div>

	</div>
	<?php echo $this->element('sql_dump'); ?>

	<div id='tampa'></div>
</body>
</html>
