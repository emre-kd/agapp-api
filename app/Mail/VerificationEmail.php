<?php
// app/Mail/VerificationEmail.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;

    public function __construct($verificationCode)
    {
        $this->verificationCode = $verificationCode;
    }

    public function build()
    {
        return $this->subject('E-Posta Doğrulama Kodu')
                    ->view('emails.verification') // Make sure this matches your template file
                    ->with([
                        'code' => $this->verificationCode
                    ]);
    }
}
