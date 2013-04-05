<?php
/**
 * Cadastro de Mensagens
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class MensagensController extends AppController {
	/**
	 * Model usuarios
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = array('Mensagem');

	/**
	 * Executa código antes da renderização da view
	 * 
	 * @return	void
	 */
	public function beforeRender()
	{
		parent::beforeRender();
		$this->viewVars['edicaoCampos'] = array('Mensagem.texto','-','Mensagem.modificado','Mensagem.criado');
		$this->viewVars['focus'] = 'Mensagem.texto';
	}
}
