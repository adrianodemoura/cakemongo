<?php ?>
<div id='paginacao_lista'>

	<ul>
		<li>
			<?php if ($vizinhos['r']) : ?>
			<a href='<?= Router::url('/',true).strtolower($this->name) ?>/editar/<?= $vizinhos['r'] ?>'> << </a>
			<?php else : ?>
			<<
			<?php endif ?>
		</li>

		<li>
			<?php if ($vizinhos['a']) : ?>
			<a href='<?= Router::url('/',true).strtolower($this->name) ?>/editar/<?= $vizinhos['a'] ?>'> < </a>
			<?php else : ?>
			<
			<?php endif ?>
		</li>

		<li>
			<?php if ($vizinhos['p']) : ?>
			<a href='<?= Router::url('/',true).strtolower($this->name) ?>/editar/<?= $vizinhos['p'] ?>'> > </a>
			<?php else : ?>
			>
			<?php endif ?>
		</li>

		<li>
			<?php if ($vizinhos['u']) : ?>
			<a href='<?= Router::url('/',true).strtolower($this->name) ?>/editar/<?= $vizinhos['u'] ?>'> >> </a>
			<?php else : ?>
			>>
			<?php endif ?>
		</li>

	</ul>

</div>
