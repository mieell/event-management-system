<?php

namespace App\Controllers;

class UploadController extends BaseController
{
    private array $folders = ['events', 'profiles', 'payments'];

    public function show(string $folder, string $file)
    {
        if (! in_array($folder, $this->folders, true)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $safeFile = basename($file);
        $path = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $safeFile;

        if (! is_file($path)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $mime = mime_content_type($path) ?: 'application/octet-stream';
        if (! in_array($mime, ['image/jpeg', 'image/png', 'image/webp'], true)) {
            return $this->response->setStatusCode(403)->setBody('Forbidden');
        }

        return $this->response
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setContentType($mime)
            ->setBody(file_get_contents($path));
    }
}
