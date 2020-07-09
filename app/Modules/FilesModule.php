<?php
namespace App\Modules;

use App\UploadedFile;
use Carbon\Carbon;

class FilesModule
{
    public function create($data = [])
    {
        return UploadedFile::create($data);
    }

    public function getByUserId($userId)
    {
        $files = UploadedFile::with('tags')
                            ->where('user_id', $userId)
                            ->get();

        return $this->transform($files);

    }

    private function transform($files)
    {
        $results = [];

        foreach($files as $file) {
            $result = [
                'id' => $file->id,
                'title' => $file->title,
                'path' => $file->path,
                'description' => $file->description,
                'type' => $file->type,
                'created_at' => Carbon::parse($file->created_at)->diffForHumans(),
                'tags' => $file->tags()->get()->toArray()
            ];

            $results[] = $result;
        }

        return $results;
    }
}
