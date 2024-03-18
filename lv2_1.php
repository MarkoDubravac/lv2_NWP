<?php
$db_name = 'lv1';
$dir = "backup/$db_name";
if (!is_dir($dir)) {
    if (!@mkdir($dir)) {
        die("<p>Ne možemo stvoriti direktorij $dir.</p></body></html>");
    }
}
$time = time();
$dbc = @mysqli_connect(
    'localhost',
    'root',
    '',
    $db_name
) or
    die("<p>Ne možemo se spojiti na bazu $db_name.</p></body></html>");
$r = mysqli_query($dbc, 'SHOW TABLES');
if (mysqli_num_rows($r) > 0) {
    echo "<p>Backup za bazu podataka '$db_name'.</p>";
    while (list($table) = mysqli_fetch_array(
        $r,
        MYSQLI_NUM
    )) {
        $q = "SELECT * FROM $table";
        $r2 = mysqli_query($dbc, $q);
        if (mysqli_num_rows($r2) > 0) {
            $fields = mysqli_fetch_fields($r2);
            foreach ($fields as $field) {
                $columns[] = $field->name;
            }
            if ($fp = gzopen("$dir/{$table}_{$time}.sql.gz", 'w9')) {
                while ($row = mysqli_fetch_array(
                    $r2,
                    MYSQLI_NUM
                )) {
                    gzwrite(
                        $fp,
                        "INSERT INTO $table (" . implode(', ', $columns) . ")\nVALUES("
                    );
                    $num_values = count($row);
                    $counter = 0;
                    foreach ($row as $value) {
                        $value = addslashes($value);
                        gzwrite(
                            $fp,
                            "'$value'"
                        );
                        $counter++;
                        if ($counter < $num_values) {
                            gzwrite($fp, ", ");
                        }
                    }
                    gzwrite(
                        $fp,
                        ");\n"
                    );
                }
                gzclose($fp);
                echo "<p>Tablica '$table' je pohranjena.</p>";
            } else {
                echo "<p>Datoteka $dir/{$table}_{$time}.sql.gz se ne može otvoriti.</p>";
                break;
            }
        }
    }
} else {
    echo "<p>Baza $db_name ne sadrži tablice.</p>";
}
