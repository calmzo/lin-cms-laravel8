<?php

namespace App\Lib\Notice\Mail;

use App\Mail\VerifyShipped;
use App\Services\VerifyService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Verify
{

    /**
     * @param string $email
     * @return bool
     */
    public function handle($email)
    {
        try {

            $verify = new VerifyService();

            $minutes = 5;
            $code = $verify->getMailCode($email, 60 * $minutes);
            $content = $this->formatContent($code, $minutes);


            $viewData = [
                'content' => $content
            ];
            Mail::to($email)->send(new VerifyShipped($viewData));

        } catch (\Exception $e) {
            Log::channel('mail')->error('Send Verify Mail Exception ' . json_encode([
                    'code' => $e->getCode(),
                    'message' => $e->getMessage(),
                ]));

            $result = false;
        }

        return $result;
    }

    protected function formatContent($code, $minutes)
    {
        return sprintf('验证码：%s，%s 分钟内有效，如非本人操作请忽略。', $code, $minutes);
    }

}
