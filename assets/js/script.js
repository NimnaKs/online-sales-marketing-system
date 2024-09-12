function validateForm() {
    const name = document.querySelector('input[name="name"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value.trim();
    const role = document.querySelector('select[name="role"]').value;

    if (name === "" || email === "" || password === "" || role === "") {
        alert("Please fill out all fields.");
        return false;
    }

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
    if (!email.match(emailPattern)) {
        alert("Please enter a valid email address.");
        return false;
    }

    return true;
}

function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function() {
        var imagePreview = document.getElementById('imagePreview');
        imagePreview.src = reader.result; 
    };
    reader.readAsDataURL(event.target.files[0]); 
}

const form = document.getElementById("productForm");
const productList = document.getElementById("productList");
const toggleBtn = document.getElementById("toggleBtn");

function toggleProductForm() {
    
    if (form.style.display === "none") {
      form.style.display = "block";
      productList.style.display = "none";
      toggleBtn.textContent = "View Product List";
    } else {
      form.style.display = "none";
      productList.style.display = "block";
      toggleBtn.textContent = "Add Product";
    }
}

function fetchProducts() {
    fetch('../seller_dashboard/fetch_product.php')
        .then(response => response.json())
        .then(products => {
            const tableBody = document.getElementById('productTableBody');
            tableBody.innerHTML = ''; 
  
            products.forEach(product => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.id}</td>
                    <td>${product.name}</td>
                    <td>${product.description}</td>
                    <td>${product.price}</td>
                    <td>${product.stock_qty}</td>
                    <td>${product.category}</td>
                    <td><img src="data:image/jpeg;base64,${product.product_image}" alt="Product Image" class="product-img"></td>
                    <td class="action-buttons">
                        <button class="btn-edit" 
                            onclick="editProduct(${product.id})">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn-delete" onclick="confirmDelete(${product.id})">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                `;
  
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching products:', error));
}


function showImagePreview(event) {
  console.log('Image Preview Called');
  const input = event.target;
  const reader = new FileReader();
  
  reader.onload = function() {
    const imagePreview = document.getElementById('imagePreview');
    imagePreview.src = reader.result;
    imagePreview.style.display = 'block'; 
  }
  
  if (input.files && input.files[0]) {
    reader.readAsDataURL(input.files[0]); 
  }
}

function editProduct(product_id) {
  console.log("edit product");
  toggleProductForm();
  fetch(`../seller_Dashboard/fetch_product.php?id=${product_id}`)
      .then(response => response.json())
      .then(product => {
          if (product.error) {
              alert(product.error);
              return;
          }

          const { id, name, description, price, stock_qty, category, product_image } = product;

          
          document.getElementById('name').value = name;
          document.getElementById('description').value = description;
          document.getElementById('price').value = price;
          document.getElementById('stock_qty').value = stock_qty;
          document.getElementById('category').value = category;

          const imagePreview = document.getElementById('imagePreview');
          if (product_image) {
              imagePreview.src = `data:image/jpeg;base64,${product_image}`;
              imagePreview.style.display = 'block';
          } else {
              imagePreview.style.display = 'none';
          }

          document.querySelector('input[name="id"]').value = id;
          document.getElementById('formSubmitBtn').textContent = 'Update Product';

          document.getElementById("productList").style.display = 'none';
      })
      .catch(error => console.error('Error fetching product:', error));
}

function confirmDelete(productId) {
  if (confirm('Are you sure you want to delete this product?')) {
      window.location.href = `../seller_dashboard/delete_product.php?id=${productId}`;
  }
}

console.log('Status Updated - 1');

document.querySelectorAll('.update-btn').forEach(button => {
    console.log('Status Updated -2');
    button.addEventListener('click', function () {
        const orderId = this.getAttribute('data-order-id');
        const statusSelect = document.querySelector(`select[data-order-id='${orderId}']`);
        const newStatus = statusSelect.value;

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "update-order-status.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                alert(`Order ID ${orderId} updated to ${newStatus}`);
            }
        };
        xhr.send(`orderId=${orderId}&newStatus=${newStatus}`);
    });
});


fetchProducts();  