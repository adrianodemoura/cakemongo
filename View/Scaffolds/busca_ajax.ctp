<?php  ?>
<style>
	ul
	{
		margin: 0px;
		padding: 5px;
		list-style: none;
	}
	ul li
	{
		line-height: 20px;
	}
	ul li:hover
	{
		background-color: #fff;
		cursor: pointer;
	}
</style>
<ul>
<?php foreach($this->data as $id => $valor)  echo "\t".'<li>'.$valor.'</li>'."\n"; ?>
</ul>

