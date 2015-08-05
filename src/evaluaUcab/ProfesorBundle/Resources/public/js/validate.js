/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function validate(){

    var extensions = new Array("jpg","jpeg","gif","png");
    var image_file = document.getElementById('evaluaUcab_profesorbundle_profesortype_file').value;
    var image_length = document.getElementById('evaluaUcab_profesorbundle_profesortype_file').value.length;
    var pos = image_file.lastIndexOf('.') + 1;
    var ext = image_file.substring(pos, image_length);
    var final_ext = ext.toLowerCase();
    var aux = 0;
    
     if(image_file ==''){
        return true;
    }

    for (i = 0; i < extensions.length; i++){
        if(extensions[i] == final_ext){     
              return true;
        }
    }
    
  alert("Error en foto");
    return false;

}

function validarArchivo(){
   aler("dgd");
    var extensions = new Array("jpg","jpeg","gif","png","pdf");
    var image_file = document.getElementById('evaluaUcab_profesorbundle_profesortype_file').value;
    var image_length = document.getElementById('evaluaUcab_profesorbundle_profesortype_file').value.length;
    var pos = image_file.lastIndexOf('.') + 1;
    var ext = image_file.substring(pos, image_length);
    var final_ext = ext.toLowerCase();
    var aux = 0;
    
     if(image_file ==''){
         alert("Seleccione el archivo");
        return false;
    }

    for (i = 0; i < extensions.length; i++){
        if(extensions[i] == final_ext){     
              return true;
        }
    }
    
  alert("Error en archivo");
    return false;

}

