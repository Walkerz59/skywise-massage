<?php

$nav_links = [
    ['url' => 'about.php', 'label' => 'About Us'],
    ['url' => 'products.php', 'label' => 'Massage Sofa'],
    ['url' => 'products.php', 'label' => 'Massage Office Chair'],
    ['url' => 'products.php', 'label' => 'Massage Beach Chair'],
    ['url' => 'products.php', 'label' => 'Massage Lounge Chair'],
];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="icon" href="assets/images/skywise-favicon.png">

    <title>Skywise Global Enterprise</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo file_exists('assets/css/style.css') ? filemtime('assets/css/style.css') : '1.0'; ?>">
</head>

<body>

    <header>
        <div class="logo">
            <a href="index.php"><img src="assets/images/skywise-logo.png" alt="SKYWISE Logo"></a>
        </div>
        
        <div class="menu-toggle" id="mobile-menu">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>

        <div class="header-right" id="nav-sidebar">
            <nav>
                <ul>
                    <?php foreach ($nav_links as $link): ?>
                        <li><a href="<?= htmlspecialchars($link['url']) ?>"><?= htmlspecialchars($link['label']) ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>
        <div class="sidebar-overlay" id="sidebar-overlay"></div>
    </header>