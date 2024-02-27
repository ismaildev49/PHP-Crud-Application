<?php 
if (!empty($errors)): ?>

    <div class="alert alert-danger">
        <?php foreach ($errors as $error): ?>

            <div><?php echo $error  ?></div>
        <?php endforeach; ?>
    </div>
    <?php endif ?>


    <form action="" method="POST" enctype="multipart/form-data">

    <?php if($product['image']): ?>
        <img class="update-img" src="/<?php echo $product['image'] ?>">
        <?php endif; ?>
  <div class="mb-3 form-group">
    <label  class="form-label">Product Image</label>
    <br>
    <input name="image" type="file" >
  </div>
  <div class="mb-3 form-group">
    <label  class="form-label">Product Title</label>
    <input type="text" name="title" class="form-control" value="<?php echo $product['title'] ?>">
  </div>
  <div class="mb-3 form-group">
    <label  class="form-label">Product Description</label>
    <textarea name="description" class="form-control" ><?php echo $product['description'] ?> </textarea>
  </div>
  <div class="mb-3 form-group">
    <label  class="form-label">Product Price</label>
    <input name="price" type="number" step=".01" class="form-control" value="<?php echo $product['price'] ?>">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>