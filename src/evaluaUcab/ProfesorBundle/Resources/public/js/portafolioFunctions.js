/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
   
    if (localStorage.deleteExamenPortafolio){
        $("#dialog").css("visibility","visible");
        localStorage.removeItem("deleteExamenPortafolio");
    }
    
 });
 
 
 function updatePortafolio(idPortafolio){
     var f = document.getElementById('fechaCierre').value;
     console.log(f);
      
       $.ajax({
             type: 'post',
             url: Routing.generate('update_portafolio', { idPortafolio: idPortafolio, value:f }),
                    
             success: function(data) {
             console.log(data);
              window.location.href = Routing.generate('portafolios');
            
           }, error: function(data2){
               console.log(data2);
             }
         });
         
         localStorage.removeItem("editarPortafolio");
     
 }
    
     
     function editarPortafolio(idPortafolio){
       
      localStorage.editarPortafolio = idPortafolio;
      var f = document.getElementById('fechaCierre');
      
       $.ajax({
             type: 'post',
             url: Routing.generate('ajax_max_fecha', { idPortafolio: idPortafolio }),
             dataType: 'json',       
             success: function(data) {
                console.log(data[0].fechaMaxima);
                //seteo el rango de fecha minima del popup editar fecha cierre
               $('#fechaCierre').prop('min',data[0].fechaMaxima);
               //document.getElementById('viewTipoEvento').value;
               $('#fechaCierre').prop('value',data[0].fechaMaxima);
               f.value = data[0].fechaMaxima;
               console.log(f.value);
             
           }, error: function(data2){
               console.log(data2);
               var today = new Date();
               var dd = today.getDate();
               var mm = today.getMonth()+1; //January is 0!
               var yyyy = today.getFullYear();
                 if(dd<10){
                    dd='0'+dd
                  } 
                 if(mm<10){
                     mm='0'+mm
                   } 
                 var today = yyyy+'-'+mm+'-'+dd;
               $('#fechaCierre').prop('min',today);
               f.value = today;
             }
         });
      
      
  }

function borrarPortafolio(idPortafolio){
   $.ajax({
             type: 'post',
             url: Routing.generate('borrar_portafolio', { idPortafolio: idPortafolio }),
                    
             success: function(data) {
             window.location.href = Routing.generate('portafolios');
             localStorage.deletePortafolio = "true";
           }, error: function(data2){
               console.log(data2);
             }
         });
         
}

 function borrarExamenPortafolio(idExamenPortafolio,idPortafolio){
            $.ajax({
             type: 'post',
             url: Routing.generate('borrar_examen_portafolio', { idExamenPortafolio: idExamenPortafolio }),
                    
             success: function(data) {
             window.location.href = Routing.generate('portafolio_examenes_profesor',{idPortafolio:idPortafolio});
             localStorage.deleteExamenPortafolio = "true";
           }, error: function(data2){
               console.log(data2);
             }
         });
         
   } 


