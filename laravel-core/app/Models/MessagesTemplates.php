<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessagesTemplates extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'photos', 'video', 'message', 'deleted_at'];
    public function Asset()
    {
        $assets = [];
        foreach(explode(',', $this->photos) as $photo)
        {
            if($photo != null)
            {
                $assets[] = array(
                    "type" => "image",
                    "content" => $photo
                );
            }
        }
        foreach(explode(',', $this->video) as $video)
        {
            if($video != null)
            {
                $assets[] = array(
                    "type" => "video",
                    "content" => $video
                );
            }
        }
        if($this->message != null)
        {
            $assets[] = array(
                "type" => "message",
                "content" => $this->message
            );
        }
        return $assets;
    }
}
