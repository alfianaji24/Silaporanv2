<?php

namespace App\Jobs;

use App\Models\WaMessageStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SendWhatsAppMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $message;

    /**
     * Create a new job instance.
     */
    public function __construct(WaMessageStatus $message)
    {
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            // Get WhatsApp gateway configuration
            $config = DB::table('pengaturanumum')
                ->where('key', 'wagateway')
                ->first();

            if (!$config) {
                throw new \Exception('WhatsApp gateway configuration not found');
            }

            $config = json_decode($config->value, true);
            $domain = $config['domain'] ?? null;

            if (!$domain) {
                throw new \Exception('WhatsApp gateway domain not configured');
            }

            // Prepare request
            $url = rtrim($domain, '/') . '/send-message';
            $data = [
                'phone' => $this->message->phone_number,
                'message' => $this->message->message_content
            ];

            // Send request
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json'
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error) {
                throw new \Exception('CURL Error: ' . $error);
            }

            if ($httpCode !== 200) {
                throw new \Exception('HTTP Error: ' . $httpCode);
            }

            $result = json_decode($response, true);

            if (!isset($result['success']) || !$result['success']) {
                throw new \Exception($result['message'] ?? 'Unknown error occurred');
            }

            // Update message status
            $this->message->update([
                'status' => WaMessageStatus::STATUS_SENT,
                'message_id' => $result['message_id'] ?? null,
                'sent_at' => now()
            ]);

            Log::info('WhatsApp message sent successfully', [
                'message_id' => $this->message->id,
                'whatsapp_message_id' => $result['message_id'] ?? null
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp message', [
                'message_id' => $this->message->id,
                'error' => $e->getMessage()
            ]);

            $this->message->update([
                'status' => WaMessageStatus::STATUS_FAILED,
                'error_message' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception)
    {
        Log::error('WhatsApp message job failed', [
            'message_id' => $this->message->id,
            'error' => $exception->getMessage()
        ]);
    }
}
