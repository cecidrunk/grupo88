<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Centros_model extends CI_Model {

	public function insertNewCentro($centro)
	{
		try {
			$this->db->insert('centrorecoleccion', $centro);

			return $this->db->insert_id();
		} catch (Exception $e) {
			return false;
		}
	}

	public function getAllCentros()
	{
		try {
			return $this->db->get('centrorecoleccion',0 ,10 )->result();
		} 	catch (Exception $e) {
			return false;
		}
	}

	public function deleteCentro($idCentro)
	{
		try {
			$this->db->where('idCentroRecoleccion', $idCentro);
			$this->db->delete('centrorecoleccion');
			return TRUE;
		} catch (Exception $e) {
			return FALSE;
		}
	}

	public function updateCentro($centros, $nroCentro)
	{
		try {
			$this->db->where('idCentroRecoleccion', $nroCentro);
			return $this->db->update('centrorecoleccion', $centros);
		} catch (Exception $e) {
			return false;
		}
		
	}

	public function getCentro($idCentro)
	{
		try {
			$this->db->where('idCentroRecoleccion', $idCentro);
			return $this->db->get('centrorecoleccion')->result();
		} catch (Exception $e) {
			return false;
		}
	}


}

/* End of file Donantes_model.php */
/* Location: ./application/models/Donantes_model.php */