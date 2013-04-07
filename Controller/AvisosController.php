<?php
/**
 * Cadastro de Avisos
 * Aqui o usuário poderá cadastros avisos para todos.
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class AvisosController extends AppController {
	/**
	 * Model
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = array('Aviso');

	/**
	 * Executa código antes da renderização da view
	 * 
	 * @return	void
	 */
	public function beforeRender()
	{
		parent::beforeRender();
		$this->viewVars['edicaoCampos'] = array('Aviso.texto','-','Aviso.modificado','Aviso.criado');
		$this->viewVars['focus'] = 'Aviso.texto';
	}
}
