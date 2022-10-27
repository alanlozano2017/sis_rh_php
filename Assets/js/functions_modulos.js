var tabla;

function init(){
    $("#modulo_form").on("submit",function(e){
        guardaryeditar(e);	
    });


    
}

$(document).ready(function(){

    tabla= $('#modulo_data').DataTable({
        "aProcessing": true,//Activamos el procesamiento del datatables
        "aServerSide": true,//Paginación y filtrado realizados por el servidor
        dom: 'Bfrtip',//Definimos los elementos del control de tabla
        "ajax":{
        url: base_url+"/Modulos/listar/",
        type : "post",					
            error: function(e){
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 10,
        "order": [[ 0, "desc" ]],
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MODULO_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {          
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
    });

    




});

$(document).on("click","#btnnuevo", function(){
    $('#titulo_modulo').html('CRUD-Nuevo Registro');

    $('#modulo_form')[0].reset();
    $('#modalmodulo').modal('show');
    $('#idmodulo').val('');
    $('#titulo').prop( "disabled", false );
    $('#descripcion').prop( "disabled", false );
    $('#status').prop( "disabled", false );
});


function verModulo(idmod){
    console.log("ver  "+idmodulo);
    $.post(base_url+'/Modulos/mostrarModulo/',{idmodulo : idmod}, function(data, status){
        console.log("editar  "+data);
        // console.log(base_url);
        data = JSON.parse(data);
        $('#titulo_modulo').html('CRUD-Editar Registro');
        $('#titulo').val(data.titulo).prop( "disabled", true );
        $('#descripcion').val(data.descripcion).prop( "disabled", true );
        $('#status').val(data.status).prop( "disabled", true );
        $('#idmodulo').val('').prop( "disabled", true );
   
    }); 
    $("#modalmodulo").modal('show');	
}

function editar(idmod){
    console.log("editar  "+idmodulo);
    $.post(base_url+'/Modulos/mostrarModulo/',{idmodulo : idmod}, function(data, status){
        console.log("editar  "+data);
        // console.log(base_url);
        data = JSON.parse(data);
        $('#titulo_modulo').html('CRUD-Editar Registro');
        $('#titulo').val(data.titulo).prop( "disabled", false );
        $('#descripcion').val(data.descripcion).prop( "disabled", false );
        $('#status').val(data.status).prop( "disabled", false );
        $('#idmodulo').val(data.idmodulo).prop( "disabled", false );
    }); 
    $("#modalmodulo").modal('show');	
}
function eliminar(idmodulo){

    //let idUsuario = id_usuario;
    swal({
        title: "Eliminar Módulo",
        text: "Desea Eliminar el Módulo?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) 
        {
            $.post(base_url+'/Modulos/delModulo',{idmodulo : idmodulo}, function(data, status){
                console.log("eliminar 2  "+data);
                $('#modulo_data').DataTable().ajax.reload();	
                swal('Eliminado!','Registro Eliminado Correctamente.','success');
            }); 
        }

    });

}


function guardaryeditar(e){
    e.preventDefault();
	var formData = new FormData($("#modulo_form")[0]);
    console.log("form data :");
    for (var pair of formData.entries()) {
        console.log(pair[0]+ ', ' + pair[1]); 
    }
    
    $.ajax({
        url: base_url+'/Modulos/guardaryeditar/',
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos){    
            console.log(datos);
            $('#modulo_form')[0].reset();
            $("#modalmodulo").modal('hide');
            $('#modulo_data').DataTable().ajax.reload();	
            swal('Guardado!','Registro Guardado Correctamente.','success')
        }
    }); 
}

init();