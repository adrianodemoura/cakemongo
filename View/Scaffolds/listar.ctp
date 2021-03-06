<?php
	// meta
	$this->Html->script('listar', array('inline' => false));
	$this->Html->css('listar', null, array('inline' => false));
	$this->Html->css('menu_listar', null, array('inline' => false));
	$this->Html->css('abas', null, array('inline' => false));

	// ferramentas
	$listaFerram['editar']['ico'] 	= Router::url('/',true).'/img/bt_editar.png';
	$listaFerram['editar']['url']	= $this->base.'/'.strtolower($this->name).'/editar/{id}';
	$this->viewVars['listaFerram']	= isset($this->viewVars['listaFerram']) ? $this->viewVars['listaFerram'] : $listaFerram;

	// lista campos
	$this->viewVars['listaCampos'] = isset($this->viewVars['listaCampos']) ? $this->viewVars['listaCampos'] : array();

	// 
	$urlPag = Router::url('/',true).strtolower($this->name).'/listar/pag:'.$this->request->params['paging'][$modelClass]['page'];

	//
	if ($this->request->named['direction']=='asc') $direcao = 'desc'; else $direcao = 'asc';
?>

<?php if (!isset($soLeitura) && $this->Html->getLink('/'.strtolower($this->name).'/salvar')) : ?>
<div id='novo'>
	<a title='Clique aqui para inserir um novo registro' 
		href='<?= $this->base.'/'.strtolower($this->name) ?>/editar/0'>
	<img src='<?= $this->base ?>/img/bt_novo.png' />
	</a>
	<?php if (isset($botoes)) : foreach($botoes as $_tit => $_arrProp) : ?>
	<br /><br /><a title='<?= isset($_arrProp['title']) ? $_arrProp['title'] : null ?>' href='<?= $_arrProp['url'] ?>'>
	<img src='<?= $_arrProp['img'] ?>' width=30 />
	</a>
	<?php endforeach; endif ?>
</div>
<?php endif ?>

<?php echo $this->element('paginacao_lista'); ?>

<?php echo $this->element('pesquisa'); ?>

<?php echo $this->element('abas'); ?>

<table cellspacing='0' cellpadding='0' class='tabLista'>
<?php foreach($this->data as $_l => $_arrMods) : $id = $_arrMods[$modelClass]['_id']; ?>
	<!-- cabeçalho -->
	<?php if (!$_l) : ?>
	<tr>
	<th colspan='<?= count($this->viewVars['listaFerram']) ?>' style='text-align: center'>#</th>
	<?php 
		foreach($this->viewVars['listaCampos'] as $_cmp) : 
		$a=explode('.',$_cmp); 
		$tit = $a['1']; 
		$p = isset($schema[$a['1']]) ? $schema[$a['1']] : array(); ?>
		<?php $tit = isset($p['input']['label']) ? $p['input']['label'] : $a['1']; ?>
		<th id='th<?= ucfirst($a['1']) ?>' <?php if (isset($p['th'])) foreach($p['th'] as $_tag => $_vlr) echo $_tag.'="'.$_vlr.'"';  ?> >
		
		<?php if (isset($p['index'])) : ?>
			<a href='<?= $urlPag ?>/ordem:<?= strtolower($a['1']) ?>/dire:<?= $direcao ?>'><?= $tit ?></a>
		<?php else : ?>
			<?= $tit ?>
		<?php endif ?>

		</th>
	<?php endforeach ?>
	</tr>
	<?php endif ?>
	<!-- FIM cabeçalho -->
	
	<tr>
		<!-- menu -->
		<td align='center' class='tdListaMenu'>
			<?php if (isset($opcMenuLista)) echo $this->element('menu_lista',array('id'=>$id)) ?>

		</td>

		<!-- campos -->
		<?php 
		foreach($this->viewVars['listaCampos'] as $_cmp) : 
		$a=explode('.',$_cmp); 
		if (isset($_arrMods[$a['0']][$a['1']])) : 
		$m = isset($schema[$a['1']]['mascara']) ? $schema[$a['1']]['mascara'] : '';
		$p = isset($schema[$a['1']]) ? $schema[$a['1']] : array();
		?>
		<?php if (!is_array($_arrMods[$a['0']][$a['1']])) : ?>
		<td <?php if (isset($p['td'])) foreach($p['td'] as $_tag => $_vlr) echo $_tag.'="'.$_vlr.'"';  ?>
		>
			<?= $this->Html->getMascara($_arrMods[$a['0']][$a['1']],$m) ?>
		</td>
		<?php else : ?>
		<td>
			<?php foreach($_arrMods[$a['0']][$a['1']] as $_l => $_nome) echo $_nome.', '; ?>
		</td>
		<?php endif ?>
		
		<?php else : ?>
		<td style='text-align: center;'>-</td>
		<?php endif; endforeach ?>
		<!-- FIM campos -->

	</tr>
<?php endforeach ?>
	<?php if (isset($this->request->params['paging'][$modelClass]['count']) && ($this->request->params['paging'][$modelClass]['count']) ) : ?>
	<tr>
		<td colspan='<?= count($this->viewVars['listaCampos'])+1 ?>' >
		Total: <?= number_format($this->request->params['paging'][$modelClass]['count'],0,',','.') ?>
		</td>
	</tr>
	<?php endif ?>
</table>
