<!DOCTYPE html>

<html>
  <head>
    <!--Boostrap-->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
      crossorigin="anonymous"
    ></script>

    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title></title>
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="" />
  </head>
  <body>

  <?php
  include('connect.php');
  $get_products = "SELECT * FROM products";
  $get_categories = "SELECT * FROM category";
  $products = mysqli_query($conn, $get_products);
  $categories = [];

  $category = mysqli_query($conn, $get_categories);
  if ($category) {
    while ($row = mysqli_fetch_assoc($category)) {
      $categories[$row['id']] = $row['cate_name'];
    }
  }
  ?>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary mt-3">
      <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">ATN Toys</a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="create.php">New</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <!--Products-->
          <div class="container-fluid text-center">
            <div class="row row-gap-3 justify-content-center">
              <?php foreach ($products as $product) { ?>
              <div class="col-12 col-md-6 col-lg-2">
                <div class="card">
                  <img src="<?php echo $product['thumbnail']; ?>" class="card-img-top" 
                  alt="<?php echo $product['prod_name']; ?>">
                  <div class="card-body">
                    <h5 class="card-title"><?php echo $product['prod_name']; ?></h5>
                    <div class="d-flex justify-content-between align-item-center">
                      <p class="text-info fs-4"><?php echo $product['price']; ?></p>
                      <p class="text-secondary fs-5">
                      <span class="badge bg-secondary">#<?php echo $categories[$product['category_id']]; ?></span>
                      </p>
                    </div>
                    <a href="./update.php/<?php echo $product["id"]; ?>" class="btn btn-warning w-100">Update</a>
                    <!-- Button trigger modal -->
                <button type="button" class="btn btn-danger w-100 mt-3" data-bs-toggle="modal" data-bs-target="#<?php echo $product["id"]?>">
        Delete
      </button>

      <!-- Modal -->
      <div class="modal fade" id="<?php echo $product["id"]?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Are you sure to delete this product?</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Once you delete, it would be removed from database.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, keep it</button>
              <button type="button" class="btn btn-danger">
                <a href="index.php?delete_product_id=<?php echo $product["id"]; ?>"
                class="text-while text-decoration-none">
                Yes, delete it
              </a>
              </button>
            </div>
          </div>
        </div>
      </div>
              </div>
                </div>
              </div>
        <?php } ?>
      </div>
    </div>
  </body>
</html>

<?php

if (isset($_GET['delete_product_id'])) {
  $product_id = $_GET['delete_product_id'];
  $delete_product = "delete from products where id='$product_id'";
  $execute = mysqli_query($conn, $delete_product);

  if ($execute) {
    echo "<script>window.open('index.php', '_self')</script>";
  };
}

?>
