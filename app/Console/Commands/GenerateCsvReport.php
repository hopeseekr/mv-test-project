<?php

namespace App\Console\Commands;

use App\CompaniesInvestments;
use App\Company;
use App\Mail\InvestmentsCSVReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Validator;

class GenerateCsvReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:investments-csv {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and emails a CSV report of the companies and their total investments.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    protected function packageIntoCSV(array $companiesInvestments): string
    {
        // Open up a temporary memory file for the CSV.
        $fh = fopen('php://memory', 'w+');

        // Output the columns to CSV:
        fputcsv($fh, array_keys($companiesInvestments[0]));

        // Output it to CSV.
        foreach ($companiesInvestments as $companyInvestments) {
            fputcsv($fh, $companyInvestments);
        }

        // Read it back.
        rewind($fh);
        $csv = stream_get_contents($fh);

        // Don't forget to close, or you'll run out of file handlers eventually and
        // have a memleak.
        fclose($fh);

        return $csv;
    }

    protected function emailCSV($email, $csv)
    {
        Mail::to($email)
            ->send(new InvestmentsCSVReport($csv));
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email');
        Validator::make(['email' => $email], ['email:required|email']);

        // Let's demo the 'performant' way.
        $companiesInvestments = CompaniesInvestments::all()->sortBy('id')
            ->toArray();
        if (empty($companiesInvestments)) {
            $this->line('No investment info is available.');
            exit;
        }

        $csv = $this->packageIntoCSV($companiesInvestments);

        $this->emailCSV($email, $csv);
    }
}
