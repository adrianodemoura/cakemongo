<?php
/**
 * Cadastro de urls
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class UrlsController extends AppController {
	/**
	 * Model usuarios
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = array('Url');

	/**
	 * Executa código antes da renderização da view.
	 * 
	 * @return	http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 */
	public function beforeRender()
	{
		$this->viewVars['edicaoCampos'] = array('Url.link','-',	'Url.perfis');
		$this->viewVars['focus'] = 'Url.titulo';
		parent::beforeRender();
		App::uses('Perfil','Model');
		$Perfil 		= new Perfil();
		$opc 			= array();
		$opc['order']	= array('Perfil.nome'=>'asc');
		$opc['fields'] 	= array('Perfil.nome','Perfil.nome');
		$this->viewVars['perfis'] = $Perfil->find('list',$opc,'listaPerfil');
		unset($this->viewVars['perfis']['ADMINISTRADOR']);
		$url = Router::url('/',true);
		$url = substr($url,0,strlen($url)-1);
		$this->viewVars['schema']['link']['input']['label'] = 'Url <span style="font-size: 9px;">'.$url.'</span>';
	}
}
