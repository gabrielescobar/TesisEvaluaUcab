
var fila = 0
function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}
$.ajax({
    url: 'vistarubrica.php',
    type: 'post',
    data: {"id": getURLParameter('id')},
    success: function (response) {
        var prueba = response;
        var cont2 = JSON.parse(prueba);
      
        $.each(cont2.rubros[0], function () {
        fila += 1;
})
var hola = JSON.parse(prueba);
var totalp = 0;

document.getElementById("titulo").value = hola.titulo;
var total = [];
var parcial = [];
var rubric = 0;
$.each(hola.rubros[0], function (key, value) {
    var add = "";
    var categoria = "";
    var cont = 0;
    $.each(value, function (key2, value2) {
        if (cont == 0) {
            categoria = value2.value;
            cont += 1;

        }
        else if (cont == 1) {
            parcial.push(value2.value);
            cont += 1
        }
        else if (cont == 2) {
            parcial.push(value2.value);
            total.push(parcial);
            parcial = [];
            cont = 1;
        }
    })
    var hola = total.sort(sortFunction);
    total = [];
    add = '<div class="section group" id="rubric' + rubric + '">' +
            '<div class="col span_1_of_' + (hola.length + 2) + '">' +
            '<textarea class="rubric-info" placeholder="Nombre de la Categoria" name="rubro">' + categoria + '</textarea>' +
            '</div>';
    for (var i = 0; i < hola.length; i++) {
        add = add + '<div class="col span_1_of_' + (hola.length + 2) + '">' +
                '<input type="number" class="rubric-value" placeholder="Puntuación" value="' + hola[i][0] + '">' +
                '<br>' +
                '<textarea class="rubric-description" placeholder="Descipción" name="fdesc">' + hola[i][1] + '</textarea>' +
                '</div>';


    }
    add = add + '  <div class="col span_1_of_' + (hola.length + 2) + '">' +
            ' <button class="button-add" type="button" onclick="addColumn(\'rubric' + rubric + '\', ' + (hola.length + 2) + ')"></button>' +
            ' <button class="button-delete" type="button" onclick="deleteColumn(\'rubric' + rubric + '\', ' + (hola.length + 2) + ')"></button>' +
            '</div>';
    totalp += hola[hola.length - 1][0];
    add = add + '</div>';
    $("#e").append(add);
    rubric += 1;
});
    }
});



function sortFunction(a, b) {
    if (a[0] === b[0]) {
        return 0;
    }
    else {
        return (a[0] < b[0]) ? -1 : 1;
    }
}

$(document).ready(function(){
$(".addCF").click(function () {
    if (fila != 15) {
        $("#e").append('<div class="section group" id="rubric' + fila + '">' +
                '<div class="col span_1_of_3">' +
                '<textarea class="rubric-info" placeholder="Nombre de la Categoria" name="rubro"></textarea>' +
                '</div>' +
                '<div class="col span_1_of_3">' +
                '<input type="number" class="rubric-value" placeholder="Puntuación" name="fname">' +
                '<br>' +
                '<textarea class="rubric-description" placeholder="Descipción" name="fdesc"></textarea>' +
                '</div>' +
                '<div class="col span_1_of_3">' +
                '<button class="button-add" type="button" onclick="addColumn(\'rubric' + fila + '\',3)"></button>' +
                '<button class="button-delete" type="button" onclick="deleteColumn(\'rubric' + fila + '\',3)"></button>' +
                '</div>' +
                '</div>');
        fila = fila + 1;
    }
    else {
        $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>No puede tener más de 15 rubros en su rubrica</div>");
    }
});

$(".deleteCF").click(function () {
    if (fila != 1) {
        fila = fila - 1;
        $("#rubric" + fila).remove();
    }
    else {
         $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Debe tener al menos un rubro en su rubrica</div>");
    }
});
});
function addColumn(tblId, totalcol) {

    var newContent = "";
    var totalcol2 = totalcol + 1;
    if (totalcol < 8) {
        newContent = newContent + '<div class="col span_1_of_' + totalcol2 + '">' +
                '<textarea class="rubric-info" placeholder="Nombre de la Categoria" >' + $("#" + tblId + " :input")[0].value + '</textarea>' +
                '</div>';
        cont = 1;
        for (i = 0; i < totalcol - 1; i++) {
            newContent = newContent + '<div class="col span_1_of_' + totalcol2 + '">' +
                    '<input type="number" placeholder="Puntuación" class="rubric-value" value="' + $("#" + tblId + " :input")[cont].value + '" name="fname">' +
                    '<br>' +
                    '<textarea placeholder="Descipción" class="rubric-description">' + $("#" + tblId + " :input")[cont + 1].value + '</textarea>' +
                    '</div>';
            cont = cont + 2;
        }
        newContent = newContent + '<div class="col span_1_of_' + totalcol2 + '">' +
                '<button class="button-add" type="button" onclick="addColumn(\'' + tblId + '\',' + totalcol2 + ')"></button>' +
                '<button class="button-delete" type="button" onclick="deleteColumn(\'' + tblId + '\',' + totalcol2 + ')"></button>' +
                '</div>';
        document.getElementById(tblId).innerHTML = newContent;
        if (tblId === "rubric0")
            document.getElementById("titulos").innerHTML = '<div class="col span_1_of_' + totalcol2 + '"><label>Criterio</label></div><div class="col span_' + (totalcol2 - 1) + '_of_' + totalcol2 + '"><label>Clasifiación</label></div>';

    }
    else {
           $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>No puede crear más de 7 caracteristicas</div>");
    }
}

function deleteColumn(tblId, totalcol) {
    var newContent = "";
    var totalcol2 = totalcol - 1;
    if (totalcol > 3) {
        newContent = newContent + '<div class="col span_1_of_' + totalcol2 + '">' +
                '<textarea class="rubric-info" placeholder="Nombre de la Categoria">' + $("#" + tblId + " :input")[0].value + '</textarea>' +
                '</div>';
        cont = 1;
        for (i = 0; i < totalcol - 3; i++) {
            newContent = newContent + '<div class="col span_1_of_' + totalcol2 + '">' +
                    '<input type="number" placeholder="Puntuación" class="rubric-value" value="' + $("#" + tblId + " :input")[cont].value + '" name="fname">' +
                    '<br>' +
                    '<textarea placeholder="Descipción" class="rubric-description">' + $("#" + tblId + " :input")[cont + 1].value + '</textarea>' +
                    '</div>';
            cont = cont + 2;
        }

        newContent = newContent + '<div class="col span_1_of_' + totalcol2 + '">' +
                '<button style="width: 100%;" class="button-add" type="button" onclick="addColumn(\'' + tblId + '\',' + totalcol2 + ')"></button>' +
                '<button class="button-delete" type="button" onclick="deleteColumn(\'' + tblId + '\',' + totalcol2 + ')"></button>' +
                '</div>';
        document.getElementById(tblId).innerHTML = newContent;
        if (tblId === "rubric0")
            document.getElementById("titulos").innerHTML = '<div class="col span_1_of_' + totalcol2 + '"><label>Criterio</label></div><div class="col span_' + (totalcol2 - 1) + '_of_' + totalcol2 + '"><label>Clasifiación</label></div>';

    }
    else {
          $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Debe tener al menos una caracteristica a evaluar</div>");
    }
}
function hasDuplicates(array) {
    var valuesSoFar = {};
    for (var i = 0; i < array.length; ++i) {
        var value = array[i];
        if (Object.prototype.hasOwnProperty.call(valuesSoFar, value)) {
            return true;
        }
        valuesSoFar[value] = true;
    }
    return false;
}

function rubricCreate() {
    var error = false;
    var duplicate = [];
    var parcial=0;
    var total=0;
    var rubros = '{"titulo": "' + document.getElementById("titulo").value + '", "rubros": [{';
    for (i = 0; i < fila; i++) {
        rubros = rubros + '"caracteristica' + i + '": [';
        for (j = 0; j < $("#rubric" + i + " :input").length - 2; j++) {

            if (document.getElementById("titulo").value == "") {
                $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Debe ingregar el título de la rubrica</div>");
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].type === "number" && isNaN(parseFloat($("#rubric" + i + " :input")[j].value))) {
                $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Las puntuaciones deben ser valores numéricos</div>");
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].value == "") {
                 $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>No se aceptan campos en blanco</div>");
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].type === "number" && ($("#rubric" + i + " :input")[j].value < 0)) {
                $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Las puntuaciones deben ser valores numéricos Positivos</div>");
    
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].type === "number")
            {
                duplicate.push($("#rubric" + i + " :input")[j].value + "");

                if (hasDuplicates(duplicate)) {
                     $("#body").prepend("<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>En un mismo criterio las clasificaciones deben tener puntuaciones distintas</div>");
                    error = true;
                    break;
                }
                else {
                    rubros = rubros + '{"value": ' + $("#rubric" + i + " :input")[j].value + '}';
                    if (parcial < parseFloat($("#rubric" + i + " :input")[j].value) )
                parcial=parseFloat($("#rubric" + i + " :input")[j].value)
                    if (j + 1 < $("#rubric" + i + " :input").length - 2)
                        rubros = rubros + ',';
                }
            }
            else {
                rubros = rubros + '{"value": "' + $("#rubric" + i + " :input")[j].value + '"}';
                if (j + 1 < $("#rubric" + i + " :input").length - 2)
                    rubros = rubros + ',';
            }
        }
        total+=parcial;
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
                    url: 'editarrubrica.php',
                    type: 'post',
                    data: {"rubrica": rubros,"titulo": document.getElementById("titulo").value,"id": getURLParameter('id'),"total": total},
                    success: function (response) {
                      $("#body").prepend("<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+response+"</div>");
                   
                    }
                });
    }

}
