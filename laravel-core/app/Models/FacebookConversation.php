<?php

namespace App\Models;

use App\Models\Responder;
use App\Models\FacebookPage;
use App\Models\FacebookUser;
use App\Models\FacebookMessage;
use Illuminate\Support\Facades\DB;
use App\Models\RemarketingInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacebookConversation extends Model
{
    use HasFactory;
    
    protected $fillable = ['facebook_conversation_id', 'page', 'user', 'can_reply', 'started_at', 'ended_at',
    'make_order', 'last_from', 'last_from_page_at', 'last_from_user_at'];

    public function Messages()
    {
        return FacebookMessage::where('conversation', $this->facebook_conversation_id)->orderBy('created_at', 'desc')->get();
    }
    public function Orders()
    {
        return FacebookMessage::where('conversation', $this->facebook_conversation_id)->where('message', 'like', '%سجلت الطلبية تاعك خلي برك الهاتف مفتوح باه يعيطلك الليفرور و ما تنساش الطلبية على خاطر رانا نخلصو عليها جزاك الله%')->count();
        return 0;
    }
    public function User()
    {
        $user = FacebookUser::where('facebook_user_id', $this->user)->first();
        return $user;
    }

    public function Page()
    {
        $page = FacebookPage::where('facebook_page_id', $this->page)->where('expired_at', null)->first();
        return $page;
    }

    public function Send_Message($message)
    {
        return $this->Page()->Send_Message($this->user, $message);
    }

    public function Remarketing(Remarketing $remarketing)
    {
        return $this->Page()->Remarketing($this->user, $remarketing);
    }
    public function RemarketingInterval(RemarketingInterval $remarketing)
    {
        return $this->Page()->RemarketingInterval($this->user, $remarketing);
    }
    public function Responder(Responder $responder)
    {
        return $this->Page()->Responder($this->user, $responder);
        
    }
}
