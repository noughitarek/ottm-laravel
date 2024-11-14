<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TransferDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transfer-data-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ignore = ['remarketing_messages', 'remarketing_interval_messages', 'cache', 'cache_locks', 'dashboard_response_times', 'password_reset_tokens'];
        $chunkSize = 100;

        // Connect to the source and target databases
        $old_mysql = DB::connection('old_mysql');
        $new_mysql = DB::connection('mysql');

        // Get a list of all tables in the source database
        $tables = $old_mysql->select('SHOW TABLES');
        $tableKey = 'Tables_in_' . env('OLD_DB_DATABASE');

        foreach ($tables as $table) {
            $tableName = reset($table); // Get the table name

            // Skip specific tables if needed
            if (in_array($tableName, $ignore)) {
                continue;
            }


            $this->info("Transferring data for table: {$tableName}");

            // Check if the table exists in the target database, create if not
            if (!$new_mysql->getSchemaBuilder()->hasTable($tableName)) {
                $this->info("Creating table {$tableName} in target database.");
                Schema::connection('mysql')->create($tableName, function ($tableSchema) use ($tableName, $old_mysql) {
                    $columns = $old_mysql->getSchemaBuilder()->getColumnListing($tableName);
                    foreach ($columns as $column) {
                        $columnType = $old_mysql->getDoctrineColumn($tableName, $column)->getType()->getName();
                        $tableSchema->addColumn($columnType, $column);
                    }
                });
            }

            // Transfer data in chunks
            $old_mysql->table($tableName)->orderBy('id')->chunk($chunkSize, function ($rows) use ($new_mysql, $tableName, $chunkSize) {
                $data = [];
                foreach ($rows as $row) {
                    $data[] = (array) $row; // Convert each row object to an array
                }

                // Insert data into the target table, ignoring duplicates
                if (!empty($data)) {
                    $new_mysql->table($tableName)->insertOrIgnore($data);
                    $this->info("Transferred chunk of {$chunkSize} rows for table {$tableName}.");
                }
            });

            $this->info("Completed data transfer for table: {$tableName}.");
        }

        $this->info("All tables transferred successfully.");
    }
}
