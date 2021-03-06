<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cpasteurizacion extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
	}

	public function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		if (!isset($is_logged_in) || $is_logged_in != true) {
			$data = array();
         	
			redirect(base_url(),'refresh');
		}
	}

	public function view($page="home", $param="")
	{
		if ( ! file_exists(APPPATH.'/views/pasteurizacion/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}

		switch ($page) {
			case 'nuevaPasteurizacion':
			$data["frascos"] = $this->frascos_model->getFrascosApasteurizar();
			$data["unId"] = $param;
			//var_dump($param);
			break;
			case 'mostrarPasteurizacion':
			$data["unaPasteurizacion"] = $this->pasteurizacion_model->getUnaPasteurizacion($param);
			$data["biberones"] = $this->biberon_model->mostrarBiberones($param);
			break;
			case 'verUnaPasteurizacion':
			$data["unaPasteurizacion"] = $this->pasteurizacion_model->getUnaPasteurizacion($param);
			$data["biberones"] = $this->biberon_model->mostrarBiberones($param);
			break;
			case 'verPasteurizaciones':
			$data["pasteurizaciones"] = $this->pasteurizacion_model->getAllPasteurizacion();
			break;
			case 'editarPasteurizacion':
			$data["unaPasteurizacion"] = $this->pasteurizacion_model->getUnaPasteurizacion($param);
			break;
			case 'bajaPasteurizacion':
			$data["unaPasteurizacion"] = $this->pasteurizacion_model->getUnaPasteurizacion($param);
			break;
			default:
				#code;
			break;
		}
		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/cabecera', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('pasteurizacion/'.$page, $data);
		$this->load->view('templates/pie', $data);
	}

		public function registrarPasteurizacion()
		{
			 
			$fechaArray = explode('/', $this->input->post("fpasteurizacion"));
					  $date = new DateTime();
					  $date->setDate($fechaArray[2], $fechaArray[1], $fechaArray[0]);
					  $fecha= $date->format('Y-m-d');

			$unaPasteurizacion = array(
				'fechaPasteurizacion' =>$fecha, 
				'responsable' =>$this->input->post("responsable"), 
			);

			$idPasteurizacion = $this->pasteurizacion_model->insertPasteurizacion($unaPasteurizacion);
		redirect('Cpasteurizacion/view/nuevaPasteurizacion/'.$idPasteurizacion,'refresh');	

		}

	public function agregarFrascos()
	{		
		  $cant = count($this->input->post("consSel[]"));
		  if ($cant > 0) {
			$dato['elemSelec'] = $this->input->post("consSel[]");
			$dato['idPast'] = $this->input->post("idPasteurizacion");
			$this->load->view('templates/cabecera', $dato);
			$this->load->view('templates/menu', $dato);
			$this->load->view('pasteurizacion/otraForma', $dato);
			$this->load->view('templates/pie', $dato);
			}else{
				echo '<script language="javascript">alert("No has seleccionado un frasco para un biberon");</script>'; 
		  			redirect('Cpasteurizacion/view/nuevaPasteurizacion/'.$this->input->post("idPasteurizacion"),'refresh');
		  		}
		  }
		 
		  public function crearBiberon()
		  {
		  	$fSeleccionados = $this->input->post("frascoSelec");
		  	$volumenDiv = $this->input->post("volBib");
		  	$idPasteurizacion = $this->input->post("idPasteurizacion");

		  	$cant = 0;
		  	$j = 0;
		  	while ($fSeleccionados[$cant]<> 0) {
		  		$cant= $cant + 1;
		  		$j = $j + 1;
		  	}
		  	for ($i=0; $i < $cant ; $i++) { 
				 	 $frasco = $fSeleccionados[$i];
			  		 $volumen = $volumenDiv[$i];
			  		 $bib = array('bib' => $this->guardaBiberon($frasco, $volumen,$idPasteurizacion));
		  			}
			for ($j=0; $j < $cant; $j++) { 
				$frasco = $fSeleccionados[$j];
				$unFrasco = $this->frascos_model->getFrasco("$frasco");
				$idFrasco = $unFrasco[0]->nroFrasco;
				$nuevoEstado = array(
				'estadoDeFrasco' => "Pasteurizado",
				 );
			  $guardaEstado = $this->frascos_model->updateFrasco($nuevoEstado, $idFrasco);
			}
		  		redirect('Cpasteurizacion/view/mostrarPasteurizacion/'.$idPasteurizacion,'refresh');
		  	}


		public function guardaBiberon($frasco, $volumen,$idPasteurizacion)
		{   $unaPasteurizacion = $this->pasteurizacion_model->getUnaPasteurizacion("$idPasteurizacion"); 
			$unFrasco = $this->frascos_model->getFrasco("$frasco");
			$idFrasco = $unFrasco[0]->nroFrasco;
		if ($volumen == 0) {
			$unVol = $unFrasco[0]->volumenDeLeche;
		} else {
			$unVol = $volumen;
		}
		
			$unBiberon = array(
				'tipoDeLeche' => $unFrasco[0]->tipoDeLeche,
				'estadoBiberon' => $unFrasco[0]->estadoDeFrasco,
				'volumenDeLeche' => $unVol,
				'frasco_idFrasco'=>$idFrasco,
				'pasteurizacion_idPasteurizacion'=> $unaPasteurizacion[0]->idPasteurizacion,
				 );
			$idBiberon = $this->biberon_model->insertNewBiberon($unBiberon);
			//ACTUALIZA ESTADO DE FRASCO PARA NO SER LISTADO EN EL PROXIMO PROCESO DE PASTEURIZACION
			//$nuevoEstado = array(
			//	'estadoDeFrasco' => "Pasteurizado",
			//	 );
			//$guardaEstado = $this->frascos_model->updateFrasco($nuevoEstado, $idFrasco);
		}
		 //----------------------------------------------------
		public function editarPasteurizacion($value='')
		{
			$fechaArray = explode('/', $this->input->post("fpasteurizacion"));
						  $date = new DateTime();
						  $date->setDate($fechaArray[2], $fechaArray[1], $fechaArray[0]);
						  $fechaPas= $date->format('Y-m-d');

			$pasteurizacion = array(
				'fechaPasteurizacion' => $fechaPas ,
				'responsable' => $this->input->post("responsable"),
				 );

			$data['title'] = ucfirst("home");

			$nroPasteurizacion = $this->input->post("nroPasteurizacion");
				
				if($this->pasteurizacion_model->updatePasteurizacion($pasteurizacion, $nroPasteurizacion)) {
					redirect('cpasteurizacion/view/verPasteurizaciones','refresh');
					} else 
				{
					redirect('','refresh');
				}


		}

		public function cancelaIngreso()
		{
			$nroPasteurizacion = $this->input->post("idPasteurizacion");
			$this->pasteurizacion_model->deletePasteurizacion($nroPasteurizacion);
			redirect('cpasteurizacion/view/verPasteurizaciones','refresh');
		}



}