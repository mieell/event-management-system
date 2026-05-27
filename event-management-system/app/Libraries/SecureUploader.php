<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

class SecureUploader
{
    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'webp'];
    private const IMAGE_MIMES = ['image/jpeg', 'image/png', 'image/webp'];

    public function image(?UploadedFile $file, string $folder, int $maxKilobytes = 2048): ?string
    {
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if (! $file->isValid()) {
            throw new RuntimeException($file->getErrorString());
        }

        if ($file->getSizeByUnit('kb') > $maxKilobytes) {
            throw new RuntimeException("The selected image must be {$maxKilobytes}KB or smaller.");
        }

        $extension = strtolower($file->getClientExtension());
        if (! in_array($extension, self::IMAGE_EXTENSIONS, true)) {
            throw new RuntimeException('Only JPG, PNG, and WEBP image files are allowed.');
        }

        $mime = $file->getMimeType();
        if (! in_array($mime, self::IMAGE_MIMES, true)) {
            throw new RuntimeException('The uploaded file content is not a valid image.');
        }

        $safeName = bin2hex(random_bytes(16)) . '.' . $extension;
        $target = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $folder;

        if (! is_dir($target) && ! mkdir($target, 0755, true) && ! is_dir($target)) {
            throw new RuntimeException('The upload directory could not be created.');
        }

        $file->move($target, $safeName, true);

        return $safeName;
    }
}
