/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
   
    if (localStorage.deletePregunta){
        $("#dialog").css("visibility","visible");
        localStorage.removeItem("deletePregunta");
    }
});

 function borrarPregunta(idExamen,idPregunta){
     
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



