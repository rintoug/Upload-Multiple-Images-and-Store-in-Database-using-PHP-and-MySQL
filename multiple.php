<?php

require_once  "dbconnect.inc.php";

$errors = array();
$success = array();
if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {

    $uploadDir = 'uploads/';
    $allowTypes = array('jpg','png','jpeg','gif');

    if(!empty(array_filter($_FILES['files']['name']))){
        foreach($_FILES['files']['name'] as $key=>$val){
            $filename = basename($_FILES['files']['name'][$key]);
            $targetFile = $uploadDir.$filename;
            if(move_uploaded_file($_FILES["files"]["tmp_name"][$key], $targetFile)){
                $success[] = "Uploaded $filename";
                $insertQrySplit[] = "('$filename')";
            }
            else {
                $errors[] = "Something went wrong- File - $filename";
            }
        }

        //Inserting to database
        if(!empty($insertQrySplit)) {
            $query = implode(",",$insertQrySplit);
            $sql = "INSERT INTO upload_images (image) VALUES $query";
            $stmt= $conn->prepare($sql);
            $stmt->execute();
        }
    }
    else {
        $errors[] = "No File Selected";
    }

}
?>
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ajax Upload</title>
    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.css" rel="stylesheet">
    <style>
        .formSmall {
            width: 500px;
            margin: 20px auto 20px auto;
        }
        .message {
            padding:10px;
        }
    </style>

</head>
<body>
<div class="container">

        <div class="row">
            <div class="col-lg-7">
                <h5 class="text-align"> Upload Form</h5>
            </div>
            <?php if(!empty($success)):?>
                <div class="alert alert-success" role="alert">
                    <ul>
                    <?php foreach ($success as $val):?>
                        <li><?php print $val?></li>
                    <?php endforeach;?>
                    </ul>
                </div>
            <?php endif;?>
            <!-- Error listing-->
            <?php if(!empty($errors)):?>
                <div class="alert alert-danger" role="alert">
                    <ul>
                        <?php foreach ($errors as $val):?>
                            <li><?php print $val?></li>
                        <?php endforeach;?>
                    </ul>
                </div>
            <?php endif;?>

            <div class="col-lg-7">
                <form action="" method="post" enctype="multipart/form-data">
                    <label>Select Image(s):</label>
                    <input type="file" name="files[]" multiple >
                    <br>
                    <input type="submit" name="submit" value="Upload">
                </form>
            </div>
        </div><!-- .row -->

</div>

</body>
</html>