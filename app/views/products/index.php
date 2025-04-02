<!DOCTYPE html>
<html lang="vi">
    <head>
        <meta charset="UTF-8">
        <title>Danh sách sản phẩm</title>
        <link rel="stylesheet" href="public/css/style.css">
    </head>
    <body>

    <h1 style="text-align: center;">Danh sách sản phẩm</h1>

    <!-- Form tìm kiếm -->
    <form action="index.php" method="GET" style="text-align: center; margin-bottom: 20px;">
        <input type="hidden" name="controller" value="product">
        <input type="hidden" name="action" value="index">
        
        <input type="text" name="keyword" placeholder="Nhập từ khóa tìm kiếm..." 
            value="<?= isset($_GET['keyword']) ? $_GET['keyword'] : '' ?>" 
            style="padding: 10px; width: 40%; border: 1px solid #ddd; border-radius: 5px;">
        
        <select name="category" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            <option value="">Tất cả danh mục</option>
            <?php
            require_once 'app/models/Category.php';
            $categoryModel = new Category();
            $categories = $categoryModel->getAll();
            foreach ($categories as $cat) :
            ?>
                <option value="<?= $cat['id'] ?>" <?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'selected' : '' ?>>
                    <?= $cat['name'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" style="padding: 10px 15px; background-color: #ff6600; color: white; border: none; cursor: pointer; border-radius: 5px;">
            🔍 Tìm kiếm
        </button>
    </form>

    <!-- Sử dụng partial gridview để hiển thị danh sách sản phẩm -->
        <?php
            // Thiết lập các tham số cho gridview
            $title = 'Danh sách sản phẩm'; 
            $showDiscount = false; // Không hiển thị nhãn giảm giá
            $showAddToCart = true; // Hiển thị nút thêm vào giỏ hàng

            // Load hình ảnh cho các sản phẩm từ bảng product_images
            if (!empty($products)) {
                // Lấy danh sách tất cả ảnh cho các sản phẩm
                $productIds = array_column($products, 'id');
                $productModel = new Product();
                
                foreach ($products as &$product) {
                    // Lấy ảnh đầu tiên của sản phẩm
                    $images = $productModel->getProductImages($product['id']);
                    if (!empty($images)) {
                        $product['image_path'] = $images[0]['image_path'];
                    }
                }
                unset($product); // Xóa tham chiếu
            }

            // Include partial view
            include 'app/views/partials/gridview.php';
        ?>
    </body>
</html>
