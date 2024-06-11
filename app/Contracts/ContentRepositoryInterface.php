<?php
namespace App\Contracts;

use App\Models\Content;
use App\ValueObjects\ValidatedContent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ContentRepositoryInterface
{
    public function createContent(ValidatedContent $validatedContent): Content;
    public function getContent(Request $request): Collection;
    public function updateContent(Content $content, ValidatedContent $validatedContent): Content;
    public function getContentById(int $id): Content;
    public function deleteContentById(int $id): void;
}
