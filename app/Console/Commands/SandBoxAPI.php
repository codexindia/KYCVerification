<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SandBoxAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:SandBoxAPI';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $respone = Http::withHeaders(
            [
                'accept' => 'application/json',
                'x-api-key' => env('SANDBOX_API_KEY'),
                'x-api-secret' =>  env('SANDBOX_API_SECRET'),
                'x-api-version' => '1',
            ]
        )->post('https://api.sandbox.co.in/authenticate');
        $respone = json_decode($respone);
        $this->setEnv('SANDBOX_ACCESS_TOKEN', $respone->access_token);
        echo "done";
    }
    private function setEnv($key, $value)
    {
        file_put_contents(app()->environmentFilePath(), str_replace(
            $key . '=' . env($value),
            $key . '=' . $value,
            file_get_contents(app()->environmentFilePath())
        ));
    }
    
    
}
