/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$( document ).ready(function() {
   
    if (localStorage.deleteGrupo){
        $("#dialog").css("visibility","visible");
        localStorage.removeItem("deleteGrupo");
    }
    
     if (localStorage.editGrupo){
        $("#dialog2").css("visibility","visible");
        localStorage.removeItem("editGrupo");
    }
});

 function borrarGrupo(idGrupo,idPortafolio){
            $.ajax({
             type: 'post',
             url: Routing.generate('borrar_grupo', {idPortafolio:idPortafolio, idGrupo:idGrupo}),
                    
             success: function(data) {
              window.location.href = Routing.generate('grupos', {idPortafolio: idPortafolio});
              localStorage.deleteGrupo = "true";
           }, error: function(data2){
               console.log(data2);
             }
         });
         
   } 
   
    function borrarEstudianteGrupo(idEstudianteGrupo,idPortafolio){
            $.ajax({
             type: 'post',
             url: Routing.generate('borrar_estudiante_grupo', { idEstudianteGrupo: idEstudianteGrupo }),
                    
             success: function(data) {
              window.location.href = Routing.generate('grupos', {idPortafolio: idPortafolio});
              //localStorage.editGrupo = "true";
           }, error: function(data2){
               console.log(data2);
             }
         });
         
   } 

