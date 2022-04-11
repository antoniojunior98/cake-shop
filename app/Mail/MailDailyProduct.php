<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailDailyProduct extends Mailable
{
    use Queueable, SerializesModels;
    protected $title;
    protected $product;
    protected $description;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $product, $description)
    {
        $this->title = $title;
        $this->product = $product;
        $this->description = $description;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->title)->markdown('emails.daily_product', [
            "title" => $this->title,
            "product" => $this->product,
            "description" => $this->description
        ]);
    }
}
