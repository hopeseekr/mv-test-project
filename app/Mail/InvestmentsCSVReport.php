<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvestmentsCSVReport extends Mailable
{
    use Queueable, SerializesModels;

    /** @var string The raw CSV contents for the report. */
    private $csv;

    /**
     * Create a new message instance.
     *
     * @param string $csv
     * @return void
     */
    public function __construct(string $csv)
    {
        $this->csv = $csv;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Avoid next-day rollovers due to UTC offset.
        $today = (new Carbon())->setTimezone('America/Chicago');
        $reportDate = $today->format('Y-m-d');
        $fileDate = $today->format('Ymd');

        return $this->from('no-reply@microventures.com')
            ->subject('Investments Report: ' . $reportDate)
            ->attachData($this->csv, "investments-{$fileDate}.csv")
            ->view('emails.investments_report', compact('reportDate'));
    }
}
