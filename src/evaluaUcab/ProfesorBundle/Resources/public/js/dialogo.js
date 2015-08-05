/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function alertDGC(mensaje,type,execute,id1,id2,id3)
{
    
    var dgcTiempo=500;
    var ventanaCS;

    if (type == 'prompt'){

        ventanaCS='<div class="dgcAlert">' +
            '<div class="dgcVentana" id="'+type+'">' +
            '<div class="dgcMensaje">'+mensaje+'<br>' +
            '<div class="no">No</div> ' ;


        if(execute == 'deleteExamen'){
            ventanaCS = ventanaCS +  '<div class="si" onclick="borrarExamen('+id1+')" >Sí</div></div></div></div>';
        }else if (execute == 'deletePregunta'){
            ventanaCS = ventanaCS +  '<div class="si" onclick="borrarPregunta('+id1+','+id2+')" >Sí</div></div></div></div>';
        }else if (execute == 'deleteRespuesta'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarRespuesta('+id2+','+id3+')" >Sí</div></div></div></div>';
        }else if (execute == 'deleteGrupo'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarGrupo('+id1+','+id2+')" >Sí</div></div></div></div>';
         }else if (execute == 'deleteEstudianteGrupo'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarEstudianteGrupo('+id1+','+id2+')" >Sí</div></div></div></div>';
          }else if (execute == 'deleteExamenPortafolio'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarExamenPortafolio('+id1+','+id2+')" >Sí</div></div></div></div>';
           }else if (execute == 'deletePortafolio'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarPortafolio('+id1+')" >Sí</div></div></div></div>';
          }else if (execute == 'updatePortafolio'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="updatePortafolio('+localStorage.editarPortafolio+')" >Sí</div></div></div></div>';        
          }else if (execute == 'deleteAsignacion'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarAsignacion('+id1+','+id2+')" >Sí</div></div></div></div>';
           }else if (execute == 'updateAsignacion'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="updateAsignacion('+id1+','+localStorage.editarAsignacion+')" >Sí</div></div></div></div>';          
          }else if (execute == 'deleteEstudianteSeccion'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarEstudianteSeccion('+id1+','+id2+')" >Sí</div></div></div></div>';
           }else if (execute == 'deleteDiario'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarDiario('+id1+','+id2+')" >Sí</div></div></div></div>';
           }else if (execute == 'deleteSeccion'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarSeccion('+id1+','+id2+')" >Sí</div></div></div></div>';
           }else if (execute == 'deleteMapaOutside'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarMapaMentalOutside('+id1+','+id2+')" >Sí</div></div></div></div>';
           }else if (execute == 'deleteMateria'){
           ventanaCS = ventanaCS +  '<div class="si" onclick="borrarMateria('+id1+','+id2+')" >Sí</div></div></div></div>';
           }
           else{
            ventanaCS = ventanaCS +  '<div class="si" >Sí</div></div></div></div>';
        }


    }else{
        ventanaCS='<div class="dgcAlert">' +
            '<div class="dgcVentana" id="'+type+'">' +
            '<div class="dgcMensaje">'+mensaje+'<br>' +
            '<div class="dgcAceptar">Aceptar</div></div></div></div>';

    }


    $('body').append(ventanaCS);
    var alVentana=$('.dgcVentana').height();
    var alNav=$(window).height();
    var supNav=$(window).scrollTop();
    $('.dgcAlert').css('height',$(document).height());
    $('.dgcVentana').css('top',((alNav-alVentana)/2+supNav-100)+'px');
    $('.dgcAlert').css('display','block');
    $('.dgcAlert').animate({opacity:1},dgcTiempo);

    $('.no,.dgcAceptar').click(function(e) {

        $('.dgcAlert').animate({opacity:0},dgcTiempo);
        setTimeout("$('.dgcAlert').remove()",dgcTiempo);
        if (execute == 'reload'){
            location.reload();
        }
    });

    $('.si').click(function(e) {

        $('.alert').animate({opacity:0},dgcTiempo);
        setTimeout("$('.alert').remove()",dgcTiempo);

    });

}



