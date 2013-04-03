<?php
/**
 * Cadastro de Cidades
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class CidadesController extends AppController {
	/**
	 * Model usuarios
	 * 
	 * @var		array
	 * @link	http://book.cakephp.org/2.0/en/controllers.html#components-helpers-and-uses
	 */
	public $uses = array('Cidade');

	/**
	 * 
	 */
	public function beforeFilter()
	{
		//$this->viewVars['soLeitura'] = true;
		parent::beforeFilter();
	}

	/**
	 * Executa uma busca para resposta ajax
	 * 
	 * @return	void
	 */
	public function busca_ajax($texto='')
	{
		$modelClass 	= $this->modelClass;
		$campo			= isset($this->$modelClass->displayField) ? $this->$modelClass->displayField : 'nome';
		$this->layout	= 'ajax';
		if ($this->usarScaffolds) $this->viewPath = 'Scaffolds';
		$opc	= array();
		$opc['order'][$modelClass.'.'.$campo] = 'asc';
		$opc['fields'] = array($modelClass.'.uf',$modelClass.'.nome');
		$opc['conditions'][$modelClass.'.'.$campo] = array("\$regex" => ".*".mb_strtoupper($texto).".*");
		$opc['limit'] = 30;
		
		$data = array();
		$_data = $this->$modelClass->find('list',$opc);
		foreach($_data as $_uf => $_cidade)
		{
			$data[$_cidade.' / '.$_uf] = $_cidade.' / '.$_uf;
		}
		
		$this->data = $data;
	}
}
