<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $files = $_FILES['files'];

    // e.g. my-folder/src/app.js -> my-folder
    $firstPath = str_replace('\\', '/', $files['full_path'][0]);
    $folderName = explode('/', $firstPath)[0];

    $zipPath = tempnam(sys_get_temp_dir(), 'zip_');

    $zip = new ZipArchive();

    $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

    foreach ($files['tmp_name'] as $i => $tmpName) {
        $relativePath = str_replace('\\', '/', $files['full_path'][$i]);

        $zip->addFile($tmpName, $relativePath);
    }

    $zip->close();

    header('Content-Type: application/zip');
    header(
        'Content-Disposition: attachment; filename="' .
        $folderName .
        '.zip"'
    );
    header('Content-Length: ' . filesize($zipPath));

    readfile($zipPath);
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Folder Zip</title>
</head>
<body>

<form method="POST" enctype="multipart/form-data">
    <input
        type="file"
        name="files[]"
        webkitdirectory
        multiple
        required
    >

    <button type="submit">Compress</button>
</form>

</body>
</html>
