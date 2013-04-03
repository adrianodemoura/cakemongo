<?php  
	$pag	= $this->request->params['paging'][$modelClass]['page'];
	$opcs 	= array();
	if ($this->request->params['paging'][$modelClass]['pageCount']>1)
	{
		for($i=1; $i<=$this->request->params['paging'][$modelClass]['pageCount']; $i++) $opcs[$i] = $i;
	}
?>
<div id='paginacao_lista'>
	<ul>
		<?php if (!$this->request->params['paging'][$modelClass]['prevPage']) : ?>
		<li> << </li>
		<li> < </li>
		<?php else : ?>
		<li><a href='<?= Router::url('/',true).strtolower($this->name) ?>/listar/pag:1/ordem:<?= $this->request->named['sort'] ?>/direcao:<?= $this->request->named['direction'] ?>'> << </a></li>
		<li><a href='<?= Router::url('/',true).strtolower($this->name) ?>/listar/pag:<?= $pag-1 ?>/ordem:<?= $this->request->named['sort'] ?>/direcao:<?= $this->request->named['direction'] ?>'> < </a></li>
		<?php endif ?>
		
		<?php 
			if ($this->request->params['paging'][$modelClass]['pageCount']>1)
			{
				echo $this->Form->input('Lista.pag',array
					(
					'default'=>$pag,
					'label'=>false,'div'=>null,
					'options'=>$opcs,
					'empty'=>false
					)
				);

				$this->viewVars['onRead'] .= "\t".'$("#ListaPag").change(function() {
					var p = $(this).val();
					var url = "'.Router::url('/',true).strtolower($this->name).'/listar/pag:"+p+"/ordem:'.
					$this->request->named['sort'].'/direcao:'.$this->request->named['direction'].'";
					window.location.href=url; 
				})'."\n";
			}
		?>
				
		<?php if (!$this->request->params['paging'][$modelClass]['nextPage']) : ?>
		<li> > </li>
		<li> >> </li>
		<?php else : ?>
		<li><a href='<?= Router::url('/',true).strtolower($this->name) ?>/listar/pag:<?= $pag+1 ?>/ordem:<?= $this->request->named['sort'] ?>/direcao:<?= $this->request->named['direction'] ?>'> > </a></li>
		<li><a href='<?= Router::url('/',true).strtolower($this->name) ?>/listar/pag:<?= $this->request->params['paging'][$modelClass]['pageCount'] ?>/ordem:<?= $this->request->named['sort'] ?>/direcao:<?= $this->request->named['direction'] ?>'> >> </a></li>
		<?php endif ?>
		
	</ul>
</div>
