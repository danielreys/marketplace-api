<?php
namespace App\ValueObjects;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

final class ValidatedContent extends Request
{
    public string $title;
    public ?string $description;
    public ?UploadedFile $media;

    public function __construct(array $data)
    {
        $this->title = $data['title'] ?? null;
        $this->description = $data['description'] ?? null;
        $this->media = $data['media'] ?? null;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getMedia(): ?UploadedFile
    {
        return $this->media;
    }
}
