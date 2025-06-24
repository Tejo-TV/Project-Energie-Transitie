  // Show overlay wanneer je uit wil loggen
  function logoutOverlay() {
    document.getElementById("logoutOverlay").style.display = "flex";
  }
  // unshow overlay wanneer je uit wil loggen
  function cancelLogoutOverlay() {
    document.getElementById("logoutOverlay").style.display = "none";
  }

  // Show overlay wanneer je settings wil bewerken
    function settingsOverlay() {
    document.getElementById("settingsOverlay").style.display = "flex";
  }
  // Unshow overlay wanneer je settings wil bewerken
  function cancelsettingsOverlay() {
    document.getElementById("settingsOverlay").style.display = "none";
  }

  function addressHighlightRemove() {
    document.getElementById("removeHighlight").style.backgroundColor = "transparent";
    document.getElementById("removeHighlight").style.padding = "0px";
  }

