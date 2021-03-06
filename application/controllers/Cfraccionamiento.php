<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cfraccionamiento extends CI_Controller {
	//Funciones a copiar en todos los controladores
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
	}

	public function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != true) {
			redirect(base_url(),'refresh');
		}
	}
// fin de funciones a copiar en todos los controladores
	public function view($page="home", $param1="", $param2="",$param3="")
	{
		
		if ( ! file_exists(APPPATH.'/views/fraccionamiento/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
     switch ($page) {
			
			case 'verBiberonesPorTipo':
				$data['biberonesPorTipo'] =$this->fraccionamiento_model->getBiberonesTipoLeche($param1);
				//var_dump($data['biberonesPorTipo'] );
				$data['tipoLeche'] = $param1;
				break;
			case 'verPmedicasPorTipo':
				$data['pmedicasPorTipo']= $this->pmedica_model->getPmedicasPorTipo($param1);
				$data['tipoLeche'] = $param1;
				break;
			case 'verTodosLosFraccionamientos':
				$data['fraccionamientos'] = $this->fraccionamiento_model->getAllFraccionamientos();
				break;
			case 'registrarConsumoBr':
				$data["bebesConF"] = $this->fraccionamiento_model->getBebesConFrac();
				break;
			case 'verFraccionesUnbr':
				$data['fraccionesUnbebe']=$this->fraccionamiento_model->getFraccionamientosUnBr($param1);
				$data['cantidadFracc'] = count($data['fraccionesUnbebe']);
				$data['unReceptor'] = $this->bebereceptor_model->getBebereceptor($param1);
				//var_dump($data['fraccionesUnbebe'] );
				//var_dump($data['unReceptor'] );
				break;
			//la idea de ver un fraccionamiento es ver nombre por lo menos del bebe que tomó
			case 'verUnFraccionamiento':
				$data["unFraccionamiento"]=$this->fraccionamiento_model->getUnFraccionamiento($param1);
				$data["unReceptor"] = $this->bebereceptor_model->getBebereceptor($data["unFraccionamiento"][0]->BebeReceptor_idBebeReceptor);
				$data["fechaFraccionamiento"] = $data["unFraccionamiento"][0]->fechaFraccionamiento;
				$data["fechaFraccArreglada"] = $this->fraccionamiento_model->arreglarFecha($data["fechaFraccionamiento"]);
				$data["fechaPmArreglada"] = $this->fraccionamiento_model->arreglarFecha($data["unFraccionamiento"][0]->PrescripcionMedica_fechaPrescripcion);
				$data["unaPmedica"] = $this->pmedica_model->getUnaPmedica($data["unFraccionamiento"][0]->PrescripcionMedica_idPrescripcionMedica);
				//var_dump($data["unaPmedica"]);
				$data["medico"] = $data["unaPmedica"][0]->medico;
				break;
			case 'editarUnFraccionamiento':
				$data["unFraccionamiento"]=$this->fraccionamiento_model->getUnFraccionamiento($param1);
				$data["unReceptor"] = $this->bebereceptor_model->getBebereceptor($data["unFraccionamiento"][0]->BebeReceptor_idBebeReceptor);
				$data["fechaFraccionamiento"] = $data["unFraccionamiento"][0]->fechaFraccionamiento;
				$data["fechaFraccArreglada"] = $this->fraccionamiento_model->arreglarFecha($data["fechaFraccionamiento"]);
				$data["fechaPmArreglada"] = $this->fraccionamiento_model->arreglarFecha($data["unFraccionamiento"][0]->PrescripcionMedica_fechaPrescripcion);
				$data["unaPmedica"] = $this->pmedica_model->getUnaPmedica($data["unFraccionamiento"][0]->PrescripcionMedica_idPrescripcionMedica);
				//var_dump($data["unaPmedica"]);
				$data["medico"] = $data["unaPmedica"][0]->medico;
				break;
			case 'fraccionamientos':
				$data['fracciones'] = $this->fraccionamiento_model->getFraccionesByIdProceso($param1);
				//var_dump($data['fracciones']);
				$data['tipoLeche'] = $param2;
				//var_dump($data['tipoLeche']);
				$data['nroProcF']= $param1;
				break;
			
			
			default:
				# code...
			break;
		}
		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/cabecera', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('fraccionamiento/'.$page, $data);
		$this->load->view('templates/pie', $data);
	}
	public function registrarFrac_sel()
	/* Funcion que capta el valor elegido del tipo de leche a buscar
	para obtener prescripciones medicas y realiza la busqueda  */
	{
		$tipoLeche = $this->input->post('tipoLeche');
		redirect('cfraccionamiento/view/verPmedicasPorTipo/'.$tipoLeche,'refresh');
	}

	
	//consumo de bebe
	public function consumoDeBeber()
	{
		 $cantFracciones = count ($this->input->post('fracSel'));
		 $fracciones = $this->input->post('fracSel');
		 $consumos = $this->input->post('consumo');
		 //recorre las fracciones y los consumos que va a tratar
		for ($i=0; $i < $cantFracciones ; $i++) {                                     
			$fraccion = $fracciones [$i];
			$consumo = $consumos[$i];
			//llama a guardar consumo funcion que guarda el consumo en cada fraccion
			$idFracc = array('idFracc' => $this->guardaConsumo($fraccion, $consumo));
					
		}
		redirect('cfraccionamiento/view/verTodosLosFraccionamientos/','refresh');

	}
	public function guardaConsumo($fraccion, $consumo)
	{
		//toma cada fraccion y obtiene el id y crea un arreglo con los consumos a cargar para cada fraccion 
		$unafraccion = $this->fraccionamiento_model->getUnFraccionamiento("$fraccion");
		$idF= $unafraccion[0]->idFraccionamiento;
		$unFraccionamiento = array(
			
			'consumido'=> $consumo[0],
			
			);
		 $this->fraccionamiento_model->updateFraccionConsumo($idF, $unFraccionamiento);
	}

	//1º se muestran las prescripciones medicas
	public function mostrarPmedicas()
	/*Se muestran cuales son las prescripciones médicas seleccionadas y los biberones
	disponibles para que el usuario elija.
	El usuario deebe elegir por cada toma que tengan las prescripciones médicas selecionadas
	el biberon con cual va a satisfacer dicha toma */
	{
		$data = array();
		foreach ($this->input->post('consSel') as $value) {
			$unaPm = $this->pmedica_model->getUnaPmedica("$value");
			$pmSelecc[] = array($unaPm[0]);
		}
		$data["pmSelec"] = $pmSelecc;
		$data['tipoLeche'] = $this->input->post('tipoLeche');
		$data['biberonesOk'] = $this->biberon_model->getBiberonesSinFraccionar($data['tipoLeche']);
		/* 
		Se carga la pagina encargada de mostrar los datos
		*/
			$this->load->view('templates/cabecera', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('fraccionamiento/verPmSeleccionadas', $data);
			$this->load->view('templates/pie', $data);
	}

	//2º se realiza el fraccionamiento
	public function realizarFracc()
	{
	//Calcular el nro de proceso de fraccion
		$idProceso = $this->fraccionamiento_model->getidProcesoMayor();
		$idProcesoFrac = $idProceso->nroProc + 1;
		//var_dump($idProcesoFrac);
	//fin calculo de proceso
		//contar la cantidad de elementos que se muestran en la vista - contar filas
		$cantidadElementos = count($this->input->post('bibSel'));
		//obtener el arreglo de elementos de los biberones seleccionados
		$biberones = $this->input->post('bibSel');
		//obtener el arreglo las p medicas seleccionadas
		$pmedicas = $this->input->post('pmSel');
		$fracciones = array();
		/*Para cada elemento i de la cantidad de elementos que existan, se obtiene la 
		pmedica seleccionada y el biberon que lo va a satisfacer */
		for ($i=0; $i < $cantidadElementos ; $i++) { 
			$biberon = $biberones[$i];
			$pmedica = $pmedicas[$i];
			//crear el fraccionamiento llamando a una funcion y pasando los ids
			$idFracc = array('idFracc' => $this->guardarFracc($pmedica, $biberon, $idProcesoFrac));
			//obtener un arreglo de los id de fraccionamientos creados
			$fracciones[] = $idFracc;
			//var_dump($fracciones);
			//cambiar estado de prescripcion médica
			$pmFraccionada = array('estadoPresMedica' => 'Fraccionado');
			$this->pmedica_model->updatePmedica($pmFraccionada, $pmedica);
		}

		$data["idFracc"] = $fracciones;
		$tipoLeche = $this->input->post('tipoLeche');
		
		redirect('cfraccionamiento/view/fraccionamientos/'.$idProcesoFrac.'/'.$tipoLeche,'refresh');
	}
	//3º guarda un fraccionamiento
	public function guardarFracc($idPm, $idBi,$idProcesoFrac)
	/*Se crea el fraccionamiento que se inserta en la bd */
	{
		//con esta forma se toma el formato de fecha
		$datestring = "%Y-%m-%d";
		//la funcion mdate con un solo parametro da la fecha actual
		$now = mdate($datestring);
		$unaPmedica = $this->pmedica_model->getUnaPmedica("$idPm");
		$volToma = $unaPmedica[0]->volumen;
		$unBiberon = $this->biberon_model->getUnBiberon($idBi);
		$unFraccionamiento = array(
			'fechaFraccionamiento' =>$now , 
			'volumen'=> $unaPmedica[0]->volumen,
			'PrescripcionMedica_idPrescripcionMedica' =>$unaPmedica[0]->idPrescripcionMedica ,
			'PrescripcionMedica_fechaPrescripcion' =>$unaPmedica[0]->fechaPrescripcion ,
			'BebeReceptor_idBebeReceptor' =>$unaPmedica[0]->BebeReceptor_idBebeReceptor ,
			'Biberon_idBiberon' => $unBiberon[0]->idBiberon,
			'nroProcesoFracc' => $idProcesoFrac

			);
		$idFraccionamiento = $this->fraccionamiento_model->insertFraccionamiento($unFraccionamiento);
		//cambio estado al biberon ya utilizado y modifica el volumen fraccionado
		$biberonUtilizado = array('estadoBiberon' => 'Fraccionado',
			'volFraccionado'=>$unBiberon[0]->volFraccionado + $unaPmedica[0]->volumen);
		$this->biberon_model->updateBiberon($biberonUtilizado, $unBiberon[0]->idBiberon);
		//insertar Fraccionamiento en la bd
		return $idFraccionamiento;
	}
	
	public function editarFraccionamiento()
	{
		$idFraccionamiento = $this->input->post("idFraccionamiento");
		$unFraccionamiento = array(
			'volumen' 			=> $this->input->post("volumen"),
			'consumido' 			=> $this->input->post("consumido"),
		);
		if ($this->fraccionamiento_model->updateFraccion($idFraccionamiento, $unFraccionamiento)) {
		redirect('cfraccionamiento/view/verUnFraccionamiento/'.$idFraccionamiento,'refresh');
		} else {
			redirect('','refresh');
		}


	}

}

/* End of file Cfraccionamiento.php */
/* Location: ./application/controllers/Cfraccionamiento.php */
