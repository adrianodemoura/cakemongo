<?php
/**
 * Ferramentas
 * 
 * @package       app.Controller
 */
/**
 * @package       app.Controller
 */
class FerramentasController extends AppController {
	/**
	 * Model usuarios
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
	 * Limpa o Cache
	 * 
	 * @return	void
	 */
	public function limpar_cache()
	{
		Cache::clear();
	}

	/**
	 * Importar um arquivo CSV.
	 * O arquivo deve estar no seguinte formato:\n
	 * - o nome do arquivo é o nome da tabela\n
	 * - a primeira linha deve conter os nomes do campos\n
	 * - os campos devem estar entre ";";
	 * 
	 * @return
	 */
	public function importar_csv()
	{
		$limite 	= '1000';
		$model		= '';
		$arq 		= '';
		if (isset($this->data['Importa']['arquivo']))
		{
			$arq 	= $this->data['Importa']['arquivo']['tmp_name'];
			$nome	= $this->data['Importa']['arquivo']['name'];
			$limite	= $this->data['Importa']['limite'];
			$model	= ucfirst(strtolower($this->data['Importa']['model']));
			$arqMod = APP.DS.'Model'.DS.$model.'.php';
			if (!file_exists($arqMod))
			{
				$this->Session->setFlash('Model inválido !!!','default',array('class'=>'msgErro'));
				$this->redirect('importar_csv');
			}

			if (file_exists($arq))
			{
				if (!move_uploaded_file($arq,'/tmp/'.$nome)) die('Não foi possível mover o arquiv !!!');
				$arq 	= '/tmp/'.$nome;
			}else
			{
				$this->Session->setFlash('Arquivo inválido !!!','default',array('class'=>'msgErro'));
				$this->redirect('importar_csv');
			}
		}
		if (!empty($arq) && file_exists($arq))
		{
			$this->Session->delete('csv');
			$this->Session->write('csv.Arq',$arq);
			$this->Session->write('csv.Model',$model);
			$this->Session->write('csv.Limite',$limite);
			$this->Session->write('csv.Lista',array());
			$this->redirect('set_csv');
		}
		$this->viewVars['limite'] = $limite;
	}

	/**
	 * Popula uma tabela do banco com seu aquivo CSV
	 * 
	 * @parameter 	$arq		string	Caminho completo com o nome do arquivo
	 * @parameter	$collection	string	Nome da collection a ser populada
	 * @return		void		
	 */
	public function set_csv()
	{
		// recuperando os parâmetros
		$arq 		= $this->Session->read('csv.Arq');
		$model 		= $this->Session->read('csv.Model');
		$limitePV 	= $this->Session->check('csv.Limite') ? $this->Session->read('csv.Limite') : 1000;

		// loop
		$loop = $this->Session->check('csv.Loop') ? $this->Session->read('csv.Loop') : 1;

		// linha inicial e final do arquivo a ser exportada
		$limite['ini'] = ($limitePV*$loop)-$limitePV;
		$limite['fim'] = ($limite['ini']+$limitePV);

		// atualizando a view
		$this->viewVars['t']		= 0;
		$this->viewVars['loop'] 	= ($loop-2);
		$this->viewVars['arq']		= $arq;
		$this->viewVars['lista']	= $this->Session->check('csv.Lista') ? $this->Session->read('csv.Lista') : array();

		// mandando bala se o csv existe
		if (file_exists($arq))
		{
			// importando o model
			App::uses($model,'Model');
			$Model 		= new $model();
			$data		= array();

			$linhas  	= file($arq);
			$l 			= 0;
			
			// executando linha a linha
			foreach($linhas as $_l => $_linha)
			{
				$linha = explode(';',trim($_linha));
				if ($_l>$limite['fim']) break;
				if (!$_l)
				{
					$arrCmps	= $linha;
				} elseif($_l>$limite['ini'])
				{
					$i = 0;
					foreach($linha as $_vlr)
					{
						$data[($_l-1)][$model][$arrCmps[$i]] = trim($_vlr);
						$i++;
					}
				}
			}

			$lista = $this->Session->check('csv.Lista') ? $this->Session->read('csv.Lista') : array();

			// retorna
			if (!empty($data))
			{
				if (!$Model->saveAll($data))
				{
					debug($Model->validationErrors);
					die('fudeu !!!');
				} else
				{
					$t = count($data);
					$this->viewVars['t']		= $t;

					// atualizando a lista
					array_push($lista,array($limite['fim']=>$t));
					if ($t) $loop++;
				}
			} else
			{
				$this->viewVars['msg'] = 'O arquivo foi importado com sucesso !!!';;
			}
			$this->Session->write('csv.Lista',$lista);
			$this->Session->write('csv.Loop',$loop);
		} else $msg = 'não foi possivel localizar '.$arq;
	}
}
