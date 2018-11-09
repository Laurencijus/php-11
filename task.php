
<?php

require_once 'Exception/FileException.php';

const FILE_FOLDER = 'files';
const MAX_FILE_SIZE = 524288; //0.5mb
const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png', 'php', 'txt'];

if (isset($_FILES['uploaded_file'])) {
    if ($_FILES['uploaded_file']['tmp_name']) {
        try {
            upload($_FILES['uploaded_file']);
        } catch (FileException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo "Įvyko klaida.";
        }

    } else {
        echo "Unable to upload file<br>";
    }
}

function isFileSizeAllowed(array $file): bool
{
    return filesize($file['tmp_name']) < MAX_FILE_SIZE;
}

function isExtensionAllowed(array $file): bool
{
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

    return in_array($extension, ALLOWED_EXTENSIONS);
}

function filterFileName(string $oldFileName): string
{
    $result = filter_var($oldFileName, FILTER_SANITIZE_STRING);
    $result = str_replace(' ', '_', $result);
    $result = strtolower($result);
    $result = preg_replace('/[^a-z0-9_\.]/i', '', $result);

    return $result;
}

function upload(array $file): void
{
    if (!isExtensionAllowed($file)) {
        throw new FileException('File extension is not allowed');
        return;
    }

    if (!isFileSizeAllowed($file)) {
        throw new FileException('File size is not allowed');
        return;
    }

    move_uploaded_file(
        $file['tmp_name'],
        FILE_FOLDER . '/' . filterFileName($file['name'])
    );

    echo "Successfully uploaded<br>";
}

?>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="uploaded_file">
    <input type="submit" value="Upload">
</form>
