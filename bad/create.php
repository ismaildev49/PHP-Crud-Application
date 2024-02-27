
<?php 
// Connection to mysql db
$pdo = new PDO('mysql:host=localhost;port=3306;dbname=products_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
$errors = [];
$title = '';
$description='';
$price = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {



$title= $_POST['title'];
$description= $_POST['description'];
$price= $_POST['price'];
$date= date('Y-m-d H:i:s');


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
$imagePath = '';
if ($image && $image['tmp_name']) {
    $imagePath = 'images/'.randomString(8).'/'.$image['name'];
    mkdir(dirname($imagePath));
    move_uploaded_file($image['tmp_name'], $imagePath);
}

$statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date) 
                VALUES (:title, :image, :description, :price, :date)
");

$statement->bindValue(':title', $title);
$statement->bindValue(':image', $imagePath);
$statement->bindValue(':description', $description);
$statement->bindValue(':price', $price);
$statement->bindValue(':date', $date);

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
    <h1>Create new product</h1>
    <?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>

            <div><?php echo $error  ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif ?>
    <form action="" method="POST" enctype="multipart/form-data">
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




