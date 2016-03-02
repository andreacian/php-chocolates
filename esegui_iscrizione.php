<?php
namespace MvLabs\Chocosite\Entity\Clienti;

// includere i file con le classi gestiti da Composer
include 'vendor/autoload.php';

// inizializziamo le sessioni
session_start();

// salviamo in sessione tutti i dati ricevuti via POST

$Utente = new Utente () ;
$Utente = $_POST;

$utente = $ArchivioUtenti->salva($Utente);


// $_SESSION['utente'] = $Utente;

// rimando a pagina carrello
header ('location: riepilogo.php');
