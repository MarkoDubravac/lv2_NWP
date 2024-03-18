<?php
session_start();

$uploadDir = "uploads/";

$uploadedFiles = scandir($uploadDir);

$uploadedFiles = array_diff($uploadedFiles, array('.', '..'));

echo "<h2>Uploaded Files:</h2>";
if (!empty($uploadedFiles)) {
    echo "<ul>";
    foreach ($uploadedFiles as $file) {
        if (!is_dir($uploadDir . $file) && isset($_SESSION['iv'])) {
            $decryption_key = md5('jed4n j4k0 v3l1k1 kljuc');
            $cipher = 'AES-128-CTR';
            $options = 0;
            $decryption_iv = $_SESSION['iv'];
            $data = openssl_decrypt(
                file_get_contents($uploadDir . $file),
                $cipher,
                $decryption_key,
                $options,
                $decryption_iv
            );
            if ($data !== false) {
                $decryptedFilePath = "decrypted_" . basename($file);
                file_put_contents($decryptedFilePath, $data);
                echo "<li><a href='lv2_2_download.php?file={$decryptedFilePath}' target='_blank'>Download {$file}</a></li>";
            } else {
                echo "<li>Error decrypting file: {$file}</li>";
            }
        }
    }
    echo "</ul>";
} else {
    echo "No files uploaded yet.";
}
