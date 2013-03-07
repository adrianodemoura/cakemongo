<?php
/**
 * Application level View Helper
 *
 * This file is application-wide helper file. You can put all
 * application-wide helper-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Helper
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Helper', 'View');

/**
 * Application helper
 *
 * Add your application-wide methods in the class below, your helpers
 * will inherit them.
 *
 * @package       app.View.Helper
 */
class AppHelper extends Helper {
	/**
	 * Retorna o valor mascarado
	 * 
	 * @param	string	$valor		Valor a ser mascarado
	 * @param	string	$mascara	Máscara
	 * @return	string	$mascarado	Campo mascarado
	 */
	public function getMascara($valor,$mascara)
	{
		$mascarado = $valor;
		switch($mascara)
		{
			case '99:99':
			case '999:99':
				$valor	= round($valor*100)/100;
				$natu	= floor($valor);
				$frac	= round(($valor - $natu)*60);
				if ($mascara=='99:99')	{ $natu	= '00'.$natu;	$natu 	= substr($natu,strlen($natu)-2,2); }
				if ($mascara=='999:99') { $natu	= '000'.$natu;	$natu 	= substr($natu,strlen($natu)-3,3); }
				$mascarado = ($frac>0)  ? $natu.':'.floor($frac) : $natu.':00';
				break;
			case '9':
				if ($valor=='0') $mascarado = '';
				break;
			case '99':
			case '999':
				if ($mascara=='99')	{ $mascarado = '00'.$valor;		$mascarado = substr($mascarado,strlen($mascarado)-2,2); }
				if ($mascara=='999'){ $mascarado = '000'.$valor; 	$mascarado = substr($mascarado,strlen($mascarado)-3,3); }
				break;
			case 'cpf':
			case '999.999.999-99':
				$mascarado = substr($valor,0,3).'.'.substr($valor,3,3).'.'.substr($valor,6,3).'-'.substr($valor,9,2);
				if ($valor=='') $mascarado = '';
				break;
			case 'cnpj':
			case '99.999.999/9999-99':
				$mascarado = substr($valor,0,2).'.'.substr($valor,2,3).'.'.substr($valor,5,3).'/'.substr($valor,8,4).'-'.substr($valor,12,2);
				if ($valor=='0') $mascarado = '';
				break;
			case 'aniversario':
			case '99/99':
				$mascarado = substr($valor,0,2).'/'.substr($valor,2,2);
				break;
			case 'cep':
			case '99.999-999':
				$mascarado = substr($valor,0,2).'.'.substr($valor,2,3).'-'.substr($valor,5,3);
				if ($valor=='') $mascarado = '';
				break;
			case 'telefone':
			case 'celular':
			case '(99)9999-9999':
				$mascarado = '('.substr($valor,0,2).')'.substr($valor,2,4).'-'.substr($valor,6,4);
				if ($valor=='') $mascarado = '';
				if (strlen($valor)==8)
				{
					$mascarado = substr($valor,0,4).'-'.substr($valor,4,4);
				}
				break;
		}
		return $mascarado;
	}

	/**
	 * Retorna verdadeiro ou false, se o usuário logado pode acessar o link
	 * 
	 * @param	string	$link	Link a ser testadado.
	 * @return	boolean	
	 */
	public function getLink($link)
	{
		$perfil = $_SESSION['Usuario']['perfil'];
		if ($perfil=='ADMINISTRADOR') return true;
		$urls	= Cache::read('urls'.$perfil);
		if (isset($urls[$link])) return true;
		//foreach($urls as $_link => $_tit) if (strpos($_link,$link)) return true;
		return false;
	}

	/**
	 * Retorna o link, testado.
	 * 
	 * <img src='<?= $this->base ?>/img/logo_relatorios.png' />
	 * 
	 * @param	string	$url	Url a ser testada
	 * @param	string	$tit	Título do Link
	 * @param	string	$img	Imagem do link
	 */
	public function getLinkLI($url='',$tit='',$img='')
	{
		$link = '';
		if ($this->getLink($url))
		{
			$link = "<li><a href='".$this->base.$url."'>";
			if (!empty($img)) $link .= "<img src='".$this->base."/img/".$img."' />";
			$link .= $tit;
			$link .= "</a></li>\n";
		}
		return $link;
	}
}
