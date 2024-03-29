<?php 
$title= $_POST['title'];
$description= $_POST['description'];
$price= $_POST['price'];
$imagePath = "";



if(!$title) {
    $errors[] = 'product title is required';
}

if (!$price) {
    $errors[] = 'product price is required';
}

if (!is_dir(__DIR__.'/public/images')) {
    mkdir((__DIR__.'/public/images'));
}

if (empty($errors)){
$image = $_FILES['image'] ?? null;
$imagePath = $product['image'];

if ($image && $image['tmp_name']) {
//if there is already an image we remove it
    if($product['image']){
        unlink(__DIR__.'/public/'.$product['image']);
    }
    $imagePath = 'images/'.randomString(8).'/'.$image['name'];
    mkdir(dirname(__DIR__.'/public/'.$imagePath));
    move_uploaded_file($image['tmp_name'], __DIR__.'/public/'.$imagePath);
}
}