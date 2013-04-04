<?php ksort($opcMenuLista); ?>
<img src='<?= $this->base ?>/img/bt_menu.png' onmouseover='showMenu("<?= $id ?>")' class='imgMenuLista' />

<div id='menuLista<?= $id ?>' class='divMenuLista'>
<ul class='menuLista'>
	<?php foreach($opcMenuLista as $_menu => $_arrUrl) : ?>

	<?php if (!is_array($_arrUrl)) : ?>
	<li>
		<a href='<?= str_replace('{_id}',$id,$_arrUrl) ?>' <?php if ($_menu=='Excluir') echo " onclick=\"return confirm('Você tem certeza em excluir este registro ?')\" "; ?> >
			<?= $_menu ?>
		</a>
	</li>
	
	<?php else : ?>
	
	<li><a href='javascript:void(0)'><?= $_menu ?></a>
		<ul class='col2'>
		<?php foreach($_arrUrl as $_tit => $_url) : ?>
			<li>
				<a href='<?= str_replace('{_id}',$id,$_url) ?>'<?php if ($_tit=='Excluir') echo " onclick=return confirm('Você tem certeza em excluir este registro ?') "; ?> >
					<?= $_tit ?>
				</a>
			</li>
		<?php endforeach ?>
		</ul>
	</li>
	<?php endif ?>
	
	<?php endforeach ?>
</ul>
</div>
