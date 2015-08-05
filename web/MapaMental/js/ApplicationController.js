/**
 * Creates a new Application Controller.
 * 
 * @constructor
 */
mindmaps.ApplicationController = function () {
    var eventBus = new mindmaps.EventBus();
    var shortcutController = new mindmaps.ShortcutController();
    var commandRegistry = new mindmaps.CommandRegistry(shortcutController);
    var undoController = new mindmaps.UndoController(eventBus, commandRegistry);
    var mindmapModel = new mindmaps.MindMapModel(eventBus, commandRegistry, undoController);
    var clipboardController = new mindmaps.ClipboardController(eventBus,
            commandRegistry, mindmapModel);
    var helpController = new mindmaps.HelpController(eventBus, commandRegistry);
    var printController = new mindmaps.PrintController(eventBus,
            commandRegistry, mindmapModel);
    var autosaveController = new mindmaps.AutoSaveController(eventBus, mindmapModel);
    var filePicker = new mindmaps.FilePicker(eventBus, mindmapModel);

    /**
     * Handles the new document command.
     */
    function doNewDocument() {
        // close old document first
        var doc = mindmapModel.getDocument();
        doCloseDocument();

        var presenter = new mindmaps.NewDocumentPresenter(eventBus,
                mindmapModel, new mindmaps.NewDocumentView());
        presenter.go();
    }

    /**
     * Handles the save document command.
     */
    function doSaveDocument() {
        var presenter = new mindmaps.SaveDocumentPresenter(eventBus,
                mindmapModel, new mindmaps.SaveDocumentView(), autosaveController, filePicker);
        presenter.go();
    }

    /**
     * Handles the close document command.
     */
    function doCloseDocument() {
        var doc = mindmapModel.getDocument();
        if (doc) {
                        function getURLParameter(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
}


      // window.location.replace();
      
      if(getURLParameter('idasignacion') != 'null') {
   // var win = window.open("http://evaluaucab.edu/app_dev.php/estudiante/asignacionesPorPortafolio/"+getURLParameter('portafolio'), "mapa");
window.close();
}
else {
    var win = window.open("http://evaluaucab.edu/app_dev.php/estudiante/mapasMentales/"+getURLParameter('idestudiante'), "mapa");
window.close();
}
    
        }
    }

    /**
     * Handles the open document command.
     */
    function doOpenDocument() {
        var presenter = new mindmaps.OpenDocumentPresenter(eventBus,
                mindmapModel, new mindmaps.OpenDocumentView(), filePicker);
        presenter.go();
    }

    function doExportDocument() {
        var presenter = new mindmaps.ExportMapPresenter(eventBus,
                mindmapModel, new mindmaps.ExportMapView());
        presenter.go();
    }

    /**
     * Initializes the controller, registers for all commands and subscribes to
     * event bus.
     */
    this.init = function () {
        var newDocumentCommand = commandRegistry
                .get(mindmaps.NewDocumentCommand);
        newDocumentCommand.setHandler(doNewDocument);
        newDocumentCommand.setEnabled(true);

        var openDocumentCommand = commandRegistry
                .get(mindmaps.OpenDocumentCommand);
        openDocumentCommand.setHandler(doOpenDocument);
        openDocumentCommand.setEnabled(true);

        var saveDocumentCommand = commandRegistry
                .get(mindmaps.SaveDocumentCommand);
        saveDocumentCommand.setHandler(doSaveDocument);

        var closeDocumentCommand = commandRegistry
                .get(mindmaps.CloseDocumentCommand);
        closeDocumentCommand.setHandler(doCloseDocument);

        var exportCommand = commandRegistry.get(mindmaps.ExportCommand);
        exportCommand.setHandler(doExportDocument);

        eventBus.subscribe(mindmaps.Event.DOCUMENT_CLOSED, function () {
            saveDocumentCommand.setEnabled(false);
            closeDocumentCommand.setEnabled(false);
            exportCommand.setEnabled(false);
        });

        eventBus.subscribe(mindmaps.Event.DOCUMENT_OPENED, function () {
            saveDocumentCommand.setEnabled(true);
            closeDocumentCommand.setEnabled(true);
            exportCommand.setEnabled(true);
        });
    };

    /**
     * Launches the main view controller.
     */
    this.go = function () {
        function getURLParameter(name) {
            return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)').exec(location.search) || [, ""])[1].replace(/\+/g, '%20')) || null
        }
        if (getURLParameter('idmapa') == '0' && getURLParameter('idasignacion') == 'null') {

            localStorage.setItem("root", '{"root": {"id": "1e348414-4b48-419e-b00e-5e07f443fcb5","parentId": null,"text": {"caption": "Idea Principal","font": {"style": "normal","weight": "bold","decoration": "none","size": 20,"color": "#000000"}},"offset": {"x": 0,"y": 0},"foldChildren": false,"branchColor": "#000000","children": []}}');
         
            var viewController = new mindmaps.MainViewController(eventBus,
                            mindmapModel, commandRegistry);
                    viewController.go();
                    doNewDocument();
        }
        if (getURLParameter('idmapa') == '0' && getURLParameter('idasignacion') == 'null') {

            localStorage.setItem("root", '{"root": {"id": "1e348414-4b48-419e-b00e-5e07f443fcb5","parentId": null,"text": {"caption": "Idea Principal","font": {"style": "normal","weight": "bold","decoration": "none","size": 20,"color": "#000000"}},"offset": {"x": 0,"y": 0},"foldChildren": false,"branchColor": "#000000","children": []}}');
         var viewController = new mindmaps.MainViewController(eventBus,
                            mindmapModel, commandRegistry);
                    viewController.go();
                    doNewDocument();
        }
    
        else if (getURLParameter('idasignacion') != 'null') {
           
            $.ajax({
                url: 'vistamapa_1.php',
                type: 'post',
                data: {"seccion": getURLParameter('estsecc'),"idestudiante": getURLParameter('idestudiante'),"grupo": getURLParameter('idgrupo'),"idasignacion": getURLParameter('idasignacion')},
                success: function (response) {
                    if(response != "redirect"){
                    localStorage.setItem("root", response);
              //    alert(localStorage.getItem("root"));
                    var viewController = new mindmaps.MainViewController(eventBus,
                            mindmapModel, commandRegistry);
                    viewController.go();
                    doNewDocument();
                    }
                    else {
                       
                        window.location.href="http://evaluaucab.edu/MapaMental_vista/index.html?idestudiante="+getURLParameter('idestudiante')+"&estsecc="+getURLParameter('estsecc')+"&estgrupo="+getURLParameter('idgrupo')+"&idasignacion="+getURLParameter('idasignacion')+"&idmapa=0&idrubrica=0&idlista=0";
                    }
                }
            });
        }
//    var viewController = new mindmaps.MainViewController(eventBus,
//        mindmapModel, commandRegistry);
//    viewController.go();
//
//    doNewDocument();
    };

    this.init();
};
