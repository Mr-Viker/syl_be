<?php

namespace App\Jobs;

use App\Models\Order as OrderModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class Order implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;

    /**
     * 任务可以尝试的最大次数。
     *
     * @var int
     */
    public $tries = 3;

    /**
     * 超时时间。
     *
     * @var int
     */
    public $timeout = 30;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('================== 触发检测待支付订单Job: '.$this->order->id.' ====================');
        if ($this->order->status == OrderModel::ORDER_WAIT_PAY) {
            try {
                $this->order->cancel();
            } catch(\Exception $e) {
                Log::error("检测待支付订单时出现错误: ".$e->getMessage());
            }
            Log::info('取消待支付订单: '.json_encode($this->order));
        } else {
            Log::info('没有待支付订单哦~');
        }
        Log::info('================== 结束检测待支付订单Job ====================');
    }
}
