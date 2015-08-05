/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
   
    if (localStorage.deleteDiario){
        $("#dialog").css("visibility","visible");
        localStorage.removeItem("deleteDiario");
    }
});

 function borrarDiario(idPortafolio,idDiario){
            $.ajax({
             type: 'post',
             url: Routing.generate('eliminar_diario', { idDiario: idDiario }),
                    
             success: function(data) {
              window.location.href = Routing.generate('diarios',{'idPortafolio':idPortafolio});
              localStorage.deleteDiario = "true";
           },
            error: function (data2){
              console.log(data2);
            }
         });
         
   } 

