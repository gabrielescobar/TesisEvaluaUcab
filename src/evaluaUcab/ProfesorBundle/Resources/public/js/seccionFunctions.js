/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {

    if (localStorage.deleteEstudianteSeccion) {
        $("#dialog").css("visibility", "visible");
        localStorage.removeItem("deleteEstudianteSeccion");
    }
    
     if (localStorage.deleteSeccion) {
        $("#dialog").css("visibility", "visible");
        localStorage.removeItem("deleteSeccion");
    }
});

function borrarEstudianteSeccion(idEstudianteSeccion, idSeccion) {
    $.ajax({
        type: 'post',
        url: Routing.generate('borrar_estudiante_seccion', {idEstudianteSeccion: idEstudianteSeccion}),
        success: function(data) {
            window.location.href = Routing.generate('alumnos_aceptados', {id: idSeccion});
            localStorage.deleteEstudianteSeccion = "true";
            console.log(data);
        },
        error: function(data2) {
          console.log(data2);
        }
    });
    
}

function borrarSeccion(idSeccion,idProfesor){
     
     $.ajax({
        type: 'post',
        url: Routing.generate('borrar_seccion', {idSeccion: idSeccion}),
        success: function(data) {
            window.location.href = Routing.generate('seccion', {idProfesor: idProfesor});
            localStorage.deleteSeccion = "true";
            console.log(data);
        },
        error: function(data2) {
          console.log(data2);
        }
    });
 }


