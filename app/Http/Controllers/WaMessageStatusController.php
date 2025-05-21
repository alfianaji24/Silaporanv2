<?php

namespace App\Http\Controllers;

use App\Models\WaMessageStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendWhatsAppMessage;

class WaMessageStatusController extends Controller
{
    /**
     * Display the message status page
     */
    public function index()
    {
        return view('wagateway.message-status');
    }

    /**
     * Get message status data for DataTables
     */
    public function getData(Request $request)
    {
        $query = WaMessageStatus::with('employee')
            ->select('wa_message_statuses.*');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('nik')) {
            $query->where('nik', 'like', '%' . $request->nik . '%');
        }

        if ($request->filled('phone')) {
            $query->where('phone_number', 'like', '%' . $request->phone . '%');
        }

        return datatables()->of($query)
            ->addIndexColumn()
            ->make(true);
    }

    /**
     * Resend a failed or pending message
     */
    public function resend($id)
    {
        try {
            // Validate request
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|exists:wa_message_statuses,id'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid message ID'
                ], 422);
            }

            $message = WaMessageStatus::findOrFail($id);

            // Check if message can be resent
            if (!in_array($message->status, [WaMessageStatus::STATUS_PENDING, WaMessageStatus::STATUS_FAILED])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending or failed messages can be resent'
                ], 422);
            }

            // Log resend attempt
            Log::info('Attempting to resend WhatsApp message', [
                'message_id' => $message->id,
                'nik' => $message->nik,
                'phone' => $message->phone_number,
                'previous_status' => $message->status
            ]);

            // Reset message status
            $message->status = WaMessageStatus::STATUS_PENDING;
            $message->error_message = null;
            $message->message_id = null;
            $message->sent_at = null;
            $message->delivered_at = null;
            $message->read_at = null;
            $message->save();

            // Dispatch job to send message
            SendWhatsAppMessage::dispatch($message);

            return response()->json([
                'success' => true,
                'message' => 'Message queued for resending'
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to resend WhatsApp message', [
                'message_id' => $id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send WhatsApp message using the gateway
     */
    private function sendWhatsAppMessage($phoneNumber, $message)
    {
        try {
            $generalsetting = \App\Models\Pengaturanumum::where('id', 1)->first();

            if (!$generalsetting || !$generalsetting->domain_wa_gateway) {
                return [
                    'success' => false,
                    'message' => 'WhatsApp gateway tidak dikonfigurasi'
                ];
            }

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $generalsetting->domain_wa_gateway . '/send-message',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => [
                    'message' => $message,
                    'number' => $phoneNumber,
                    'file_dikirim' => ''
                ]
            ]);

            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            if ($httpCode === 200) {
                $responseData = json_decode($response, true);
                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? null
                ];
            }

            return [
                'success' => false,
                'message' => 'Gagal mengirim pesan: ' . ($response ?: 'Unknown error')
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get message statistics
     */
    public function getStats()
    {
        $stats = WaMessageStatus::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status')
            ->toArray();

        return response()->json([
            'total' => array_sum($stats),
            'pending' => $stats[WaMessageStatus::STATUS_PENDING] ?? 0,
            'sent' => $stats[WaMessageStatus::STATUS_SENT] ?? 0,
            'delivered' => $stats[WaMessageStatus::STATUS_DELIVERED] ?? 0,
            'read' => $stats[WaMessageStatus::STATUS_READ] ?? 0,
            'failed' => $stats[WaMessageStatus::STATUS_FAILED] ?? 0,
        ]);
    }
}
