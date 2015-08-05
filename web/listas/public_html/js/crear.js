function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}
var fila = 1;
$(".addCF").click(function () {
    if (fila != 15) {
        $("#e").append('<div class="section group" id="rubric' + fila + '">' +
                '<div class="col span_1_of_2">' +
                '<textarea class="rubric-info" placeholder="Nombre de la Categoria" name="rubro"></textarea>' +
                '</div>' +
                '<div class="col span_1_of_2" style="text-align: center;">' +
                '<input style="border-radius: 6px;width: 300px;" type="number" class="rubric-value" placeholder="Puntuación" name="fname">' +
                '<br>' +
                '</div>' +
                '</div>');
        fila = fila + 1;
    }
    else {
            $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>No puede tener más de 15 rubros en su lista de cotejo</div>");
    
    }
});

$(".deleteCF").click(function () {
    if (fila != 1) {
        fila = fila - 1;
        $("#rubric" + fila).remove();
    }
    else {

            $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Debe tener al menos un criterio en su lista de cotejo</div>");
    
    }
});



function rubricCreate() {
    var error = false;
    var duplicate = [];
    var total=0;
    var rubros = '{"titulo": "' + document.getElementById("titulo").value + '", "rubros": [{';
    for (i = 0; i < fila; i++) {
        rubros = rubros + '"caracteristica' + i + '": [';
        for (j = 0; j < $("#rubric" + i + " :input").length; j++) {

            if (document.getElementById("titulo").value == "") {
              
                $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Debe ingregar el título de la lista de cotejo</div>");
    
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].type === "number" && isNaN(parseFloat($("#rubric" + i + " :input")[j].value))) {
                
                 $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Las puntuaciones deben ser valores numéricos</div>");
    
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].type === "number" && ($("#rubric" + i + " :input")[j].value < 0)) {
                
                 $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Las puntuaciones deben ser valores numéricos Positivos</div>");
    
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].value == "") {
               
                $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>No se aceptan campos en blanco</div>");
    
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].type === "number")
            {
              
                    rubros = rubros + '{"value": ' + $("#rubric" + i + " :input")[j].value + '}';
                    if (j + 1 < $("#rubric" + i + " :input").length - 1)
                        rubros = rubros + ',';
                        total=total+ parseFloat($("#rubric" + i + " :input")[j].value);
                
            }
            else {
                rubros = rubros + '{"value": "' + $("#rubric" + i + " :input")[j].value + '"}';
                if (j + 1 < $("#rubric" + i + " :input").length )
                    rubros = rubros + ',';
            }
        }
        rubros = rubros + ' ]';
        if (i + 1 < fila)
            rubros = rubros + ',';
        duplicate = [];
        if (error)
            break;
    }
    rubros = rubros + '}]}';
    if (!error){
       // alert(getURLParameter('hola'));
        $.ajax({
                    url: 'insertlista.php',
                    type: 'post',
                    data: {"rubrica": rubros,"titulo": document.getElementById("titulo").value, "total": total,"profesor":getURLParameter('id_profesor')},
                    success: function (response) {
                             $("#body").prepend("<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+response+"</div>");
                    
                    }
                });
    }
}
