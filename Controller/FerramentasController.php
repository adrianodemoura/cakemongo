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

	/**
	 * Popula o Model
	 * 
	 * @param	integer	$tot	Total de documentos
	 */
	public function popular()
	{
		$tota = isset($this->data['Popular']['total']) ? $this->data['Popular']['total'] : 0;
		if (!empty($tota))
		{
			$arrProp 			= array();
			$arrProp['total'] 	= $tota;
			$arrProp['Model'] 	= isset($this->data['Popular']['model']) ? $this->data['Popular']['model'] : null;
			$arrProp['loop']  	= isset($this->data['Popular']['loop'])  ? $this->data['Popular']['loop']  : 0;
			$arrProp['feito']	= 0;
			$arrProp['inicio']  = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
			$this->Session->write('Popular',$arrProp);
		}
		$arrProp = $this->Session->check('Popular') ? $this->Session->read('Popular') : array();

		if ($this->Session->check('Popular.total'))
		{
			$total	= $this->Session->read('Popular.total');
			$loop 	= $this->Session->read('Popular.loop');
			$aFazer = ($total / $loop);

			// se chegou no fim
			if ($this->Session->read('Popular.feito')>=$total)
			{
				$arrProp['fim'] = mktime(date('H'),date('i'),date('s'),date('m'),date('d'),date('Y'));
				$this->Session->delete('Popular');
			} else
			{
				$texto['0'] = 'Lorem ipsum dolor sit amet, ei munere aliquid officiis mel, an fugit imperdiet disputando quo, ut quo';
				$texto['1'] = 'dictas expetenda dissentiet. Ei simul salutatus eum. Timeam consequuntur sea te, alii eirmod vel ut.';
				$texto['2'] = 'Nec reque appetere et. Cum et solet exerci, sea invidunt tractatos ut. Usu et amet case consul. Ferri';
				$texto['3'] = 'tation malorum sea, cu qui esse omittam. Eu prima dicit aperiri est, dicat ignota interpretaris at mel, te';
				$texto['4'] = 'Vim et veri tamquam commune, his ne novum commodo minimum. Eu rebum viris definiebas mea, no tuo';
				$d1 = mktime(0,0,0,1,1,1980);
				$d2 = mktime(0,0,0,1,1,date('Y'));

				$model	= $this->Session->read('Popular.Model');
				App::uses($model,'Model');
				$M 		= new $model();
				$schema = $M->schema;
				unset($schema['_id']);
				$this->Session->write('Popular.feito',(($this->Session->read('Popular.feito')+$aFazer)));
				$arrProp 	= $this->Session->read('Popular');
				$data 		= array();
				for($i=0; $i<=$aFazer; $i++)
				{
					foreach($schema as $_cmp => $_arrProp)
					{
						$tipo = isset($_arrProp['type']) ? $_arrProp['type'] : 'string';
						if (isset($_arrProp['tipo'])) $tipo = $_arrProp['tipo'];
						switch($tipo)
						{
							case 'integer':
								$data[$i][$model][$_cmp] = rand(1,1000);
								break;
							case 'data':
								$data[$i][$model][$_cmp] = date('d/m/Y',rand($d1,$d2));
								break;
							case 'datatempo':
								$data[$i][$model][$_cmp] = date('d/m/Y H:i:s',rand($d1,$d2));
								break;
							case 'string':
							case 'text':
								$data[$i][$model][$_cmp] = substr($texto[rand(0,4)],0,$_arrProp['length']);
								break;
						}
					}
				}
				if (!$M->saveAll($data))
				{
					debug($data);
					die('fudeu !!!');
				}
			}
		}
		
		$this->set(compact('arrProp'));
	}
}
