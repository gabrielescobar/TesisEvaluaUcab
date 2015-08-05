/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
   
    if (localStorage.deleteExamen){
        $("#dialog").css("visibility","visible");
        localStorage.removeItem("deleteExamen");
    }
});

 function borrarExamen(id){
            $.ajax({
             type: 'post',
             url: Routing.generate('borrar_examen', { idExamen: id }),
                    
             success: function(data) {
              window.location.href = Routing.generate('examenes');
              localStorage.deleteExamen = "true";
           },
            error: function (data2){
              console.log(data2);
            }
         });
         
   } 


