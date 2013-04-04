<?php ?>
<div id='paginacao_lista'>

	<ul>
		<li>
			<a href='<?= Router::url('/',true).strtolower($this->name) ?>/editar/<?= $vizinhos['a'] ?>'> < </a>
		</li>
		<li>
			<a href='<?= Router::url('/',true).strtolower($this->name) ?>/editar/<?= $vizinhos['p'] ?>'> > </a>
		</li>

	</ul>

</div>
