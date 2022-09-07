<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class VerifyShipped extends Mailable
{
    use Queueable, SerializesModels;

    public $viewData;

    public $subject = '邮件验证码';

    public $view = 'emails.verify';


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($viewData)
    {
        $this->subject($this->subject);
        $this->viewData = $viewData;
        if (!view()->exists($this->view)) {
            abort(404, '注册邮件模板不存在');
        }
        $this->view($this->view);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->with($this->viewData);
    }
}
