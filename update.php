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

    $get_categories = "SELECT * FROM category";
    $categories = mysqli_query($conn, $get_categories);

    $categoriesArr = [];
    if ($categories) {
      while ($row = mysqli_fetch_assoc($categories)) {
        $categoriesArr[$row['id']] = $row['cate_name'];
      }
    }


    $currentURL = $_SERVER['REQUEST_URI'];
    $baseURL = dirname(substr($currentURL, 0, strrpos($currentURL, '/')));

    // Split the URL by slashes (/)
    $parts = explode('/', $currentURL);

    // Get the last part of the URL, which should be the ID
    $id = end($parts);

    // Command to specific row
    $getProductById = "SELECT * FROM products WHERE id = '$id'";
    $currentProduct = mysqli_query($conn, $getProductById);

    // Check if the query was successful and there are rows
    if ($currentProduct && mysqli_num_rows($currentProduct) > 0) {
      // fetch the data
      $productData = mysqli_fetch_assoc($currentProduct);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Get form data
      $title = $_POST["name"];
      $price = $_POST["price"];
      $category = $_POST["category"];
  
      // Handle thumbnail file upload
      $thumbnail = $_FILES["thumbnail"];
      $thumbnailName = $thumbnail["name"];
      $thumbnailTmpName = $thumbnail["tmp_name"];
      $thumbnailPath = "assets/" . $thumbnailName;
      move_uploaded_file($thumbnailTmpName, $thumbnailPath);
  
      //  Process from submission
      $sql = "UPDATE products SET prod_name = ?, price = ?, category_id = ?, thumbnail=? WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sdisi", $title, $price, $category, $thumbnailPath, $id);
  
      // Execute the prepared statement
      if ($stmt->execute()) {
        // Redirect to the hompage
        header("Location: ../index.php");
        exit();
      } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
      }
      // Close the prepared statenebt and database connection
      $stmt->close();
    }
    ?>
    <!--Navbar-->
    <nav class="navbar navbar-expand-lg bg-body-tertiary mt-3">
        <div class="container-fluid">
          <a class="navbar-brand" href="../index.php">ATN Toys</a>
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
                <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="../create.php">New</a>
              </li>
            </ul>
          </div>
        </div>
    </nav>
    <!--Update product Form-->
    <form class="row container mx-auto py-3" method="POST" enctype="multipart/form-data">
        <h1>Update a product</h1>
        <?php foreach ($currentProduct as $product) { ?>
    <div class="mb-3">
        <label for="prod_name" class="form-label">Product name</label>
        <input type="text" class="form-control" id="prod_name" placeholder="Input product name" name="name"
        value="<?php echo $product["prod_name"]?>">
      </div>
      <div class="mb-3">
        <label for="prod_price" class="form-label">Price</label>
        <input type="text" class="form-control" id="prod_price" placeholder="Input Price" name="price"
        value="<?php echo $product["price"]?>">
      </div>
      <div class="mb-3">
        <label for="category" class="form-label">Category</label>
        <select class="form-select" id="category" name="category">
          <option selected hidden value="<?php echo $product['category_id'] ?>">
          <?php echo '#' . $categoriesArr[$product['category_id']] ?></option>
          <?php foreach ($categories as $category) { ?>
            <option class="text-dark" value="<?php echo $category["id"] ?>"><?php echo "#" . $category["cate_name"] ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="prod_image" class="form-label">Product Image</label>
        <input type="file" class="form-control" id="prod_img" value="<?php echo $product["thumbnail"] ?>" name="thumbnail">
        <img src="<?php echo $baseURL . '/' . $product["thumbnail"]; ?>" class="mt-3" alt="preview" width="200" height="200" />
      </div>
        <?php } ?>
      <div class="mb-3 text-center">
        <a href="../index.php" class="btn btn-outline-secondary">Back to products</a>
        <button class="btn btn-success">Update</button>
      </div>
      </form>
  </body>
     <!-- Navigation-->
     
</html>

<?php
if (isset($_GET['update_product_id'])) {
  $product_id = $_GET['update_product_id'];
  $update_product = "update from products set prod_name = '$title' category_id = '$category' price =
   $price' thumbnail = '$thumbnailPath' where id=' $product_id'";
  $execute = mysqli_query($conn, $update_product);

  if ($execute) {
    echo "<script>window.open('index.php', '_self')</script>";
  };
}
?>
