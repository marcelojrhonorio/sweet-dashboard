<?php

namespace App\Console\Commands;

use DB;
use App\Models\Customer;
use Illuminate\Console\Command;

class ExportCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export-customers:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send export to the e-mail address';

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
     * @return mixed
     */
    public function handle()
    {
        $file = 'CUSTOMERS.csv'; 
        $directory = '/var/lib/mysql-files/';
        
        $this->info('Generating CSV file...');

        $customers = DB::select("
            SELECT *
            FROM customers_export
            INTO OUTFILE '".$directory.$file."'
            FIELDS ENCLOSED BY '\"'
            TERMINATED BY ';'
            ESCAPED BY '\"'
            LINES TERMINATED BY '\r\n'
        ");
        
        $this->info('Sending file to e-mail...');        

        exec('~/customers-export/run.sh');

        $this->info('Done!');
    }
}
