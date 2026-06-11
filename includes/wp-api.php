<?php
define('WP_API_URL', 'https://skywiseglobal.com/wp/wp-json');
define('WC_API_URL', WP_API_URL . '/wc/v3');
define('WC_CONSUMER_KEY', 'ck_0d65c4929d2204b3fba2bac11a13c122df010a6c');
define('WC_CONSUMER_SECRET', 'cs_e95af2bb3c32309e2a00ebc4dafa9062fed17cfb');

define('CACHE_DIR', __DIR__ . '/../cache');
define('CACHE_TTL', 300);

function wc_api_get_cached($endpoint)
{
    if (!file_exists(CACHE_DIR)) {
        @mkdir(CACHE_DIR, 0755, true);
    }
    $cache_file = CACHE_DIR . '/' . md5($endpoint) . '.json';
    if (file_exists($cache_file) && (time() - filemtime($cache_file)) < CACHE_TTL) {
        $data = json_decode(file_get_contents($cache_file), true);
        if ($data !== null)
            return $data;
    }
    $url = WC_API_URL . $endpoint;
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERPWD => WC_CONSUMER_KEY . ':' . WC_CONSUMER_SECRET,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_TIMEOUT => 15,
    ]);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code === 200) {
        $data = json_decode($response, true);
        file_put_contents($cache_file, $response);
        return $data;
    }

    // Fallback: If API request fails, try to return stale cache instead of an empty array
    if (file_exists($cache_file)) {
        $data = json_decode(file_get_contents($cache_file), true);
        if ($data !== null) {
            return $data;
        }
    }

    return [];
}

function get_products_by_category_slug($slug)
{
    $categories = wc_api_get_cached("/products/categories?slug=" . urlencode($slug));
    if (empty($categories))
        return [];
    $cat_id = $categories[0]['id'];
    return wc_api_get_cached("/products?category=$cat_id&per_page=50&status=publish");
}

function get_all_products()
{
    return wc_api_get_cached("/products?per_page=100&status=publish");
}

function get_all_categories()
{
    return wc_api_get_cached("/products/categories?per_page=100&hide_empty=false");
}

function get_product_by_id($id)
{
    $product = wc_api_get_cached("/products/" . intval($id));
    return empty($product['id']) ? null : $product;
}

function get_product_attribute($product, $name)
{
    if (empty($product['attributes']))
        return '';
    foreach ($product['attributes'] as $attr) {
        if (strcasecmp($attr['name'], $name) === 0) {
            return implode(', ', $attr['options'] ?? []);
        }
    }
    return '';
}
?>