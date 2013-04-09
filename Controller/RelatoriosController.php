<?php
/**
 * Relatórios
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class RelatoriosController extends AppController {
	/**
	 * Model relatorios
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = array();

	/**
	 * Exibe a tela inicial de relatórios
	 * 
	 * @return	void
	 */
	public function index()
	{
	}

	/**
	 * Imprime o relatório rel001 - Total de Cidades por Estado
	 * 
	 * @return	void
	 */
	public function rel001()
	{
		$this->data 				= $this->listaGrupo('Cidade',array('key'=>'estado'));
		$this->viewVars['titulo'] 	= 'Relatório Sintético de Cidade por Estado';
	}

	/**
	 * Imprime o relatório rel002 - Total de Acessos por usuário
	 * 
	 * @return	void
	 */
	public function rel002()
	{
		$this->data 				= $this->listaGrupo('Acesso',array('key'=>'login'));
		ksort($this->request->data);
		$this->viewVars['titulo'] 	= 'Relatório Sintético de Acessos por Usuário';
	}

	/**
	 * Imprime o relatório rel003 - Total de Usuários por Sexo
	 * 
	 * @return	void
	 */
	public function rel003()
	{
		$this->data 					= $this->listaGrupo('Usuario',array('key'=>'sexo'));
		$this->viewVars['titulo'] 		= 'Relatório Sintético de Total de Usuários por Sexo';
	}

	/**
	 * Imprime o relatório rel003 - Total de Usuários por Perfil
	 * 
	 * @return	void
	 */
	public function rel004()
	{
		$this->data 					= $this->listaGrupo('Usuario',array('key'=>'perfil'));
		ksort($this->request->data);
		$this->viewVars['titulo'] 		= 'Relatório Sintético de Total de Usuários por Perfil';
	}
}
