/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
   
    if (localStorage.deleteRespuesta){
        $("#dialog").css("visibility","visible");
        localStorage.removeItem("deleteRespuesta");
    }
    
 
});

 function borrarRespuesta(idPregunta,idRespuesta){
        
            $.ajax({
             type: 'post',
             url: Routing.generate('eliminar_respuesta', { idPregunta: idPregunta, idRespuesta: idRespuesta }),
                    
             success: function(data) {
               
              window.location.href = Routing.generate('fin_respuestas',{id: idPregunta});
              localStorage.deleteRespuesta = "true";
           },
             error: function(data2){
               console.log(data2);
             }
         });
   } 
