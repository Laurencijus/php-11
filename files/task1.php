<?php
const FILE_FOLDER = 'files';

if (isset($_FILES['uploaded_file'])) {
    echo "<pre>";
    print_r($_FILES['uploaded_file']);
    echo "</pre>";

    move_uploaded_file(
        $_FILES['uploaded_file']['tmp_name'],
        FILE_FOLDER . '/' . $_FILES['uploaded_file']['name']
    );

    echo "File successfully uploaded";
}

?>
<form method="post" enctype="multipart/form-data">
   <input type="file" name="uploaded_file">
   <input type="submit" value="Upload">
</form>
