<?php

namespace App\Console\Commands;

use App\Models\FailedApiRequest;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RetryFailedApiRequests extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:retry-failed-api-requests';

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
        $failedRequests = FailedApiRequest::all();

        foreach ($failedRequests as $fr) {
            try {
                $method = $fr->method;

                switch ($method) {
                    case 'POST':
                        $request = Request::create(route('integration.store', ['res_type' => $fr->res_type]), $method, json_decode($fr->data, true));
                        break;
                    case 'PUT':
                        $request = Request::create(route('integration.update', ['res_type' => $fr->res_type, 'satusehat_id' => $fr->satusehat_id]), $method, json_decode($fr->data, true));
                        break;
                    default:
                        throw new \Exception('Invalid method');
                }

                $response = app()->handle($request);

                if ($response->isSuccessful()) {
                    $fr->delete();
                }
            } catch (\Exception $e) {
                // Log the error
                Log::error('Failed to retry API request: ' . $e->getMessage());
            }
        }
    }
}
