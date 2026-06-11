<?php include 'includes/header.php'; ?>
<?php include_once 'includes/functions.php'; ?>
<?php require_once 'includes/wp-api.php'; ?>

<?php
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$product = get_product_by_id($id);

$name = 'Product Not Found';
$images = [];
$image = 'assets/images/index-Photoroom.png';
$color = 'Various Colors Available';
$dimension = '';
$subtitle = 'Massage Office Chair';

if ($product) {
    $name = $product['name'];

    // Collect ALL product images
    if (!empty($product['images'])) {
        $images = $product['images'];
        $image = $images[0]['src'];
    }

    // Get color from WooCommerce attributes
    $color = get_product_attribute($product, 'Color');
    if (empty($color))
        $color = get_product_attribute($product, 'pa_color');
    if (empty($color))
        $color = 'Various Colors Available';

    // Build combined dimension string from WooCommerce fields
    $dims = $product['dimensions'] ?? [];
    $dim_parts = [];
    if (!empty($dims['length']))
        $dim_parts[] = $dims['length'];
    if (!empty($dims['width']))
        $dim_parts[] = $dims['width'];
    if (!empty($dims['height']))
        $dim_parts[] = $dims['height'];
    $dimension = !empty($dim_parts) ? implode(' x ', $dim_parts) . ' mm' : '';

    // Fallback: try a combined 'Dimension' attribute if WC fields are empty
    if (empty($dimension))
        $dimension = get_product_attribute($product, 'Dimension');

    if (!empty($product['categories'])) {
        $allowed_categories = [
            'Massage Sofa',
            'Massage Office Chair',
            'Massage Beach Chair',
            'Massage Lounge Chair'
        ];
        
        $found_category = false;
        foreach ($product['categories'] as $cat) {
            $cat_name = trim($cat['name']);
            // Exact match
            if (in_array($cat_name, $allowed_categories)) {
                $subtitle = $cat_name;
                $found_category = true;
                break;
            }
            // Loose match fallback
            if (stripos($cat_name, 'sofa') !== false) {
                $subtitle = 'Massage Sofa';
                $found_category = true; break;
            } elseif (stripos($cat_name, 'office') !== false) {
                $subtitle = 'Massage Office Chair';
                $found_category = true; break;
            } elseif (stripos($cat_name, 'beach') !== false) {
                $subtitle = 'Massage Beach Chair';
                $found_category = true; break;
            } elseif (stripos($cat_name, 'lounge') !== false) {
                $subtitle = 'Massage Lounge Chair';
                $found_category = true; break;
            }
        }
        
        if (!$found_category) {
            $subtitle = 'Massage Office Chair'; // Default safe fallback
        }
    }
}
?>



<main class="product-page-main">
    <div class="pd-wrapper">

        <!-- Product Card -->
        <div class="pd-card">

            <!-- Left: Gallery -->
            <div class="pd-gallery">
                <!-- Back Button -->
                <a href="products.php" class="pd-back" title="Back to Products">
                    <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.2" fill="none"
                        stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                </a>

                <img id="pd-main-image" class="pd-main-img" src="<?= htmlspecialchars($image) ?>"
                    alt="<?= htmlspecialchars($name) ?>">

                <?php if (count($images) > 1): ?>
                    <div class="pd-thumbs">
                        <?php foreach ($images as $i => $img): ?>
                            <img class="pd-thumb <?= $i === 0 ? 'active' : '' ?>" src="<?= htmlspecialchars($img['src']) ?>"
                                alt="<?= htmlspecialchars($name) ?> view <?= $i + 1 ?>"
                                onmouseenter="pdSelectImage(this, '<?= htmlspecialchars($img['src']) ?>')">
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Right: Details -->
            <div class="pd-details">
                <div class="pd-badge">Quotation Available</div>

                <h1 class="pd-title"><?= htmlspecialchars($name) ?></h1>
                <div class="pd-subtitle"><?= htmlspecialchars($subtitle) ?></div>

                <hr class="pd-divider">

                <table class="pd-table">
                    <tr>
                        <td>Color</td>
                        <td><?= htmlspecialchars($color) ?></td>
                    </tr>
                    <?php if (!empty($dimension) && $dimension !== 'Standard Dimensions'): ?>
                        <tr>
                            <td>Dimension</td>
                            <td><?= htmlspecialchars($dimension) ?></td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td>Inquiry</td>
                        <td>WhatsApp / Email</td>
                    </tr>
                </table>

                <!-- CTA -->
                <div class="pd-cta-title">Request Quotation</div>
                <div class="pd-cta-sub">Get price, MOQ, lead time and product details.</div>

                <a href="<?= whatsapp_link($name) ?>" target="_blank" rel="noopener noreferrer"
                    class="pd-btn pd-btn-wa">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    WhatsApp for Quotation
                </a>

                <a href="<?= gmail_link($name) ?>" target="_blank" rel="noopener noreferrer"
                    class="pd-btn pd-btn-email">
                    <svg viewBox="0 0 24 24" fill="currentColor" width="18" height="18">
                        <path
                            d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                    </svg>
                    Email for Quotation
                </a>
            </div>

        </div><!-- /.pd-card -->

        <!-- Trust Banner -->
        <div class="pd-banner">
            <svg class="pd-banner-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                <polyline points="9 12 11 14 15 10"></polyline>
            </svg>
            <div>
                <div class="pd-banner-title">Product inquiry handled by Skywise</div>
                <div class="pd-banner-sub">B2B quotation · Product coordination · Export support</div>
            </div>
        </div>

    </div><!-- /.pd-wrapper -->
</main>

<script>
    function pdSelectImage(thumbEl, src) {
        // Swap main image
        document.getElementById('pd-main-image').src = src;
        // Update active thumb highlight
        document.querySelectorAll('.pd-thumb').forEach(function (t) {
            t.classList.remove('active');
        });
        thumbEl.classList.add('active');
    }
</script>

<?php include 'includes/footer.php'; ?>