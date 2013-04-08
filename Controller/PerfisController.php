<?php
/**
 * Cadastro de perfis
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class PerfisController extends AppController {
	/**
	 * Model usuarios
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = array('Perfil');

	/**
	 * Exibe a tela de edição do perfil
	 * 
	 * @param	integer		$id		Id do documento a ser editado
	 */
	public function editar($id=0)
	{
		parent::editar($id);
		$this->viewVars['focus'] = 'Perfil.nome';
	}
}
