<?php 
    headerAdmin($data); 
    
?>
    <main class="app-content">
      <div class="app-title">
        <div>
            <h1><i class="fas fa-user-tag"></i> <?= $data['page_title'] ?>
                <?php if($_SESSION['permisosMod']['w']){ ?>
                    <button type="button" class="btn btn-primary" id="btnnuevo">
                        Nuevo <i class="fas fa-plus-circle"></i>
                    </button>
                <?php } ?>
                </h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="<?= base_url(); ?>/modulos"><?= $data['page_title'] ?></a></li>
        </ul>
      </div>

        <div class="row">
            <div class="col-md-12">
              <div class="tile">
                <div class="tile-body">
                  <div class="table-responsive">
                    <table id="modulo_data" class="table table-hover table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10%;">ID</th>
                                <th style="width: 10%;">Titulo</th>
                                <th style="width: 15%;">Descripción</th>
                                <th class="d-none d-sm-table-cell" style="width: 10%;">Estado</th>
                                
                                <th class="text-center" style="width: 15%;">Accciones</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
        </div>

    </main>
    <!-- Contenido -->

    <div id="modalmodulo" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                <form method="post" id="modulo_form">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="titulo_modulo">Módulo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <input style="display: none" type="number" id="idmodulo" name="idmodulo">
                            <div class="form-group row">
                                <label class="col-12" for="titulo">Titulo</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="titulo" name="titulo" placeholder="" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12" for="descripcion">Descripción</label>
                                <div class="col-md-12">
                                    <textarea id="descripcion" name="descripcion" cols="40" rows="5" required></textarea>    
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12" for="status">status</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" id="status" name="status" placeholder="">
                                </div>
                            </div>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="action" id="#" value="add" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
<?php footerAdmin($data); ?>
