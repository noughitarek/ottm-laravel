<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MessagesTemplates extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'photos', 'video', 'audios', 'message', 'deleted_at'];
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
        foreach(explode(',', $this->audios) as $audio)
        {
            if($audio != null)
            {
                $assets[] = array(
                    "type" => "audio",
                    "content" => $audio
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
    public function Remarketings()
    {
        return Remarketing::where('template', $this->id)->get();
    }
    public function ResponseRate()
    {
        $rate = 0;
        $pourcentage = 0;
        foreach($this->Remarketings() as $remarketing)
        {
            $rate += $remarketing->ResponseRate()[1];
            $pourcentage += $remarketing->ResponseRate()[0];
        }
        $pourcentage /= count($this->Remarketings());
        return [$pourcentage, $rate];
    }
    public function OrderRate()
    {
        $rate = 0;
        $pourcentage = 0;
        foreach($this->Remarketings() as $remarketing)
        {
            $rate += $remarketing->OrderRate()[1];
            $pourcentage += $remarketing->OrderRate()[0];
        }
        $pourcentage /= count($this->Remarketings());
        return [$pourcentage, $rate];
    }
    public function Total()
    {
        $total = 0;
        foreach($this->Remarketings() as $remarketing)
        {
            $total += $remarketing->Total();
        }
        return $total;
    }
}
