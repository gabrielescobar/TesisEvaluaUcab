/**
 * <pre>
 * Listens to HELP_COMMAND and displays notifications.
 * Provides interactive tutorial for first time users.
 * </pre>
 * 
 * @constructor
 * @param {mindmaps.EventBus} eventBus
 * @param {mindmaps.commandRegistry} commandRegistry
 */
mindmaps.HelpController = function(eventBus, commandRegistry) {

  /**
   * Prepare tutorial guiders.
   */
  function setupInteractiveMode() {
    if (isTutorialDone()) {
      console.debug("skipping tutorial");
      return;
    }

    var notifications = [];
    var interactiveMode = true;

    // start tutorial after a short delay
    eventBus.once(mindmaps.Event.DOCUMENT_OPENED, function() {
      setTimeout(start, 1000);
    });

    function closeAllNotifications() {
      notifications.forEach(function(n) {
        n.close();
      });
    }

    var helpMain, helpRoot;
    function start() {
      helpMain = new mindmaps.Notification(
          "#toolbar",
          {
            position : "bottomMiddle",
            maxWidth : 550,
            title : "Bienvenido a la aplicacion de Mapa Mental",
            content : "Hola!, Parece que eres nuevo aqui! Estas burbujas "
                + "te guiaran a traves de esta aplicacion. Si quieres saltar este tutoral haz <a class='skip-tutorial link'>click aqui<a/>."
          });
      notifications.push(helpMain);
      helpMain.$().find("a.skip-tutorial").click(function() {
        interactiveMode = false;
        closeAllNotifications();
        tutorialDone();
      });
      setTimeout(theRoot, 2000);
    }

    function theRoot() {
      if (isTutorialDone())
        return;

      helpRoot = new mindmaps.Notification(
          ".node-caption.root",
          {
            position : "bottomMiddle",
            closeButton : true,
            maxWidth : 350,
            title : "Aqui es donde comenzaremos - Tu idea principal ",
            content : "Haz doble click en la idea principal para cambiar el texto. Este sera el topico principal de tu Mapa Mental."
          });
      notifications.push(helpRoot);

      eventBus.once(mindmaps.Event.NODE_TEXT_CAPTION_CHANGED, function() {
        helpRoot.close();
        setTimeout(theNub, 900);
      });
    }

    function theNub() {
      if (isTutorialDone())
        return;

      var helpNub = new mindmaps.Notification(
          ".node-caption.root",
          {
            position : "bottomMiddle",
            closeButton : true,
            maxWidth : 350,
            padding : 20,
            title : "Creando nuevas ideas",
            content : "Ahora es tiempo de crear tu mapa mental.<br/> Mueve el mouse sobre la idea pincipal, haz click y arrastra"
                + " el <span style='color:red'>circulo rojo</span> lejos de la idea. Asi es como creas una nueva rama."
          });
      notifications.push(helpNub);
      eventBus.once(mindmaps.Event.NODE_CREATED, function() {
        helpMain.close();
        helpNub.close();
        setTimeout(newNode, 900);
      });
    }

    function newNode() {
      if (isTutorialDone())
        return;

      var helpNewNode = new mindmaps.Notification(
          ".node-container.root > .node-container:first",
          {
            position : "bottomMiddle",
            closeButton : true,
            maxWidth : 350,
            title : "Tu primera rama",
            content : "Estupendo! Esto es facil, verdad? El circulo rojo es tu mas importante herramienta. Ahora, puedes mover tu idea"
                + " alrededor arrastrandola o haciendo doble click para cambiar el texto de nuevo."
          });
      notifications.push(helpNewNode);
      setTimeout(inspector, 2000);

      eventBus.once(mindmaps.Event.NODE_MOVED, function() {
        helpNewNode.close();
        setTimeout(navigate, 0);
        setTimeout(toolbar, 15000);
        setTimeout(menu, 10000);
        setTimeout(tutorialDone, 20000);
      });
    }

    function navigate() {
      if (isTutorialDone())
        return;

      var helpNavigate = new mindmaps.Notification(
          ".float-panel:has(#navigator)",
          {
            position : "bottomRight",
            closeButton : true,
            maxWidth : 350,
            expires : 10000,
            title : "Navegacion",
            content : "Puedes hacer click y arrastrar el foto del mapa para moverte en los alrededores. Usa la rueda del mouse o el slider para hacer zoom."
          });
      notifications.push(helpNavigate);
    }

    function inspector() {
      if (isTutorialDone())
        return;

      var helpInspector = new mindmaps.Notification(
          "#inspector",
          {
            position : "leftBottom",
            closeButton : true,
            maxWidth : 350,
            padding : 20,
            title : "¿No te gustan los colores?",
            content : "Usa este editor para cambiar la apariencia de tus ideas. "
                + "Haz click en el icono arriba en la derecha para minimizar este panel."
          });
      notifications.push(helpInspector);
    }

    function toolbar() {
      if (isTutorialDone())
        return;

      var helpToolbar = new mindmaps.Notification(
          "#toolbar .buttons-left",
          {
            position : "bottomLeft",
            closeButton : true,
            maxWidth : 350,
            padding : 20,
            title : "Barra de Herramientas",
            content : "Estos botones hacen lo que dice cada botom.Puedes utilizar estos botones o usar los atajos."
                + "Pasa por encima de cada boton para saber las teclas de los atajos."
          });
      notifications.push(helpToolbar);
    }

    function menu() {
      if (isTutorialDone())
        return;

      var helpMenu = new mindmaps.Notification(
          "#toolbar .buttons-right",
          {
            position : "leftTop",
            closeButton : true,
            maxWidth : 350,
            title : "Guarda tu Trabajo!!!",
            content : "El boton a la derecha abre un menu donde puedes guardar tu Mapa Mental o comienza a trabajar "
                + "en otro si lo prefieres."
          });
      notifications.push(helpMenu);
    }

    function isTutorialDone() {
      return mindmaps.LocalStorage.get("mindmaps.tutorial.done") == 1;
    }

    function tutorialDone() {
      mindmaps.LocalStorage.put("mindmaps.tutorial.done", 1);
    }

  }

  /**
   * Prepares notfications to show for help command.
   */
  function setupHelpButton() {
    var command = commandRegistry.get(mindmaps.HelpCommand);
    command.setHandler(showHelp);

    var notifications = [];
    function showHelp() {
      // true if atleast one notifications is still on screen
      var displaying = notifications.some(function(noti) {
        return noti.isVisible();
      });

      // hide notifications if visible
      if (displaying) {
        notifications.forEach(function(noti) {
          noti.close();
        });
        notifications.length = 0;
        return;
      }

      // show notifications
      var helpRoot = new mindmaps.Notification(
          ".node-caption.root",
          {
            position : "bottomLeft",
            closeButton : true,
            maxWidth : 350,
            title : "Esta es tu idea principal",
            content : "Haz doble click en la idea para editar el texto. Mueve el mouse sobre"
                + " una idea y arrastra el circulo rojo pare crear una nueva idea."
          });

      var helpNavigator = new mindmaps.Notification(
          "#navigator",
          {
            position : "leftTop",
            closeButton : true,
            maxWidth : 350,
            padding : 20,
            title : "Este es el Navegador",
            content : "Usa este panel para tener una vista total de tu Mapa Mental. "
                + "Puedes navegar alrededor arrastrando el rectangulo rojo o cambia el zoom haciendo click en los botones."
          });

      var helpInspector = new mindmaps.Notification(
          "#inspector",
          {
            position : "leftTop",
            closeButton : true,
            maxWidth : 350,
            padding : 20,
            title : "Este es el Editor de Texto",
            content : "Usa este editor para cambiar la apariencia de tus ideas. "
                + "Haz click en el icono arriba en la derecha para minimizar este panel."
          });

      var helpToolbar = new mindmaps.Notification(
          "#toolbar .buttons-left",
          {
            position : "bottomLeft",
            closeButton : true,
            maxWidth : 350,
            title : "Esta es la Barra de Herramientas",
            content : "Estos botones hacen lo que dice cada botom.Puedes utilizar estos botones o usar los atajos."
                + "Pasa por encima de cada boton para saber las teclas de los atajos."
          });

      notifications.push(helpRoot, helpNavigator, helpInspector,
          helpToolbar);
    }
  }

  setupInteractiveMode();
  setupHelpButton();
};
