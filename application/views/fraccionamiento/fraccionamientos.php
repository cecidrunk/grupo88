<!--Realizar el Fraccionamiento -->
<aside class="right-side">
   <section class="content-header col-md-12">
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url();?>index.php/page/view/"><i class="fa fa-home"></i> Home</a></li>
         <li><a href="#">Fraccionamiento</a></li>
         <li class="active">Fraccionamientos recien Realizados </li>
      </ol>
      <h1>Fraccionamientos</h1>
      <p>Aqui se listan los fraccionamientos que acaba de realizar</p>
   </section>
   <!-- fin section header -->
   <section class="content col-md-12">
      <form id="formfraccionarBiberon" class="form-horizontal" data-toggle="validator"role="form" method="POST" 
      action="<?php echo base_url();?>index.php/">
         
         <div class="row">
            <div class="col-xs-12">
               <div class="box">
                <label>Tipo de leche:</label>
                <input id="tipoLeche" name="tipoLeche" value="<?php echo $tipoLeche; ?>">
                  <div class="box-body table-responsive">
                     <table id="example2" class="table table-responsive table-bordered table-striped">
                        <thead>
                           <tr>
                              <th>Id de Fraccionamiento</th>
                              <th>Fecha de Fraccionamiento</th>
                              <th>Volumen</th>
                              <th>Id P Medica</th>
                              <th>Fecha P Medica</th>
                              <th>Bebe Receptor</th>
                              <th>Biberon</th>
                              <th>Volumen Biberon</th>
                              <th>Volumen Desperdiciado</th>
                              <th></th>
                           </tr>
                        </thead>
                        <tbody>
                           <?php foreach ($fracciones as  $value):
                           $unBebe = $this->bebereceptor_model->getBebereceptor($value->BebeReceptor_idBebeReceptor);
                           $unBiberon = $this->biberon_model->getUnBiberon($value->Biberon_idBiberon);
                           //var_dump($unBiberon);
                           ?>

                           <tr>
                              <?php  
                                 $fechaArreglada = $this->pasteurizacion_model->arreglarFecha($value->fechaFraccionamiento);
                                 $fechaArreglada2 = $this->pasteurizacion_model->arreglarFecha($value->PrescripcionMedica_fechaPrescripcion);?>
                              <td colspan="" rowspan="" headers=""><?php echo $value->idFraccionamiento; ?></td> <!--id frac  -->
                              <td colspan="" rowspan="" headers=""><?php echo $fechaArreglada; ?></td><!-- fecha frac -->
                              <td colspan="" rowspan="" headers=""><?php echo $value->volumen ?></td><!-- volumen  -->
                              <td colspan="" rowspan="" headers=""><?php echo $value->PrescripcionMedica_idPrescripcionMedica; ?></td><!-- id pm -->
                              <td colspan="" rowspan="" headers=""><?php echo $fechaArreglada2; ?></td><!--  fecha pm -->
                              <td colspan="" rowspan="" headers=""><?php echo $unBebe[0]->apellidoBebeReceptor,' ',$unBebe[0]->nombreBebeReceptor ; ?></td><!--BEBE RECEP  -->
                              <td colspan="" rowspan="" headers=""><?php echo $unBiberon[0]->idBiberon; ?></td><!--  -->
                              <td colspan="" rowspan="" headers=""><?php echo $unBiberon[0]->volumenDeLeche; ?></td><!--  -->
                              <td colspan="" rowspan="" headers=""><?php echo $unBiberon[0]->volumenDeLeche - $unBiberon[0]->volFraccionado; ?></td><!--  -->
                              <td colspan="" rowspan="" headers="">
                                 <a href="<?php echo base_url()?>index.php/cpmedica/view/verUnaPmedica/<?php echo '';//$value->idPrescripcionMedica?>"
                                    class="btn btn-default btn-sm"
                                    title="ver prescripcion medica" role="button">
                                 <i class="fa fa-eye"></i>
                                 </a>
                              </td>
                            </tr>
                           <?php endforeach ?>
                        </tbody>
                     </table>
                  </div>
                  <!--fin de tabla -->
               </div>
               <!-- -->
            </div>
         </div>
         <div class="form-group pull-right content">
            <a class="btn btn-success btn-md" 
            href="<?php echo base_url(); ?>index.php/cfraccionamiento/view/verTodosLosFraccionamientos">Finalizar</a>
            <a class="btn btn-success btn-md" href="<?php echo base_url()?>etFraccionamiento.php?nroProcF=<?php echo $nroProcF ?>&idU=<?php echo  $this->session->userdata('idUsuario')?>" target="_blank">Imprimir Etiquetas Fraccionamiento</a>
         </div>

      </form>
   </section>
</aside>
<script src="<?php echo base_url();?>assets/internals/js/fraccionamientoinfo.js" type="text/javascript" charset="utf-8" async defer></script>


