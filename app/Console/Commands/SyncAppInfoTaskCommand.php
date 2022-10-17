<?php

namespace App\Console\Commands;

use App\Lib\AppInfo;
use Illuminate\Console\Command;

class SyncAppInfoTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync_app_info_task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        echo "------ sync_app_info_task todo ------" . PHP_EOL;

//        $url = 'xxx';
//
//        $site = config('site');
//
//        $serverHost = parse_url($site['url'], PHP_URL_HOST);
//
//        $serverIp = gethostbyname($serverHost);
//
//        $appInfo = new AppInfo();
//
//        $params = [
//            'server_host' => $serverHost,
//            'server_ip' => $serverIp,
//            'app_name' => $appInfo->get('name'),
//            'app_alias' => $appInfo->get('alias'),
//            'app_version' => $appInfo->get('version'),
//            'app_link' => $appInfo->get('link'),
//        ];
//
//        $client = new Client();
//
//        $client->request('POST', $url, ['form_params' => $params]);
    }

}
