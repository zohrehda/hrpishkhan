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
        // Configure HTTP bearer authorization: BearerAuth
        $token = Config('morpheus.token');
        $base_url = config('morpheus.base_url');

        $config = Configuration::getDefaultConfiguration()
            ->setAccessToken($token);
        $config->setHost($base_url);

        $data = [
            'sender' => ['email' => $this->sender->email],
            'recipients' => ['email' => $this->recipient->email],
            'reply_to' => [],
            'subject' => $this->subject,
            'content' => $this->content,
            'parameters' => [],
        ];
        $apiInstance = new EmailApi(new Client(), $config);
        $body = new Body1($data);

        try {
            $apiInstance->sendEmail($body);
        } catch (Exception $e) {
            echo 'Exception when calling EmailApi->sendEmail: ', $e->getMessage(), PHP_EOL;
        }


    }

    /* public function handle()
     {
         //   Mail::to($this->user)->send(new NewRequisition($this->user->name,$this->msg ));

         $token = Config('morpheus.token');
         $base_url = config('morpheus.base_url');
         $resourcePath = '/api/email';

         $headers = [
             'Authorization' => 'Bearer ' . $token,
             'Accept' => 'application/json',
         ];

         $data = [
             'sender' => ['email' => $this->sender->email],
             'recipients' => ['email' => $this->recipient->email],
             'reply_to' => [],
             'subject' => $this->subject,
             'content' => $this->content,
             'parameters' => [],
         ];
         $client = new Client(['base_uri' => $base_url]);

         $request = $client->request('POST', $resourcePath, [
             'headers' => $headers,
             'form_params' => $data
         ]);


     }*/

    /*
         public function handle()
        {
            //   Mail::to($this->user)->send(new NewRequisition($this->user->name,$this->msg ));

            $client = new Client(['base_uri' => 'http://httpbin.org']);
            $headers = [

                'Accept' => 'application/json',
            ];
            $request = $client->request('POST', '/post', [
                'headers' => $headers,

            ]);

            dd($request->getBody());

        }*/
}
