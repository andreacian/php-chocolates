<?php
namespace MvLabs\Chocosite\Model;
//serialize oggetti in sessione non va bene Trucco: serialize trasforma il testo in formato specifico
class ArchivioUtenti
{
  public function salva(Cliente $cliente)
  {
    $_SESSION['cliente'] = serialize($cliente);
  }

    public function recupera() // per controllare se c'è un oggetto carrello oppure no
    {
        if (!isset($_SESSION['cliente'])) {
            return new Cliente();
        }
// userialize serve per ritradurre da testo a Oggetto   Trucco POSSO Passare anche un solor iferimento ad un articolo e poi fare uan query
        $utente = unserialize($_SESSION['cliente']);

        if ($utente instanceof Cliente) {  // è una istanza veramente? attenti agli Hacherquesto è un controllo
            return $cliente;
        }

        return new Cliente();
    }
}
