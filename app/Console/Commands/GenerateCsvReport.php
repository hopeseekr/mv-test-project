<?php

namespace App\Console\Commands;

use App\CompaniesInvestments;
use App\Mail\InvestmentsCSVReport;
use CSVWriter\LeagueCSVWriter;
use CSVWriter\NativeCSVWriter;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Validator;

class GenerateCsvReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:investments-csv {email} {--use-league}';

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

    protected function packageIntoCSV(Collection $companiesInvestments): string
    {
        /*
         * The exercise calls for me to "discuss the pros and cons of using league/csv."
         *
         * Well, to be honest, I knew about the package for about a year from /r/PHP.
         * But, I've been doing it via fopen("php://memory") for -ever-.
         *
         * League/CSV Pros:
         *  1. It's much much easier for non-pros to safely implement CSV construction.
         *  2. It has 100% test code coverage out of the box.
         *  3. It would arguably save a company roughly $50-100 to use it out-of-the-box.
         *  4. They use php://temp. While this uses php://memory for the first 2MB, if it
         *     goes over, it reverts to /tmp files. This can have SEVERE privacy and
         *     confidentiality risks!! For instance, I assume that company investments
         *     are extremely *proprietary* information, yet if a hacker can massage the
         *     report to be over 2MB, then any user in the entire system, including "nobody",
         *     can gain access to the report in the /tmp. That's why I always use php://memory.
         *
         * Cons:
         *  1. Limits the teaching potential to juniors/medium-skilled of some advanced
         *     concepts, notably PHP streams, php://memory vs php://temp, fputcsv, etc.
         *     These concepts can save a company literally thousands of dollars in misplaced
         *     man hours due to NIH syndrome.
         *  2. One More Dependency.
         *  3. You have to trust that league/csv does things optimally, or spend more time
         *     figuring it out (reduces Pro #3 by ~50%).
         *  4. Even though I implemented it in a complete white-room environment, my implementation
         *     is fundamentally identical to their's with the exception that they chose php://temp
         *     instead.
         */

        // @todo: A more appropriate place would be in the package's ServiceProvider.
        //        But that would interfere with the contrived demo.
        if ($this->option('use-league')) {
            $csvWriter = new LeagueCSVWriter();
        } else {
            $csvWriter = new NativeCSVWriter();
        }

        return $csvWriter->collectionToCsv($companiesInvestments);
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
        $companiesInvestments = CompaniesInvestments::all()->sortBy('id');
        if (empty($companiesInvestments)) {
            $this->line('No investment info is available.');
            exit;
        }

        $csv = $this->packageIntoCSV($companiesInvestments);

        $this->emailCSV($email, $csv);
    }
}
