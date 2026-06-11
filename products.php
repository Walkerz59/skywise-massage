<?php include 'includes/header.php'; ?>
<?php require_once 'includes/wp-api.php'; ?>

<?php
$products = get_all_products();

// Define custom categories and sequence
$categories = [
    'sofa' => 'Sofa',
    'office' => 'Office Chair',
    'beach' => 'Beach Chair',
    'lounge' => 'Lounge Chair'
];

// See if a filter was requested in URL
$requested_filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';
?>

<main class="products-main">
    <!-- Explore Lineup Header -->
    <div class="products-header">
        <img src="assets/images/index-Photoroom.png" alt="Explore the lineup" class="products-header-img">
        <h2 class="products-title">Explore the lineup</h2>
    </div>

    <!-- Filter Buttons -->
    <div class="filter-container">
        <button class="btn filter-btn <?= $requested_filter === 'all' ? 'btn-primary' : 'btn-outline' ?>" data-filter="all">All Products</button>
        <?php foreach ($categories as $slug => $name): ?>
            <button class="btn filter-btn <?= $requested_filter === $slug ? 'btn-primary' : 'btn-outline' ?>" data-filter="<?= htmlspecialchars($slug) ?>"><?= htmlspecialchars($name) ?></button>
        <?php endforeach; ?>
    </div>

    <div class="product-grid" id="product-grid">
        <?php foreach ($products as $product): ?>
            <?php 
                $image = !empty($product['images']) ? $product['images'][0]['src'] : 'assets/images/index-Photoroom.png'; 
                // Create a space-separated string of category slugs for filtering
                $cat_classes = [];
                if (!empty($product['categories'])) {
                    foreach ($product['categories'] as $cat) {
                        $cat_classes[] = $cat['slug'];
                    }
                }
                $cat_class_string = implode(' ', $cat_classes);
            ?>
            <a href="product.php?id=<?= urlencode($product['id']) ?>" class="product-card product-item-filter <?= htmlspecialchars($cat_class_string) ?>">
                <img src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <span class="product-name"><?= htmlspecialchars($product['name']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const productItems = document.querySelectorAll('.product-item-filter');

    function applyFilter(filterValue) {
        filterBtns.forEach(b => {
            b.classList.remove('btn-primary');
            b.classList.add('btn-outline');
        });
        const activeBtn = document.querySelector(`.filter-btn[data-filter="${filterValue}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('btn-outline');
            activeBtn.classList.add('btn-primary');
        }

        productItems.forEach(item => {
            if (filterValue === 'all') {
                item.style.display = 'flex';
                // slight delay for animation
                setTimeout(() => item.style.opacity = '1', 50);
            } else {
                if (item.classList.contains(filterValue)) {
                    item.style.display = 'flex';
                    setTimeout(() => item.style.opacity = '1', 50);
                } else {
                    item.style.opacity = '0';
                    setTimeout(() => item.style.display = 'none', 300); // Wait for transition
                }
            }
        });
        
        // Update URL query parameter without reloading
        const url = new URL(window.location);
        url.searchParams.set('filter', filterValue);
        window.history.pushState({}, '', url);
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            applyFilter(btn.getAttribute('data-filter'));
        });
    });

    // Apply initial filter without pushing state
    const initialFilter = '<?= htmlspecialchars($requested_filter) ?>';
    
    // We already do the initial setup via inline styles for classes but here we do it via JS to sync states
    // but without pushState to avoid messing up history on load
    filterBtns.forEach(b => {
        b.classList.remove('btn-primary');
        b.classList.add('btn-outline');
    });
    const activeBtn = document.querySelector(`.filter-btn[data-filter="${initialFilter}"]`);
    if (activeBtn) {
        activeBtn.classList.remove('btn-outline');
        activeBtn.classList.add('btn-primary');
    }
    productItems.forEach(item => {
        if (initialFilter === 'all') {
            item.style.display = 'flex';
            item.style.opacity = '1';
        } else {
            if (item.classList.contains(initialFilter)) {
                item.style.display = 'flex';
                item.style.opacity = '1';
            } else {
                item.style.display = 'none';
                item.style.opacity = '0';
            }
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
