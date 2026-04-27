<?php

function saveBase64Image($croppedImage, $uploadDir, $prefix = 'img-')
{
    if (empty($croppedImage)) {
        return ['success' => false, 'error' => 'empty_image'];
    }

    if (!preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,/', $croppedImage, $matches)) {
        return ['success' => false, 'error' => 'invalid_type'];
    }

    $mimeType = strtolower($matches[1]);
    $extension = $mimeType === 'jpeg' ? 'jpg' : $mimeType;

    $base64Data = preg_replace('/^data:image\/(jpeg|jpg|png|webp);base64,/', '', $croppedImage);
    $base64Data = str_replace(' ', '+', $base64Data);

    $binaryData = base64_decode($base64Data);

    if ($binaryData === false) {
        return ['success' => false, 'error' => 'upload_failed'];
    }

    $maxFileSize = 5 * 1024 * 1024;
    if (strlen($binaryData) > $maxFileSize) {
        return ['success' => false, 'error' => 'too_large'];
    }

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadDir = rtrim($uploadDir, '/') . '/';
    $newFileName = $prefix . time() . '-' . mt_rand(1000, 9999) . '.' . $extension;
    $destination = $uploadDir . $newFileName;

    if (file_put_contents($destination, $binaryData) === false) {
        return ['success' => false, 'error' => 'upload_failed'];
    }

    return [
        'success'   => true,
        'file_name' => $newFileName,
        'file_path' => $destination
    ];
}

function deleteImageFile($filePath)
{
    if (!empty($filePath) && file_exists($filePath)) {
        return unlink($filePath);
    }

    return false;
}
?>