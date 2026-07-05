<?php
$baseUrl = 'https://jamiathulhind.com/';
$files = [
    // CSS
    'assets/css/plugins/animate.min.css',
    'assets/css/plugins/fontawesome.min.css',
    'assets/css/vendor/bootstrap.min.css',
    'assets/css/plugins/swiper.min.css',
    'assets/css/vendor/magnific-popup.css',
    'assets/css/vendor/metismenu.css',
    'assets/css/plugins/nice-select.css',
    'assets/css/plugins/jquery-ui.css',
    'assets/css/style3.css',

    // JS
    'assets/js/vendor/jquery.min.js',
    'assets/js/plugins/bootstrap.min.js',
    'assets/js/vendor/jquery-ui.js',
    'assets/js/vendor/waw.js',
    'assets/js/vendor/metismenu.js',
    'assets/js/vendor/magnifying-popup.js',
    'assets/js/plugins/swiper.js',
    'assets/js/plugins/counterup.js',
    'assets/js/vendor/waypoint.js',
    'assets/js/plugins/isotop.js',
    'assets/js/plugins/imagesloaded.pkgd.min.js',
    'assets/js/plugins/sticky-sidebar.js',
    'assets/js/plugins/resize-sensor.js',
    'assets/js/plugins/twinmax.js',
    'assets/js/plugins/contact.form.js',
    'assets/js/plugins/nice-select.min.js',
    'assets/js/main.js',

    // Images & SVGs
    'assets/images/logo/logo_vert_blue.png',
    'assets/images/icon/e-cap.svg',
    'assets/images/icon/bar__line.svg',
    'assets/images/icon/note_khata.svg',
    'assets/images/icon/book.svg',
    'assets/images/icon/compas_scale.svg',
    'assets/images/banner/bg10.jpg',
    'assets/images/banner/bg2.jpg',
    'assets/images/banner/bg3.jpg',
    'assets/images/program/foundation.jpg',
    'assets/images/program/bachelor.jpg',
    'assets/images/program/masters.jpg',
    'assets/images/program/research.jpg',
    'assets/images/program/diploma.jpg',
    'assets/images/program/short.jpg',
    'assets/images/campus/assembly.jpg',
    'assets/images/campus/maharjan2025.jpg',
    'assets/images/campus/library.JPG',
    'uploads/events/69c6203c1a213.jpg',
    'uploads/news/68ede6f0342d6.jpg',
    'uploads/news/68ac1c4eac522.jpg',
    'uploads/news/684d752070617.jpg',
];

// We will track downloaded files to prevent infinite recursion
$downloaded = [];

function downloadFile($relPath) {
    global $baseUrl, $downloaded;
    
    // Normalize path
    $relPath = trim($relPath);
    if (empty($relPath)) return;
    
    // Clean query parameters from filename if any (e.g., ?v=4.7.0)
    $cleanPath = explode('?', $relPath)[0];
    
    if (isset($downloaded[$cleanPath])) return;
    
    $sourceUrl = $baseUrl . $relPath;
    $destPath = __DIR__ . '/' . $cleanPath;
    
    // Create folders
    $dir = dirname($destPath);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    
    echo "Downloading: $sourceUrl -> $cleanPath... ";
    
    // Fetch file content
    // Use stream context with a user agent to prevent blocks
    $opts = [
        "http" => [
            "method" => "GET",
            "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36\r\n"
        ]
    ];
    $context = stream_context_create($opts);
    $content = @file_get_contents($sourceUrl, false, $context);
    
    if ($content === false) {
        echo "FAILED\n";
        return;
    }
    
    file_put_contents($destPath, $content);
    echo "SUCCESS\n";
    
    $downloaded[$cleanPath] = true;
    
    // If it's a CSS file, parse for url(...) references
    if (pathinfo($cleanPath, PATHINFO_EXTENSION) === 'css') {
        parseCssAndDownloadUrls($cleanPath, $content);
    }
}

function parseCssAndDownloadUrls($cssRelPath, $cssContent) {
    $cssDir = dirname($cssRelPath);
    
    // Regex to match url(...) inside CSS
    preg_match_all('/url\s*\(\s*[\'"]?([^\'"\)]+)[\'"]?\s*\)/i', $cssContent, $matches);
    
    if (!empty($matches[1])) {
        foreach ($matches[1] as $url) {
            // Ignore external URLs or data-URIs
            if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0 || strpos($url, 'data:') === 0) {
                continue;
            }
            
            // Resolve relative path
            // e.g. from assets/css/style3.css, a URL like ../images/icon.png -> assets/images/icon.png
            $parts = explode('/', $cssDir);
            $urlParts = explode('/', $url);
            
            foreach ($urlParts as $part) {
                if ($part === '.') {
                    continue;
                } elseif ($part === '..') {
                    array_pop($parts);
                } else {
                    $parts[] = $part;
                }
            }
            
            $resolvedRelPath = implode('/', $parts);
            downloadFile($resolvedRelPath);
        }
    }
}

// Start download
foreach ($files as $file) {
    downloadFile($file);
}

echo "\nAll assets downloaded successfully!\n";

