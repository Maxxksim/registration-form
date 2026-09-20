<?php

declare(strict_types=1);

namespace Src\Storage;

class Storage
{
    public function generateFileName($extension): string
    {
        return md5(uniqid((string)rand(), true)) . ".$extension";
    }

    public function saveUploadedFile(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $pathToSave = __DIR__ . '/../../public/photos';

        if (!is_dir($pathToSave)) {
            mkdir($pathToSave, 0777, true);
        }

        $fileName = $this->generateFileName(pathinfo($file['name'], PATHINFO_EXTENSION));

        move_uploaded_file($file['tmp_name'], "$pathToSave/$fileName");

        return $fileName;
    }
}