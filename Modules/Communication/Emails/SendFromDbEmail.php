<?php

namespace Modules\Communication\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Communication\Models\Email;

class SendFromDbEmail extends Mailable // implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public $email
    ) {
    }

    public function build()
    {
        $this->subject($this->email['subject'])
            ->markdown('communication::emails.mailfromdb')
            ->with([
                'mail_body' => $this->email['mail_body'],
            ]);

        return $this;
    }
}
