<?php

declare(strict_types=1);

namespace Src\Storage;

use finfo;

class Storage
{
    public function generateFileName(string $extension): string
    {
        return md5(uniqid((string)rand(), true)) . ".$extension";
    }

    public function saveUploadedFile(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $fileInfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $fileInfo->file($file['tmp_name']);
        $extension = substr($mime, strpos($mime, '/') + 1);
        $pathToSave = __DIR__ . '/../../public/photos';

        if (!is_dir($pathToSave)) {
            mkdir($pathToSave, 0777, true);
        }

        $fileName = $this->generateFileName($extension);

        move_uploaded_file($file['tmp_name'], "$pathToSave/$fileName");

        return $fileName;
    }
}