<?php

namespace App\Jobs;

use App\User;
use App\Mail\NewRequisition;
use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use Swagger\Client\Configuration;
use Swagger\Client\ApiException;
use Swagger\Client\Model\Body1;
use Swagger\Client\ObjectSerializer;
use Swagger\Client\Api\EmailApi;
use Swagger\Client\Model\User as MorphUser;
use Swagger\Client\Model\Sender;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     *
     */
    public $sender;
    public $recipient;
    public $subject;
    public $content;

    public function __construct($sender, $recipient, $subject, $content)
    {
        $this->sender = $sender;
        $this->recipient = $recipient;
        $this->subject = $subject;
        $this->content = $content;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if (config('app.users_provider') == 'mysql') {
            return;
        }
        // Configure HTTP bearer authorization: BearerAuth
        $token = Config('morpheus.token');
        $base_url = config('morpheus.base_url');
        $sender_account = config('morpheus.sender_account');

        $config = Configuration::getDefaultConfiguration()
            ->setAccessToken($token);
        $config->setHost($base_url);

        $sender = new Sender(['name' => $this->sender->name, 'account' => $sender_account]);
        $recipient = new MorphUser(['name' => $this->recipient->name, 'email' => $this->recipient->email]);

        $data = [
            'sender' => $sender,
            'recipients' => [$recipient],
            // 'reply_to' => [],
            'subject' => $this->subject,
            'content' => $this->content,
            //   'parameters' => [],
        ];

        $apiInstance = new EmailApi(new Client(), $config);
        $body = new Body1($data);

        try {
            $apiInstance->sendEmail($body);
        } catch (Exception $e) {
            echo 'Exception when calling EmailApi->sendEmail: ', $e->getMessage(), PHP_EOL;
        }


    }


}
