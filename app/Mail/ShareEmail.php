<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str; 

class ShareEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $personalMsg;

    public function __construct($post,$personalMsg)
    {
        $this->post = $post;
        $this->personalMsg = $personalMsg;
    }

    public function build()
    {
        return $this->view('mail/share')
                    ->with(['post', $this->post, 'customMessage' => $this->personalMsg])
                    ->subject(config('app.name').' - '. Str::limit($this->post->title,50) );
    }
}
