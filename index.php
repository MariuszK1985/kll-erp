<?php

$kwoty = [23, 123.44, 11.01];
$transport = 170.53;

//własne przechwytywanie błędów
set_error_handler('exceptions_error_handler');

function exceptions_error_handler($severity, $message, $filename, $lineno) {
  if (error_reporting() == 0) {
    return;
  }
  if (error_reporting() & $severity) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
  }
}

function doliczTransport(&$kwoty, $transport) {
    
    //weryfikacja wprowadzonych danych - czy są wprowadzone wartości, czy są to wartości numeryczne i czy nie są ujemne
    $sprawdz = 1;
    $blad_tekst = '';

    if(empty($kwoty)) {
        $blad_tekst .= "Tablica kwot pozycji jest pusta. Podaj kwoty pozycji na fakturze.<br>";
        $sprawdz = 0;
    } else {
        foreach($kwoty as $kwota) {
            if((!is_numeric($kwota)) || ($kwota <= 0)){
                $sprawdz = 0;
                $blad_tekst .= "Nieprawidłowa wartość w tablicy z kwotami. Wpisz poprawne kwoty<br>";
            }
        }
    }

    if(empty($transport)) {
        $blad_tekst .= "Koszt transportu nie został podany. Podaj kwotę transportu.<br>";
        $sprawdz = 0;
    } else {
        if((!is_numeric($transport)) || ($transport <= 0)){
            $sprawdz = 0;
            $blad_tekst .= "Nieprawidłowa wartość jako kwota transportu. Wpisz poprawną kwotę transportu<br>";
        }
    }

    if($sprawdz == 0) {
        echo $blad_tekst;
        return false;
    }

    $transport = round($transport,2);
    echo "Transport: $transport <br>";
    echo "<br>Wartości przed przeliczeniem:<br>";
    echo "____________________________<br>";
    print_r($kwoty);echo "<br>";
    $ilosc_el_tab = count($kwoty);
    $suma_kwot = array_sum($kwoty);
    $pozycja_max = array_search(123.44, $kwoty, true);
    for($i=0;$i<$ilosc_el_tab;$i++) {
        $kwoty[$i] += round($transport*$kwoty[$i]/$suma_kwot,2);
    }
    $roznica = round($suma_kwot + $transport - array_sum($kwoty),2);
    $kwoty[$pozycja_max] += $roznica;
    echo "<br>Wartości po przeliczeniu:<br>";
    echo "____________________________<br>";
    print_r($kwoty);
}

try {
    doliczTransport($kwoty, $transport);
} catch (Exception $e) {
    echo "Wystąpił nieoczekiwany błąd. Zgłoś to do administratora";
}

?>