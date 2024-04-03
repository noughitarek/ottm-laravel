<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\FacebookPage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remarketing extends Model
{
    use HasFactory;
    protected $fillable = ["name", "facebook_page_id", "send_after", "last_message_from", "make_order", "since", "photos", "video", "message", "deleted_at"];

    public function Pages()
    {
        $pages = FacebookPage::where('facebook_page_id', $this->facebook_page_id)->where('expired_at', null)->get();
        return $pages;
    }

    public function Send_After()
    {
        if ($this->send_after/60/60/24 > 1) {
            return (string)($this->send_after/60/60/24). ' days';
        } elseif ($this->send_after/60/60 > 1) {
            return (string)($this->send_after/60/60). ' hours';
        } elseif ($this->send_after/60 > 1) {
            return (string)($this->send_after/60). ' minutes';
        } else {
            return (string)($this->send_after). ' seconds';
        }
    }
}
