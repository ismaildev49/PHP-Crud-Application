<?php 


// Connection to mysql db
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);


$id = $_GET['id'] ?? null ;

if (!$id) {
    header('Location: index.php');
    exit;
}

$statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
$statement->bindValue(':id', $id);
$statement->execute();
$product= $statement->fetch(PDO::FETCH_ASSOC);

$errors = [];
$title = $product['title'];
$description=$product['description'];
$price = $product['price'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$title= $_POST['title'];
$description= $_POST['description'];
$price= $_POST['price'];



if(!$title) {
    $errors[] = 'product title is required';
}

if (!$price) {
    $errors[] = 'product price is required';
}

if (!is_dir('images')) {
    mkdir(('images'));
}

if (empty($errors)){
$image = $_FILES['image'] ?? null;
$imagePath = $product['image'];

if ($image && $image['tmp_name']) {
//if there is already an image we remove it
    if($product['image']){
        unlink($product['image']);
    }
    $imagePath = 'images/'.randomString(8).'/'.$image['name'];
    mkdir(dirname($imagePath));
    move_uploaded_file($image['tmp_name'], $imagePath);
}

$statement = $pdo->prepare("UPDATE products 
SET title = :title,
image= :image,
description = :description, 
price = :price 
WHERE id = :id");

$statement->bindValue(':title', $title);
$statement->bindValue(':image', $imagePath);
$statement->bindValue(':description', $description);
$statement->bindValue(':price', $price);
$statement->bindValue(':id', $id);

$statement->execute();

header('Location: index.php');
}
}
function randomString($n){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    for ($i=0; $i < $n; $i++){
        $index = rand(0, strlen($characters) -1);
        $str .= $characters[$index];
    }
    return $str;
}
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Product crud</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
  </head>
  <body>
<a href="index.php" class="btn btn-secondary">Go Back To Products</a>

    <h1>Update Product <b><?php echo $product['title'] ?></b></h1>
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>

            <div><?php echo $error  ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif ?>
    <form action="" method="POST" enctype="multipart/form-data">

    <?php if($product['image']): ?>
        <img class="update-img" src="<?php echo $product['image'] ?>">
        <?php endif; ?>
  <div class="mb-3 form-group">
    <label  class="form-label">Product Image</label>
    <br>
    <input name="image" type="file" >
  </div>
  <div class="mb-3 form-group">
    <label  class="form-label">Product Title</label>
    <input type="text" name="title" class="form-control" value="<?php echo $title ?>">
  </div>
  <div class="mb-3 form-group">
    <label  class="form-label">Product Description</label>
    <textarea name="description" class="form-control" ><?php echo $description ?> </textarea>
  </div>
  <div class="mb-3 form-group">
    <label  class="form-label">Product Price</label>
    <input name="price" type="number" step=".01" class="form-control" value="<?php echo $price ?>">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>


  </body>
</html>




