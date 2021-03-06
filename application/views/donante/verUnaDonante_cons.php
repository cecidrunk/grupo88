<!-- Right side column. Contains the navbar and content of the page -->
<aside class="right-side">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>Datos de Donante</h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url();?>index.php/page/view/"><i class="fa fa-home"></i> Home</a></li>
      <li><a href="#">Consentimiento</a></li>
      <li class="active">Ver Datos de una Donante </li>
    </ol>
  </section>
  <section class="content" id="cont">
  <div class="form-group panel panel-default col-md-11" style="margin:auto">
      <!--Formulario para mostrar datos -->
   <form class="form-horizontal" role="form">
      <div class="form-group col-md-11" >
      </div>
    <div class="form-group">
      <label for="nroDonante" class="col-md-2 control-label">Nro de Donante</label>
      <div class="col-md-3">
        <input type="email" class="form-control" id="nroDonante" disabled="" value="<?php echo $unaDonante[0]->nroDonante;?>">
      </div>
      <!-- Nombre -->
        <label for="nombre" class="col-md-2 control-label">Nombre y Apellido</label>
        <div class="col-md-3">
          <input type="email" class="form-control" id="nombre" disabled=""
          value="<?php echo $unaDonante[0]->nombre, " ", $unaDonante[0]->apellido;?>">
        </div>
    </div>
      <!-- DNI -->
      <div class="form-group">
        <label for="dni" class="col-md-2 control-label">DNI</label>
        <div class="col-md-3">
          <input  class="form-control" id="dni" disabled=""
          value="<?php echo $unaDonante[0]->dniDonante;?>">
        </div>
      
       <!--Fecha de Nacimiento -->
      
        <label for="fechanac" class="col-md-2 control-label">Fecha de Nacimiento</label>
        <div class="col-md-3">
          <?php
          $fechaArray = explode('-', $unaDonante[0]->fechaNacDonante);
          if ($fechaArray[0]==0 && $fechaArray[1]==0){
            $fecha="";
          } else{
            $date = new DateTime();
            $date->setDate($fechaArray[0], $fechaArray[1], $fechaArray[2]);
            $fecha= $date->format('d/m/Y'); 
          }
          ?>
          <input  class="form-control" id="fechanac" disabled=""
          value="<?php echo $fecha;?>">
        </div>
      </div>
       <!--Tipo -->
      <div class="form-group">
        <label for="tipo" class="col-md-2 control-label">Tipo de Donante</label>
        <div class="col-md-3">
          <?php if ($unaDonante[0]->tipoDonante ==1) {
                # code...
                $tipo = "Externa";
              } else {
                # code...
                $tipo = "Interna"; 
              }
              ?>
          <input  class="form-control" id="tipo" disabled=""
          value="<?php echo $tipo?>">
        </div>
      <!-- Estado Civil -->
     
        <label for="estadoCivil" class="col-md-2 control-label">Estado Civil</label>
        <div class="col-md-3">
          <input  class="form-control" id="estadoCivil" disabled=""
          value="<?php echo $unaDonante[0]->estadoCivil;?>">
        </div>
      </div>
      <!-- Telefono -->
      <div class="form-group">
        <label for="telefon" class="col-md-2 control-label">Telefono</label>
        <div class="col-md-3">
          <input  class="form-control" id="telefono" disabled=""
          value="<?php echo $unaDonante[0]->telefonoDonante;?>">
        </div>
      
      <!--Email -->
      
        <label for="email" class="col-md-2 control-label">Correo Electronico</label>
        <div class="col-md-3">
          <input  class="form-control" id="email" disabled=""
          value="<?php echo $unaDonante[0]->emailDonante;?>">
        </div>
      </div>
       <!-- Ocupacion -->
      <div class="form-group">
        <label for="ocupacion" class="col-md-2 control-label">Ocupacion</label>
        <div class="col-md-3">
          <input type="email" class="form-control" id="ocupacion" disabled=""
          value="<?php echo $unaDonante[0]->ocupacion;?>">
        </div>
      
      <!-- Estudios Alcanzados -->
      
        <label for="estudios" class="col-md-2 control-label">Estudios Alcanzados</label>
        <div class="col-md-3">
          <input type="email" class="form-control" id="estudios" disabled=""
          value="<?php echo $unaDonante[0]->nivelEstudio;?>">
        </div>
      </div>
    </form> <!-- finaliza formulario para mostrar datos -->
    <div class="content pull-right-side col-md-12">
      <div class="form-group" style="float: right">
      <a class="btn btn-primary btn-md" href="<?php echo base_url()?>index.php/consentimiento/view/verConsentimientos/">Volver</a>
      <a href="<?php echo base_url()?>index.php/cdonante/view/editarDonante/<?php echo $unaDonante[0]->nroDonante;?>">
        <button type="button" class="btn btn-success btn-md">Editar Donante</button>
      </a>
      <a href="<?php echo base_url()?>index.php/cbebe/view/bebeAsociado_cons/<?php echo $unaDonante[0]->nroDonante;?>">
        <button type="button" class="btn btn-success btn-md">Agregar Bebe</button>
      </a>
    </div>
    </div>
  </div>
  </section>




<!--Cierra el aside inicial -->
  </aside><!-- /.right-side -->