/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function importarExamen(){
    
var select = document.getElementById("examenes");
var idExamen = select.options[select.selectedIndex].value;

var fechaCierre  = document.getElementById("fechaCierre");
var duracion = document.getElementById("duracion");

      $.ajax({
             type: 'post',
             url: Routing.generate('eliminar_pregunta', { idExamen: idExamen, idPregunta: idPregunta }),
                    
             success: function(data) {
               
              window.location.href = Routing.generate('fin_preguntas', {idExamen: idExamen});
              localStorage.deletePregunta = "true";
           },
             error: function(data2){
               console.log(data2);
             }
         });
    
    
}


