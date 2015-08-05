/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
   
    if (localStorage.deleteMapaOutside){
        $("#dialog").css("visibility","visible");
        localStorage.removeItem("deleteMapaOutside");
    }
});

 function borrarMapaMentalOutside(idMapa,idEstudiante){
            $.ajax({
             type: 'post',
             url: Routing.generate('borrar_mapamental_outside', { idMapa: idMapa }),
                    
             success: function(data) {
              window.location.href = Routing.generate('mapas_mentales',{'idEstudiante':idEstudiante});
              localStorage.deleteMapaOutside = "true";
           },
            error: function (data2){
              console.log(data2);
            }
         });
         
   } 

