<?php
/**
 * Helper Functions
 * Skywise Global Enterprise
 */


/**
 * Generate WhatsApp link
 */
function whatsapp_link($product_name, $phone = '601116311160')
{
    $message = urlencode("Hi, I'm interested in the " . $product_name);
    return "https://wa.me/$phone?text=$message";
}

/**
 * Generate Gmail compose link
 */
function gmail_link($product_name, $email = 'info@skywiseglobal.com')
{
    $subject = urlencode("Inquiry about " . $product_name);
    return "https://mail.google.com/mail/?view=cm&fs=1&to=$email&su=$subject";
}


?>