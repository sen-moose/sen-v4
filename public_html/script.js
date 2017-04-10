function GetURLforPage(redir) {
  /*parent.page.*/window.location.href = redir;// Depreciated
}
function GetURLforParent(redir) {
  window.location.href = redir; // Depreciated
}

function toggle(id) {
  var object = document.getElementById(id);
  if (object.style.display != "") {
    object.style.display = "";
  } else {
    object.style.display = "none";
  }
}
function getid(id) {
  return document.getElementById(id);
}
function hideshow(idhide,idshow) {
  toggle(idhide);
  toggle(idshow);
}
function DebugLinks() { //Written by Pie_Sniper, because he wanted it :P
  var Links = document.getElementsByTagName( "a" );
  for( var I = 0; I < Links.length; I++ ) {
    if( Links[I].href.charAt(31) == '?' && Links[I].href.search("&debug") == -1) {
      Links[I].href = Links[I].href+"&debug";
    }
  }
}