<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{SendProductDaily, Product, Customer};
use App\Jobs\DailyProduct;

class SendProductDailyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send product daily';

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
        $SendProductDaily = SendProductDaily::get();
        foreach ($SendProductDaily as $sendProduct){
            $product = Product::find($sendProduct->product_id);
            $this->sendToCustomer($product);
        }
    }

    private function sendToCustomer($product)
    {
        $customers = Customer::get();
        foreach($customers as $customer){
            DailyProduct::dispatch($customer, $product);
        }
    }
}
