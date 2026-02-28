<?php

namespace App\Jobs;

use App\Models\PaymentTransaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckPaymentStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected PaymentTransaction $paymentTransaction;

    /**
     * Create a new job instance.
     */
    public function __construct(PaymentTransaction $paymentTransaction)
    {
        $this->paymentTransaction = $paymentTransaction;
    }

    /**
     * Execute the job.
     * Kiểm tra thanh toán sau 3 phút - nếu vẫn pending thì đánh dấu expired
     */
    public function handle(): void
    {
        try {
            // Reload transaction từ database để lấy trạng thái mới nhất
            $transaction = PaymentTransaction::find($this->paymentTransaction->id);
            
            if (!$transaction) {
                Log::warning('PaymentTransaction not found', [
                    'transaction_id' => $this->paymentTransaction->id
                ]);
                return;
            }

            // Nếu vẫn còn pending sau 3 phút, đánh dấu là expired
            if ($transaction->status === 'pending') {
                $transaction->update([
                    'status' => 'expired'
                ]);

                Log::info('Payment transaction expired', [
                    'transaction_id' => $transaction->id,
                    'order_id' => $transaction->order_id,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error checking payment status in job', [
                'transaction_id' => $this->paymentTransaction->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
