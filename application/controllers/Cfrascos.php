<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cfrascos extends CI_Controller {
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



	public function view($page="home", $param="", $param2="")
	{
		if ( ! file_exists(APPPATH.'/views/frascos/'.$page.'.php'))
		{
			// Whoops, we don't have a page for that!
			show_404();
		}
		switch ($page) {
			case 'verFrascos':
			$data["frascos"] = $this->frascos_model->mostrarFrascos();
			break;
			case 'bajaFrasco':
			$data["unFrasco"] = $this->frascos_model->getFrasco($param);
			$data["tipoLeche"] = $this->bebeasociado_model->getTipoDeLeche($data["unFrasco"][0]->Consentimiento_por_HojaDeRuta_Consentimiento_nroConsentimiento,$data["unFrasco"][0]->fechaExtraccion);
			$data["unaDonante"]= $this->frascos_model->buscarDonanteFrasco($data["unFrasco"][0]->nroFrasco);
			break;
			case 'ingresoFrascos':
			$data['result'] = $this->frascos_model->buscarDonanteFrasco(trim($param2));
			$data['unahr'] = $param;
			$data['nrofrasco'] = $param2;
			break;
			case 'verUnFrasco':
			$data["unFrasco"] = $this->frascos_model->getFrasco($param);
			$data["tipoLeche"] = $this->bebeasociado_model->getTipoDeLeche($data["unFrasco"][0]->Consentimiento_por_HojaDeRuta_Consentimiento_nroConsentimiento,$data["unFrasco"][0]->fechaExtraccion);
			$data["unaDonante"]= $this->frascos_model->buscarDonanteFrasco($data["unFrasco"][0]->nroFrasco);
			break;
			case 'registrarAnalisisFrasco':
			$data["unFrasco"] = $this->frascos_model->getFrasco($param);
			$data["tipoLeche"] = $this->bebeasociado_model->getTipoDeLeche($data["unFrasco"][0]->Consentimiento_por_HojaDeRuta_Consentimiento_nroConsentimiento,$data["unFrasco"][0]->fechaExtraccion);
			$data["unaDonante"]= $this->frascos_model->buscarDonanteFrasco($data["unFrasco"][0]->nroFrasco);
			break;
			case 'editarFrasco':
			$data["unFrasco"] = $this->frascos_model->getFrasco($param);
			$data["tipoLeche"] = $this->bebeasociado_model->getTipoDeLeche($data["unFrasco"][0]->Consentimiento_por_HojaDeRuta_Consentimiento_nroConsentimiento,$data["unFrasco"][0]->fechaExtraccion);
			$data["unaDonante"]= $this->frascos_model->buscarDonanteFrasco($data["unFrasco"][0]->nroFrasco);
			break;
			case 'verFrascosPorDonante':
				$data['frascos'] = $this->frascos_model->getFrascosVaciosPorCon($param);
				if ($data['frascos']){
					$data['unConsentimiento'] = $this->consentimiento_model->getConsentimiento($param);
					$data['unaDonante'] = $this->donantes_model->getDonante($data['unConsentimiento'][0]->Donante_nroDonante);
					$data['unaHr'] = $this->hojaruta_model->getUnaHRuta($data['frascos'][0]->Consentimiento_por_HojaDeRuta_HojaDeRuta_idHojaDeRuta);
					}
			break;
			case 'verFrascosPasteurizados':
			$data["frascos"] = $this->frascos_model->mostrarFrascosPasteurizados();
			//if ($data['frascos']){
			//    $data["todosFrascos"] = $this->frascos_model->getFrasco($data["frascos"][0]->nroFrasco);
			break;
			default:
				# code...  
			break;
		}
		$data['title'] = ucfirst($page); // Capitalize the first letter

		$this->load->view('templates/cabecera', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('frascos/'.$page, $data);
		$this->load->view('templates/pie', $data);
	}

				public function buscarDonanteFrasco() 
				{
					$nroFrasco = $this->input->get('nroFrasco');
					$nroHR = $this->input->get('nroHR');
					redirect('cfrascos/view/ingresoFrascos/'.$nroHR.'/'.$nroFrasco ,'refresh');
				}

				public function guardarFrasco(){

					 $fechaArray = explode('/', $this->input->post("fextraccion"));
					  $date = new DateTime();
					  $date->setDate($fechaArray[2], $fechaArray[1], $fechaArray[0]);
					  $fecha= $date->format('Y-m-d');
					  $numFrasco = $this->input->post("nroFrasco");
					  $otroFrasco = $this->frascos_model->getFrasco($numFrasco);
					  $tipoLeche = $this->bebeasociado_model->getTipoDeLeche($otroFrasco[0]->Consentimiento_por_HojaDeRuta_Consentimiento_nroConsentimiento, $fecha);
					$estadoFrasco = $this->frascos_model->getEstadoSerologia($numFrasco); 
					//var_dump($estadoFrasco);
					$unFrasco = array(
					'fechaExtraccion' =>$fecha,
					'volumenDeLeche' =>$this->input->post("vol"),
					'tipoDeLeche' => $tipoLeche,
					'HRVuelta' =>$this->input->post("nroHR"),
					'estadoDeFrasco' => $estadoFrasco,
					);
					$data['title'] = ucfirst("home");
					
					if ($this->frascos_model->updateFrasco($unFrasco, $numFrasco )) {
						if ($this->input->post("guardarYterminar")!== null) {
							redirect('cfrascos/view/verFrascos','refresh');
						} else {
							redirect('cfrascos/view/ingresoFrascos/'.$this->input->post("nroHR") ,'refresh');
						}
						} else 
					{
						redirect('','refresh');
					}
			       }
					public function guardarResultados(){
						if ($this->input->post("estadoFrasco") == "ConSerologiaOk") {
							$nuevoEstado = "OK";
						}else{
							$nuevoEstado = $this->input->post("estadoFrasco");
						}
					 $unFrasco= array(
					 	'estadoDeFrasco' =>$nuevoEstado,
					 	'motivoRechazoFrasco' =>$this->input->post("motivoBaja"),
					 	'nivelDeAcidez' =>$this->input->post("acidez"),
					 	'hematocritos' =>$this->input->post("hematocritos"),
					 	 );
					 //var_dump($unFrasco);
					 $data['title'] = ucfirst("home");
					 $numFrasco = (int)$this->input->post("nroFrasco");
							 if ($this->frascos_model->updateFrasco($unFrasco, $numFrasco)) {
								redirect('cfrascos/view/verFrascos','refresh');
								} else 
							{
								redirect('','refresh');
							}
					}

					public function editarFrasco(){
						/*if ($this->input->post("estadoFrasco") == "ConSerologiaOk") {
							$nuevoEstado = "OK";
						}else{
							$nuevoEstado = $this->input->post("estadoFrasco");
						}*/
						 $fechaArray = explode('/', $this->input->post("fextraccion"));
						  $date = new DateTime();
						  $date->setDate($fechaArray[2], $fechaArray[1], $fechaArray[0]);
						  $fecha= $date->format('Y-m-d');

						  $numFrasco = $this->input->post("nroFrasco");
					 	 $otroFrasco = $this->frascos_model->getFrasco($numFrasco);
					 	 $tipoLeche = $this->bebeasociado_model->getTipoDeLeche($otroFrasco[0]->Consentimiento_por_HojaDeRuta_Consentimiento_nroConsentimiento, $fecha);
						//$estadoFrasco = $this->frascos_model->getEstadoSerologia($numFrasco);
						
						$unFrasco = array(
						'fechaExtraccion' =>$fecha,
						'volumenDeLeche' =>$this->input->post("vol"),
						'tipoDeLeche' => $tipoLeche,
						'nivelDeAcidez' =>$this->input->post("acidez"),
						'hematocritos' =>$this->input->post("hematocritos"),
						'motivoRechazoFrasco' =>$this->input->post("motivoBaja"),
						'estadoDeFrasco' => $this->input->post("estadoFrasco"),
						);

						$data['title'] = ucfirst("home");
						
						 if ($this->frascos_model->updateFrasco($unFrasco, $numFrasco)) {
							redirect('cfrascos/view/verFrascos','refresh');
							} else 
						{
							redirect('','refresh');
						}

						}
						
						public function rechazarFrasco(){  
							 $unfrascoRechazado= array(
							 	'estadoDeFrasco' => "Rechazado",
							 	'motivoRechazoFrasco' => $this->input->post("motivoBaja"),
							 	 );
							 $data['title'] = ucfirst("home");
							 $nroFrasco =(int)$this->input->post("nroFrasco");
							 if ($this->frascos_model->updateFrasco($unfrascoRechazado, $nroFrasco )) {
								redirect('cfrascos/view/verFrascos','refresh');
								} else 
							{
								redirect('','refresh');
							}
						}
}


/* End of file Cfrascos.php */
/* Location: ./application/controllers/Cfrascos.php */