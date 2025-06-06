unzip.php<?php
// unzip.php - Website uitpakker
set_time_limit(300); // 5 minuten timeout voor grote bestanden

// CONFIGURATIE
$zipFile = 'website.zip'; // Pas aan naar jouw ZIP bestandsnaam
$extractPath = './'; // Uitpakken in huidige map (of pas aan naar gewenste map)

// Controleer of ZIP bestand bestaat
if (!file_exists($zipFile)) {
    die("❌ Fout: ZIP bestand '{$zipFile}' niet gevonden!");
}

// Controleer of ZipArchive beschikbaar is
if (!class_exists('ZipArchive')) {
    die("❌ Fout: ZipArchive extensie niet beschikbaar op deze server!");
}

echo "<h2>🚀 Website Uitpakker</h2>";
echo "<p><strong>ZIP bestand:</strong> {$zipFile}</p>";
echo "<p><strong>Uitpakken naar:</strong> " . realpath($extractPath) . "</p>";

$zip = new ZipArchive;
$result = $zip->open($zipFile);

if ($result === TRUE) {
    echo "<p>✅ ZIP bestand geopend...</p>";
    
    // Toon aantal bestanden
    $fileCount = $zip->numFiles;
    echo "<p>📁 Aantal bestanden: {$fileCount}</p>";
    
    // Uitpakken
    echo "<p>🔄 Bezig met uitpakken...</p>";
    
    if ($zip->extractTo($extractPath)) {
        $zip->close();
        echo "<p style='color: green; font-weight: bold;'>✅ Website succesvol uitgepakt!</p>";
        
        // Toon uitgepakte bestanden
        echo "<h3>📋 Uitgepakte bestanden:</h3>";
        echo "<ul>";
        for ($i = 0; $i < $fileCount; $i++) {
            $fileName = $zip->getNameIndex($i);
            echo "<li>" . htmlspecialchars($fileName) . "</li>";
        }
        echo "</ul>";
        
        // Opruimen optie
        echo "<hr>";
        echo "<p><strong>⚠️ Belangrijk:</strong> Verwijder dit script en het ZIP bestand voor veiligheid!</p>";
        echo "<p><a href='?delete=zip' style='color: red;'>🗑️ Verwijder ZIP bestand</a></p>";
        echo "<p><a href='?delete=script' style='color: red;'>🗑️ Verwijder dit script</a></p>";
        
    } else {
        $zip->close();
        echo "<p style='color: red;'>❌ Fout bij uitpakken van bestanden!</p>";
    }
    
} else {
    // Error codes
    $errors = [
        ZipArchive::ER_OK => 'Geen fout',
        ZipArchive::ER_MULTIDISK => 'Multi-disk zip archives niet ondersteund',
        ZipArchive::ER_RENAME => 'Hernoemen tijdelijk bestand mislukt',
        ZipArchive::ER_CLOSE => 'Sluiten zip archive mislukt',
        ZipArchive::ER_SEEK => 'Seek error',
        ZipArchive::ER_READ => 'Lees fout',
        ZipArchive::ER_WRITE => 'Schrijf fout',
        ZipArchive::ER_CRC => 'CRC fout',
        ZipArchive::ER_ZIPCLOSED => 'Zip archive gesloten',
        ZipArchive::ER_NOENT => 'Bestand niet gevonden',
        ZipArchive::ER_EXISTS => 'Bestand bestaat al',
        ZipArchive::ER_OPEN => 'Kan bestand niet openen',
        ZipArchive::ER_TMPOPEN => 'Tijdelijk bestand fout',
        ZipArchive::ER_ZLIB => 'Zlib fout',
        ZipArchive::ER_MEMORY => 'Geheugen fout',
        ZipArchive::ER_CHANGED => 'Entry gewijzigd',
        ZipArchive::ER_COMPNOTSUPP => 'Compressie methode niet ondersteund',
        ZipArchive::ER_EOF => 'Premature EOF',
        ZipArchive::ER_INVAL => 'Ongeldig argument',
        ZipArchive::ER_NOZIP => 'Geen zip archive',
        ZipArchive::ER_INTERNAL => 'Interne fout',
        ZipArchive::ER_INCONS => 'Zip archive inconsistent'
    ];
    
    $errorMsg = isset($errors[$result]) ? $errors[$result] : "Onbekende fout (code: {$result})";
    echo "<p style='color: red;'>❌ Fout bij openen ZIP bestand: {$errorMsg}</p>";
}

// Cleanup functionaliteit
if (isset($_GET['delete'])) {
    if ($_GET['delete'] === 'zip' && file_exists($zipFile)) {
        if (unlink($zipFile)) {
            echo "<p style='color: green;'>✅ ZIP bestand verwijderd!</p>";
        } else {
            echo "<p style='color: red;'>❌ Kon ZIP bestand niet verwijderen!</p>";
        }
    }
    
    if ($_GET['delete'] === 'script') {
        echo "<p style='color: orange;'>🔄 Script wordt verwijderd...</p>";
        echo "<script>setTimeout(function(){ window.location.href = window.location.href.split('?')[0]; }, 2000);</script>";
        unlink(__FILE__);
    }
}

echo "<hr>";
echo "<p><small>💡 <strong>Tip:</strong> Verwijder dit script na gebruik voor veiligheid!</small></p>";
?>
