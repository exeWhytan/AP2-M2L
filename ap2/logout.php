<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

  // Initialiser la session
  session_start();
  
  
  // Détruire la session.
  if(session_destroy())
  {
    // Redirection vers la page de connexion
    header("Location: index.html");
  }
?>