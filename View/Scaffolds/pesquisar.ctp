<?php ?>
<ul>
<?php foreach($pesquisa as $id => $valor) echo "\t".'<li onclick="document.location.href=\''.$url.'/'.$id.'\'">'.$valor.'</li>'."\n"; ?>
</ul>
