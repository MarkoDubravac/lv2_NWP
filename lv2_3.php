<?php
$xml = simplexml_load_file('LV2.xml');
foreach ($xml->record as $person) {
    echo "<div class='grid-container'>";
    echo "<div><h2>$person->id.";
    echo " {$person->ime}";
    echo " {$person->prezime}";
    if ($person->spol == 'Female') {
        echo " (F)";
    } else {
        echo " (M)";
    }
    echo '</h2><div>';
    echo "<div>Contact: $person->email \n</div><div>";
    $imageURL = (string)$person->slika;
    echo "<br><img src=\"$imageURL\" loading='lazy's></div>";
    echo "<div>$person->zivotopis</div>";
    echo '</div>';
}
