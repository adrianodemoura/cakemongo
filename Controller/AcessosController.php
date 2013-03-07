<?php
/**
 * Cadastro de acessos
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class AcessosController extends AppController {
	/**
	 * Model usuarios
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = array('Acesso');

	/**
	 * Chamada antes de qualquer outroa método.
	 * 
	 * @return	void
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
	 */
	public function beforeFilter()
	{
		$this->viewVars['soLeitura'] = true;
		parent::beforeFilter();
		$this->viewVars['schema']['diferenca'] = array
		(	'type' 	=> 'integer',
			'td'	=> array('style'=>'text-align: center;'),
			'input'	=> array('label'=>'Permanência (h:m)')
		);
	}

	/**
	 * Exibe a tela de acessos.
	 * - Somente Administradores podem ver todos os registros, caso contrário.\n
	 * o usuário logado verá somente o os seus acessos.\n
	 * 
	 * @return	void
	 */
	public function listar()
	{
		$perfil = $this->Session->read('Usuario.perfil');
		if ($perfil != 'ADMINISTRADOR')
		{
			$this->filtro['Acesso.login'] 	= $this->Session->read('Usuario.login');
		}
		$this->passedArgs['dire'] 		= 'desc';
		$this->passedArgs['ordem']		= 'data_acesso';
		$this->viewVars['listaCampos'] = array('Acesso.login','Acesso.ip','Acesso.data_acesso','Acesso.data_saida','Acesso.diferenca');
		parent::listar();
	}
}
