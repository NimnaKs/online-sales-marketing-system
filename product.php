<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Product Page | Sales & Marketing System</title>
  <link rel="stylesheet" href="assets/css/sidebar-style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<body>
  <div class="container">

    <?php include('sidebar.php'); ?>

    <div class="main">
      <div class="main-top">
        <h1>Products</h1>
      </div>

      <div class="product-page">
        
        <button class="add-product-btn" id="toggleBtn" onclick="toggleProductForm()">Add Product</button>

        <?php if (isset($_GET['success'])): ?>
              <div class="alert success">
                  <?php if ($_GET['success'] == 'save'): ?>
                      Product saved successfully!
                  <?php elseif ($_GET['success'] == 'delete'): ?>
                      Product deleted successfully!
                  <?php elseif ($_GET['success'] == 'update'): ?>
                      Product updated successfully!
                  <?php endif; ?>
                  <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
              </div>
        <?php elseif (isset($_GET['error'])): ?>
              <div class="alert error">
                  <?php if ($_GET['error'] == 'save'): ?>
                      Error saving product. Please try again.
                  <?php elseif ($_GET['error'] == 'delete'): ?>
                      Error deleting product. Please try again.
                  <?php elseif ($_GET['error'] == 'update'): ?>
                      Error updating product. Please try again.
                  <?php endif; ?>
                  <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
              </div>
        <?php endif; ?>

        
        <div class="product-form" id="productForm" style="display: none;">
              <h2>Product Management</h2>
              <form action="product_operations.php" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="id" id="productId" value="<?php echo $product['id'] ?? ''; ?>">

                  <label for="name">Product Name:</label>
                  <input type="text" id="name" name="name" value="<?php echo $product['name'] ?? ''; ?>" required>

                  <label for="description">Description:</label>
                  <textarea id="description" name="description" required><?php echo $product['description'] ?? ''; ?></textarea>

                  <label for="price">Price:</label>
                  <input type="number" id="price" name="price" value="<?php echo $product['price'] ?? ''; ?>" step="0.01" min="0" required>

                  <label for="stock_qty">Stock Quantity:</label>
                  <input type="number" id="stock_qty" name="stock_qty" value="<?php echo $product['stock_qty'] ?? ''; ?>" min="0" required>

                  <label for="category">Category:</label>
                  <select id="category" name="category" required>
                      <option value="">Select Category</option>
                      <option value="Electronics">Electronics</option>
                      <option value="Clothing">Clothing</option>
                      <option value="Home Appliances">Home Appliances</option>
                      <option value="Books">Books</option>
                      <option value="Sports">Sports</option>
                      <option value="Beauty & Health">Beauty & Health</option>
                      <option value="Toys">Toys</option>
                      <option value="Automotive">Automotive</option>
                      <option value="Furniture">Furniture</option>
                      <option value="Groceries">Groceries</option>
                  </select>

                  <label for="product_image">Product Image:</label>
                  <div class="product-file-upload">
                      <input type="file" id="product_image" name="product_image" accept="image/*" onchange="showImagePreview(event)" style="display: none;">
                      <label for="product_image">
                          <span class="upload-text">Choose Image</span>
                      </label>
                  </div>

                  <img id="imagePreview" src="" alt="Preview Image" style="display: none; width: 500px; margin: 20px auto;">
                  
                  <button type="submit" name="save" id="formSubmitBtn">Save Product</button>
              </form>
        </div>


        <!-- Product List -->
        <div class="product-list" id="productList">
          <h2>Product List</h2>
          <table>
            <thead>
              <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock Qty</th>
                <th>Category</th>
                <th>Image</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody id="productTableBody">
              
            </tbody>
          </table>
        </div>

      </div> 
    </div> 
  </div> 

  <script src="assets/js/script.js"></script>
</body>
</html>
