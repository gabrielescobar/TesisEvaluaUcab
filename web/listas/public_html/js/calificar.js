//var prueba = '{"titulo": "sadasdasd","rubros": [{"caracteristica0": [{"value": "dasdas"},{"value": 2}],"caracteristica1": [{"value": "sadasdasd"},{"value": 3}],"caracteristica2": [{"value": "asdasdas"},{"value": 3}],"caracteristica3": [{"value": "asdasdasdsadasdasdsa"},{"value": 6}]}]}';
//var cont2 = JSON.parse(prueba);
var fila = 0
//console.log(cont2.rubros[0]);
//
//$.each(cont2.rubros[0], function () {
//    fila += 1;
//})

function sortFunction(a, b) {
    if (a[0] === b[0]) {
        return 0;
    }
    else {
        return (a[0] < b[0]) ? -1 : 1;
    }
}
function getURLParameter(name) {
                return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
            }
//            document.getElementById("estudiante").value = getURLParameter('idalumno');
//            document.getElementById("grupo").value = getURLParameter('idgrupo');
//            document.getElementById("asignacion").value = getURLParameter('idasignacion');
         
            
            $.ajax({
                url: 'vistalista.php',
                type: 'post',
                data: {"id": getURLParameter('idlista')},
                success: function (response) {
                    var hola = JSON.parse(response);
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

            total.push(parcial);
            parcial = [];
            cont = 1;
        }
        else if (cont == 2) {
            parcial.push(value2.value);


        }
    })

    var hola = total.sort(sortFunction);

    total = [];
    add = '<div class="section group" id="rubric' + rubric + '">' +
            '<div class="col span_1_of_' + (hola.length + 1) + '">' +
            '<textarea disabled class="rubric-info" placeholder="Nombre de la Categoria" name="rubro">' + categoria + '</textarea>' +
            '</div>';
    for (var i = 0; i < hola.length; i++) {
        add = add + '<div class="col span_1_of_' + (hola.length + 1) + '" style="text-align: center;">' +
                '<input disabled style="border-radius: 6px;width: 300px;" type="number" class="rubric-value" placeholder="Puntuación" value="' + hola[i][0] + '">' +
                '<br>' +
                '<div class="switch demo3">'+
                       ' <input type="checkbox">'+
                       ' <label><i></i></label>'+
                   ' </div>'+
                '</div>';
    }


    totalp += hola[hola.length - 1][0];
    add = add + '</div>';
    $("#e").append(add);
    rubric += 1;
     fila += 1;
    
});
}
            });

$(".addCF").click(function () {
    if (fila != 15) {
        $("#e").append('<div class="section group" id="rubric' + fila + '">' +
                '<div class="col span_1_of_2">' +
                '<textarea class="rubric-info" placeholder="Nombre de la Categoria" name="rubro"></textarea>' +
                '</div>' +
                '<div class="col span_1_of_2" style="text-align: center;">' +
                '<input style="border-radius: 6px;width: 300px;" type="number" class="rubric-value" placeholder="Puntuación" name="fname">' +
                '</div>' +
                '</div>');
        fila = fila + 1;
    }
    else {
        alert("No puede tener más de 15 rubros en su rubrica");
    }
});

$(".deleteCF").click(function () {
    if (fila != 1) {
        fila = fila - 1;
        $("#rubric" + fila).remove();
    }
    else {
        alert("Debe tener al menos un rubro en su rubrica");
    }
});

function addColumn(tblId, totalcol) {
    console.log(tblId);
    console.log(totalcol);
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
        alert("No puede cerar mas de 7 recuadros");
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
        alert("Debe tener al menos una caracteristica a evaluar");
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
    var parcial = 0;
    var rubros = '[';
    for (i = 0; i < fila; i++) {
        rubros = rubros + '';
        for (j = 0; j < $("#rubric" + i + " :input").length; j++) {

            if (document.getElementById("titulo").value == "") {
                alert("Debe ingregar el título de la rubrica");
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].type === "number" && isNaN(parseFloat($("#rubric" + i + " :input")[j].value))) {
                alert("Las puntuaciones deben ser valores numéricos");
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].value == "") {
                alert("No se aceptan campos en blanco");
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].type === "number" && ($("#rubric" + i + " :input")[j].value < 0)) {
                alert("Las puntuaciones deben ser valores numéricos Positivos");
                error = true;
                break;
            }
            else if ($("#rubric" + i + " :input")[j].type === "number")
            {
                duplicate.push($("#rubric" + i + " :input")[j].value + "");

                if (hasDuplicates(duplicate)) {
                    alert("En un mismo criterio las clasificaciones deben tener puntuaciones distintas");
                    error = true;
                    break;
                }
                else {
//                    rubros = rubros + '{"value": ' + $("#rubric" + i + " :input")[j].value + '}';
//                    if (j + 1 < $("#rubric" + i + " :input").length )
//                        rubros = rubros + ',';
                    parcial = $("#rubric" + i + " :input")[j].value;
                }
            }
            else {

                if ($("#rubric" + i + " :input")[j].type === "checkbox" && $("#rubric" + i + " :input")[j].checked)
                    rubros = rubros + '"' + parcial + '"';
                else if ($("#rubric" + i + " :input")[j].type === "checkbox")
                    rubros = rubros + '"'+0+'"';

//                if (j + 1 < $("#rubric" + i + " :input").length)
//                    rubros = rubros + ',';
            }
        }
      
        if (i + 1 < fila)
            rubros = rubros + ',';
        duplicate = [];
        if (error)
            break;
    }
    rubros = rubros + ']';
    if (!error){
       // alert(getURLParameter('hola'));
       //            document.getElementById("estudiante").value = getURLParameter('idalumno');
//            document.getElementById("grupo").value = getURLParameter('idgrupo');
//            document.getElementById("asignacion").value = getURLParameter('idasignacion');
        $.ajax({
                    url: 'guardar.php',
                    type: 'post',
                    data: {"data": rubros,"estudiante": getURLParameter('idalumno'),"grupo": getURLParameter('idgrupo'),"asignacion": getURLParameter('idasignacion'),"tipo": getURLParameter('tipo')},
                    success: function (response) {
                        $("#body").prepend("<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>"+response+"</div>");
                    
                    }
                });
    }
}
