<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\MailNewProduct;
use Exception;

class DailyProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $product;
    protected $customer;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($customer, $product)
    {
        $this->product = $product;
        $this->customer = $customer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            Mail::to($this->customer->email)
                ->send(new MailNewProduct("Produto do dia", $this->product->name, $this->product->description));
        } catch(Exception $e){
            Log::info("[DailyProduct]mail: {$e->getMessage()}");
        }
    }
}
