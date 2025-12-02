<?php
// sync_utils.php

// دالة لمسح محتويات مجلد (لعملية TRUNCATE للصور)
function clearFolder($folderPath)
{
    if (!is_dir($folderPath)) {
        if (mkdir($folderPath, 0777, true)) return;
        return;
    }
    
    $files = glob($folderPath . '/*');
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); 
        } elseif (is_dir($file)) {
            //Recursive deletion is complex, so for simplicity we only delete files inside
            array_map('unlink', glob("$file/*"));
        }
    }
}

// دالة لتحميل الصورة من رابط وحفظها
function handleImageDownload($url, $saveDir, $prefix) {
    if (empty($url)) return null;

    $imageContent = @file_get_contents($url);
    if (!$imageContent) return null; 

    $ext = pathinfo($url, PATHINFO_EXTENSION) ?: 'jpg';
    $localFileName = $prefix . uniqid() . '.' . $ext;
    $savePath = $saveDir . $localFileName;

    if (file_put_contents($savePath, $imageContent)) {
        return $localFileName;
    }
    return null;
}