<?php

namespace App\Mail\AbstractSubmission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ThankYou extends Mailable
{
    use Queueable, SerializesModels;
    
    public $user;
    public $abstract_submission;
    public $submission_date;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    

    public function __construct($user, $abstract_submission)
    {
        $this->user = $user;
        $this->abstract_submission = $abstract_submission;
        $this->submission_date = date("F j, Y", strtotime($abstract_submission->created_at));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.abstract-submission.thankyou')
            ->subject("RCD 2022 Abstract Submission Receipt - ".$this->submission_date)
            ->with(array('user' => $this->user, 'abstract_submission' => $this->abstract_submission, 'submission_date' => $this->submission_date));
    }
}