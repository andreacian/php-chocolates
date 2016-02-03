<?php

function creaConnessionePDO() {
    // Nella realtà evitare di connettersi al db con l'utente "root".
    // E' preferibile creare un utente ad-hoc
    return new PDO('mysql:host=localhost;dbname=mvchocolates', 'root', 'mvlabs');
}

function inizializzaListaProdotti() {
    $db = creaConnessionePDO();
    return $db->query('SELECT * FROM prodotti');
}

function recuperaProdottoDaCodice($codice) {
    $db = creaConnessionePDO();

    // prepara la query da eseguire
    $stmt = $db->prepare('SELECT * FROM prodotti WHERE codice LIKE :codice');

    // filtra i dati ricevuti e si assicura che non contengano caratteri indesiderati
    $codice = filter_input(INPUT_GET, 'codice', FILTER_SANITIZE_STRING);

    // sanitizza i dati per evitare SQL injections
    $stmt->bindParam(':codice', $codice, PDO::PARAM_STR);

    // esegue la query
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// A - esercizio
function recuperaListaCosto($prezzo5) {
    $db = creaConnessionePDO();

    // prepara la query da eseguire
    $stmt = $db->prepare('SELECT * FROM prodotti WHERE prezzo<500 LIKE :prezzo');

    // filtra i dati ricevuti e si assicura che non contengano caratteri indesiderati
    $prezzo5 = filter_input(INPUT_GET, 'codice', FILTER_SANITIZE_STRING);

    // sanitizza i dati per evitare SQL injections
    $stmt->bindParam(':prezzo', $prezzo5, PDO::PARAM_STR);

    // esegue la query
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// A- fine eseercizio

function salvaOrdine($prodotti, $utente) {

    $db = creaConnessionePDO();

    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->beginTransaction();

        // inserimento in tabella clienti
        $stmt = $db->prepare("INSERT INTO clienti (nome, cognome, email, indirizzo, citta, cap, provincia)
                              VALUES (:nome, :cognome, :email, :indirizzo, :citta, :cap, :provincia)");

        $stmt->bindParam(':nome', $utente['nome'], PDO::PARAM_STR);
        $stmt->bindParam(':cognome', $utente['cognome'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $utente['email'], PDO::PARAM_STR);
        $stmt->bindParam(':indirizzo', $utente['indirizzo'], PDO::PARAM_STR);
        $stmt->bindParam(':citta', $utente['citta'], PDO::PARAM_STR);
        $stmt->bindParam(':cap', $utente['cap'], PDO::PARAM_STR);
        $stmt->bindParam(':provincia', $utente['provincia'], PDO::PARAM_STR);

        $stmt->execute();

        $idCliente = $db->lastInsertId();

        // inserimento in tabella ordini
        $stmt = $db->prepare("INSERT INTO ordini (cliente_id, data, totale, note)
                              VALUES (:cliente_id, :data, :totale, :note)");

        $stmt->bindParam(':cliente_id', $idCliente, PDO::PARAM_INT);

        $date = date('Y-m-d H:i:s');
        $stmt->bindParam(':data', $date);

        $totale = 0;
        foreach($prodotti as $rigaProdotto) {
            $totale += $rigaProdotto['prodotto']['prezzo'] * $rigaProdotto['quantita'];
        }

        $stmt->bindParam(':totale', $totale, PDO::PARAM_INT);
        $stmt->bindParam(':note', $utente['note'], PDO::PARAM_STR);

        $stmt->execute();

// A- recupera l'ID di cliente così lo posso usare
        $idOrdine = $db->lastInsertId();

        // inserimento in tabella ordini_dettagli
        // A- e usa l'ID oridine immesso in automatico
        foreach($prodotti as $rigaProdotto) {

            $stmt = $db->prepare("INSERT INTO ordini_dettagli (ordine_id, codice_prodotto, prezzo, quantita, totale)
                                  VALUES (:ordine_id, :codice_prodotto, :prezzo, :quantita, :totale)");

            $stmt->bindParam(':ordine_id', $idOrdine, PDO::PARAM_INT);
            $stmt->bindParam(':codice_prodotto', $rigaProdotto['prodotto']['codice'], PDO::PARAM_STR);
            $stmt->bindParam(':prezzo', $rigaProdotto['prodotto']['prezzo'], PDO::PARAM_INT);
            $stmt->bindParam(':quantita', $rigaProdotto['quantita'], PDO::PARAM_INT);

            $totale = $rigaProdotto['prodotto']['prezzo'] * $rigaProdotto['quantita'];
            $stmt->bindParam(':totale', $totale, PDO::PARAM_INT);

            $stmt->execute();

        }
// A- finchè non fai il commit ache sul database in diretta non noti nulla
        $db->commit();

    } catch (Exception $e) {
        $db->rollBack();
        echo "Si è verificato un errore: " . $e->getMessage();
    }

    // svuotamento variabili di sessione
    unset($_SESSION['utente']);
    unset($_SESSION['carrello']);
}
