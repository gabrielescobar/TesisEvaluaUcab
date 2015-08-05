/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function() {

    if (localStorage.deleteAsignacion) {
        $("#dialog").css("visibility", "visible");
        localStorage.removeItem("deleteAsignacion");
    }
});

function borrarAsignacion(idPortafolio, idAsignacion) {
    $.ajax({
        type: 'post',
        url: Routing.generate('borrar_asignacion', {idAsignacion: idAsignacion}),
        success: function(data) {
            window.location.href = Routing.generate('asignaciones', {idPortafolio: idPortafolio});
            localStorage.deleteAsignacion = "true";
            console.log(data);
        },
        error: function(data2) {
          console.log(data2);
        }
    });

}




