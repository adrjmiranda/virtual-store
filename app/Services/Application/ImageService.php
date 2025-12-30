<?php

namespace App\Services\Application;

use App\Domain\Images\Image;
use App\Domain\Images\ImageableId;
use App\Domain\Images\ImageableType;
use App\Domain\Images\Path;
use App\Domain\ValueObjects\Id;
use App\Exceptions\ImageCreationException;
use App\Exceptions\ImageRemoveException;
use App\Exceptions\ImageUpdateException;
use App\Repository\ImageRepository;
use Exception;
use RuntimeException;

class ImageService
{
  public function __construct(
    private ImageRepository $repo,
    private string $uploadDir = 'uploads'
  ) {
  }

  private function storeFile(array $file, string $baseDir): string
  {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
      throw new RuntimeException('Erro no upload do arquivo.');
    }

    if (!is_uploaded_file($file['tmp_name'])) {
      throw new RuntimeException('Upload inválido.');
    }

    $maxSize = phpIniToBytes(ini_get('upload_max_filesize'));
    if ($file['size'] > $maxSize) {
      throw new RuntimeException('Arquivo muito grande.');
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed = config('files')['allowed_mime_types'];

    if (!isset($allowed[$mime])) {
      throw new RuntimeException('Tipo de imagem não permitido. Tipos permitidos: ' . implode(', ', array_values($allowed)) . '.');
    }

    $extension = $allowed[$mime];
    $filename = bin2hex(random_bytes(16)) . '.' . $extension;

    $directory = rtrim($baseDir, '/') . '/' . date('Y/m');
    if (!is_dir($directory)) {
      mkdir($directory, 0755, true);
    }

    $destination = $directory . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
      throw new RuntimeException('Falha ao salvar o arquivo.');
    }

    return $destination;
  }

  public function save(array $file, int $imageableId, string $imageableType): Image
  {
    $this->repo->queryBuilder()->startTransaction();

    try {
      $path = $this->storeFile($file, $this->uploadDir);

      $image = new Image(
        null,
        new Path($path),
        new ImageableId($imageableId),
        new ImageableType($imageableType),
        null,
        null
      );

      $id = $this->repo->insert($image, ['path', 'imageable_id', 'imageable_type']);
      if (!$id) {
        throw new ImageCreationException();
      }

      $created = $this->repo->find($id);
      if (!$created) {
        throw new ImageCreationException();
      }

      $this->repo->queryBuilder()->finishTransaction();

      return $created;
    } catch (\Throwable $e) {
      $this->repo->queryBuilder()->cancelTransaction();
      if (isset($path)) {
        $this->deleteFile($path);
      }
      throw $e;
    }
  }

  public function show(int $id): ?Image
  {
    return $this->repo->find($id);
  }

  public function update(int $id, array $file): Image
  {
    $this->repo->queryBuilder()->startTransaction();

    try {
      $imageById = $this->repo->find($id);
      if ($imageById === null) {
        throw new Exception('Image not found.');
      }

      $oldPath = $imageById->pathValue();

      $path = $this->storeFile($file, $this->uploadDir);

      $image = new Image(
        new Id($imageById->idValue()),
        new Path($path),
        new ImageableId($imageById->imageableIdValue()),
        new ImageableType($imageById->imageableTypeValue()),
        null,
        null
      );

      $updated = $this->repo->update($image, ['path', 'imageable_id', 'imageable_type']);
      if (!$updated) {
        throw new ImageUpdateException();
      }

      $imageUpdated = $this->repo->find($id);
      if (!$imageUpdated) {
        throw new ImageUpdateException();
      }

      $this->deleteFile($oldPath);

      $this->repo->queryBuilder()->finishTransaction();

      return $imageUpdated;
    } catch (\Throwable $e) {
      $this->repo->queryBuilder()->cancelTransaction();
      if (isset($path)) {
        $this->deleteFile($path);
      }
      throw $e;
    }
  }

  private function deleteFile(string $path): void
  {
    if (!is_file($path)) {
      return;
    }

    if (!unlink($path)) {
      throw new RuntimeException('Falha ao remover o arquivo.');
    }
  }

  public function remove(int $id): bool
  {
    $this->repo->queryBuilder()->startTransaction();

    try {
      $imageById = $this->repo->find($id);
      if ($imageById === null) {
        throw new Exception('Image not found.');
      }

      $path = $imageById->pathValue();

      $deleted = $this->repo->delete($imageById);
      if (!$deleted) {
        throw new ImageRemoveException();
      }

      $this->repo->queryBuilder()->finishTransaction();

      $this->deleteFile($path);

      return true;
    } catch (\Throwable $e) {
      $this->repo->queryBuilder()->cancelTransaction();
      throw $e;
    }
  }
}