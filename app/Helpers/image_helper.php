<?php

if (!function_exists('processUploadedImages')) {

    function processUploadedImages(array $files, string $inputName, string $savePath, int $quality = 80, bool $convertToWebP = true): array
    {
        $uploadedImages = [];

        if (!isset($files[$inputName])) {
            return $uploadedImages; // no file uploaded
        }

        $fileSet = $files[$inputName];

        // Normalize single file to array
        if (!is_array($fileSet)) {
            $fileSet = [$fileSet];
        }

        foreach ($fileSet as $img) {
            if ($img->isValid() && !$img->hasMoved()) {

                $mime = $img->getMimeType();
                $ext  = strtolower($img->getExtension());

                // Always generate new file name
                $newName = uniqid() . ($convertToWebP ? '.webp' : '.' . $ext);
                $targetPath = rtrim($savePath, '/') . '/' . $newName;

                if ($convertToWebP) {
                    if (in_array($mime, ['image/jpeg', 'image/png']) || in_array($ext, ['jpg', 'jpeg', 'png'])) {
                        $image = ($mime === 'image/jpeg' || in_array($ext, ['jpg', 'jpeg']))
                            ? imagecreatefromjpeg($img->getTempName())
                            : imagecreatefrompng($img->getTempName());

                        if ($mime === 'image/png' || $ext === 'png') {
                            imagepalettetotruecolor($image);
                        }

                        if ($image) {
                            imagewebp($image, $targetPath, $quality);
                            imagedestroy($image);
                            $uploadedImages[] = $targetPath;
                        }
                    } elseif ($mime === 'image/webp' || $ext === 'webp') {
                        $image = imagecreatefromwebp($img->getTempName());
                        if ($image) {
                            imagewebp($image, $targetPath, $quality);
                            imagedestroy($image);
                            $uploadedImages[] = $targetPath;
                        }
                    }
                } else {
                    // Save as original format
                    $img->move($savePath, $newName);
                    $uploadedImages[] = $targetPath;
                }
            }
        }

        return $uploadedImages;
    }
}

