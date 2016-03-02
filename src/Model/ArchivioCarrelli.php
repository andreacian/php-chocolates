<?php
namespace MvLabs\Chocosite\Model;

class ArchivioCarrelli
{
    public function salva(Carrello $carrello) //serialize oggetti in sessione non va bene Trucco: serialize trasforma in testo in formato specifico
    {
        $_SESSION['carrello'] = serialize($carrello);
    }

    public function recupera() // per controllare se c'è un oggetto carrello oppure no
    {
        if (!isset($_SESSION['carrello'])) {
            return new Carrello();
        }
// userialize serve per ritradurre da testo a Oggetto   Trucco POSSO Passare anche un solor iferimento ad un articoloe epoi fare uan query
        $carrello = unserialize($_SESSION['carrello']);

        if ($carrello instanceof Carrello) {  // è una istanza veramente? attenti agli Hacherquesto è un controllo
            return $carrello;
        }

        return new Carrello();
    }
}
