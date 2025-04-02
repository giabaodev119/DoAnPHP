<!-- filepath: c:\xampp\htdocs\DoAnPHP\app\views\partials\gridview.php -->
<?php
/**
 * Partial view để hiển thị danh sách sản phẩm theo dạng lưới (grid)
 * 
 * Cách sử dụng:
 * include 'app/views/partials/gridview.php';
 * 
 * Tham số truyền vào:
 * @param array $products - Mảng các sản phẩm cần hiển thị
 * @param string $title - Tiêu đề phần sản phẩm (tùy chọn)
 * @param bool $showDiscount - Có hiển thị nhãn giảm giá hay không (mặc định: true)
 * @param bool $showAddToCart - Có hiển thị nút thêm vào giỏ hàng hay không (mặc định: true)
 */

// Đảm bảo biến $products đã được định nghĩa
if (!isset($products) || !is_array($products)) {
    return;
}

// Sử dụng giá trị mặc định cho các tham số
$title = $title ?? 'Danh sách sản phẩm';
$showDiscount = $showDiscount ?? true;
$showAddToCart = $showAddToCart ?? true;

// Tạo ID duy nhất cho gridview này để tránh xung đột nếu có nhiều gridview trên cùng trang
$gridId = 'product-grid-' . uniqid();
?>

<div class="gridview-container" style="border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 30px; background-color: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <?php if (!empty($title)): ?>
    <h2 class="product-section-title" style="text-align: center; margin-bottom: 25px; color: #333; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0;"><?= htmlspecialchars($title) ?></h2>
    <?php endif; ?>

    <div class="product-slider-container" style="position: relative;">
        <!-- Nút điều hướng trái -->
        <button class="slide-arrow prev-arrow" onclick="changeSlide('<?= $gridId ?>', -1)" style="position: absolute; left: -15px; top: 50%; transform: translateY(-50%); z-index: 100; background: rgba(255,255,255,0.8); border: 1px solid #ddd; border-radius: 50%; width: 40px; height: 40px; font-size: 18px; cursor: pointer; display: none;">
            &lt;
        </button>
        
        <div id="<?= $gridId ?>" class="product-grid" style="display: flex; flex-wrap: wrap; margin: 0 -10px; overflow: hidden;">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $index => $product): ?>
                    <div class="product-card slide" data-slide-index="<?= floor($index / 4) ?>" style="width: calc(25% - 20px); margin: 0 10px 20px; box-sizing: border-box; <?= floor($index / 4) > 0 ? 'display: none;' : '' ?>">
                        <a href="index.php?controller=product&action=detail&id=<?= $product['id'] ?>" style="text-decoration: none; color: inherit; display: block;">
                            <?php
                            // Xác định đường dẫn hình ảnh
                            $imagePath = '';
                            
                            // Trường hợp 1: Có trường image_path (từ subquery trong getRelatedProducts)
                            if (!empty($product['image_path'])) {
                                $imagePath = 'public/images/' . $product['image_path'];
                            }
                            // Trường hợp 2: Có trường image từ kết quả cũ
                            else if (!empty($product['image'])) {
                                $imagePath = 'public/images/' . $product['image'];
                            }
                            // Trường hợp 3: Sử dụng ảnh mặc định
                            else {
                                $imagePath = 'public/images/default.jpg';
                            }
                            
                            // Kiểm tra tệp có tồn tại không
                            if (!file_exists($imagePath)) {
                                $imagePath = 'public/images/default.jpg';
                            }
                            ?>
                            <div style="height: 200px; display: flex; align-items: center; justify-content: center; margin-bottom: 10px; border: 1px solid #f0f0f0; border-radius: 5px; overflow: hidden;">
                                <img src="<?= htmlspecialchars($imagePath) ?>" alt="<?= htmlspecialchars($product['name']) ?>" style="max-width: 100%; max-height: 180px; object-fit: contain;">
                            </div>
                            
                            <?php if ($product['featured'] == 1): ?>
                                <div class="label-hot" style="background-color: #ff6600; color: white; display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 12px; margin-bottom: 5px;">Yêu thích</div>
                            <?php endif; ?>
                            
                            <div class="product-name" style="font-weight: bold; font-size: 16px; margin-bottom: 5px; height: 40px; overflow: hidden;"><?= htmlspecialchars($product['name']) ?></div>
                            
                            <?php if ($showDiscount): ?>
                                <div class="discount" style="color: #e74c3c; font-size: 13px; margin-bottom: 5px;">Giảm giá</div>
                            <?php endif; ?>
                            
                            <div class="price" style="color: #e74c3c; font-weight: bold; font-size: 18px;"><?= number_format($product['price'], 0, ',', '.') ?> đ</div>
                            <div class="sold" style="color: #888; font-size: 13px;">Đã bán <?= isset($product['sold']) ? $product['sold'] : 'N/A' ?></div>
                        </a>
                        
                        <?php if ($showAddToCart): ?>
                        <div class="product-actions" style="margin-top: 10px;">
                            <a href="index.php?controller=product&action=addToCart&id=<?= $product['id'] ?>" 
                               class="btn-add-to-cart" style="background-color: #2ecc71; color: white; padding: 8px 15px; border-radius: 4px; text-decoration: none; display: inline-block; text-align: center; width: 100%;">Thêm vào giỏ</a>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-products" style="text-align: center; padding: 20px; width: 100%;">
                    <p>Không có sản phẩm nào.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Nút điều hướng phải -->
        <button class="slide-arrow next-arrow" onclick="changeSlide('<?= $gridId ?>', 1)" style="position: absolute; right: -15px; top: 50%; transform: translateY(-50%); z-index: 100; background: rgba(255,255,255,0.8); border: 1px solid #ddd; border-radius: 50%; width: 40px; height: 40px; font-size: 18px; cursor: pointer; <?= count($products) <= 4 ? 'display: none;' : '' ?>">
            &gt;
        </button>
    </div>
    
    <!-- Hiển thị điểm trang -->
    <?php $totalSlides = ceil(count($products) / 4); ?>
    <?php if ($totalSlides > 1): ?>
    <div class="slide-dots" style="text-align: center; margin-top: 15px;">
        <?php for ($i = 0; $i < $totalSlides; $i++): ?>
            <span class="dot <?= $i === 0 ? 'active' : '' ?>" onclick="goToSlide('<?= $gridId ?>', <?= $i ?>)" style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background-color: <?= $i === 0 ? '#ff6600' : '#ddd' ?>; margin: 0 5px; cursor: pointer;"></span>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</div>

<!-- JavaScript cho slider -->
<script>
// Tránh định nghĩa lại nếu đã tồn tại
if (typeof initializeSlider !== 'function') {
    const sliderState = {};
    
    function initializeSlider(gridId) {
        if (!sliderState[gridId]) {
            sliderState[gridId] = {
                currentSlide: 0,
                totalSlides: Math.ceil(document.querySelectorAll(`#${gridId} .product-card`).length / 4)
            };
        }
        
        // Hiển thị/ẩn nút điều hướng
        updateArrowVisibility(gridId);
    }
    
    function updateArrowVisibility(gridId) {
        const prevArrow = document.querySelector(`#${gridId}`).parentNode.querySelector('.prev-arrow');
        const nextArrow = document.querySelector(`#${gridId}`).parentNode.querySelector('.next-arrow');
        
        if (sliderState[gridId].currentSlide <= 0) {
            prevArrow.style.display = 'none';
        } else {
            prevArrow.style.display = 'block';
        }
        
        if (sliderState[gridId].currentSlide >= sliderState[gridId].totalSlides - 1) {
            nextArrow.style.display = 'none';
        } else {
            nextArrow.style.display = 'block';
        }
    }
    
    function changeSlide(gridId, direction) {
        const newSlideIndex = sliderState[gridId].currentSlide + direction;
        
        if (newSlideIndex >= 0 && newSlideIndex < sliderState[gridId].totalSlides) {
            goToSlide(gridId, newSlideIndex);
        }
    }
    
    function goToSlide(gridId, slideIndex) {
        // Cập nhật trạng thái
        sliderState[gridId].currentSlide = slideIndex;
        
        // Ẩn tất cả slides
        const slides = document.querySelectorAll(`#${gridId} .product-card`);
        slides.forEach(slide => {
            slide.style.display = 'none';
        });
        
        // Hiển thị slides của trang hiện tại
        const startIndex = slideIndex * 4;
        const endIndex = Math.min(startIndex + 4, slides.length);
        
        for (let i = startIndex; i < endIndex; i++) {
            slides[i].style.display = 'block';
        }
        
        // Sửa lại selector để lấy đúng các dots
        const dotsContainer = document.querySelector(`#${gridId}`).closest('.gridview-container').querySelector('.slide-dots');
        if (dotsContainer) {
            const dots = dotsContainer.querySelectorAll('.dot');
            dots.forEach((dot, index) => {
                // Cập nhật cả màu sắc và class active
                if (index === slideIndex) {
                    dot.style.backgroundColor = '#ff6600';
                    dot.classList.add('active');
                } else {
                    dot.style.backgroundColor = '#ddd';
                    dot.classList.remove('active');
                }
            });
        }
        
        // Cập nhật hiển thị nút điều hướng
        updateArrowVisibility(gridId);
    }
}

// Khởi tạo slider khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    initializeSlider('<?= $gridId ?>');
});
</script>