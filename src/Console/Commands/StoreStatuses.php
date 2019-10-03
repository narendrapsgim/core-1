<?php

namespace OpenDialogAi\Core\Console\Commands;

use Illuminate\Console\Command;
use OpenDialogAi\ConversationBuilder\Conversation;

class StoreStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statuses:store';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stores old status names in a CSV file, to be read after migrations.';

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
        $this->info("Storing statuses");

        // Get IDs & statuses
        $conversations = Conversation::all();

        $data = $conversations->map(function (Conversation $conversation) {
            return [
                'id' => $conversation->id,
                'status' => $conversation->status
            ];
        })->toArray();

        if (is_null($data)) {
            $this->error("There were no conversations to store statuses from.");
            return;
        }

        if (!is_dir(storage_path('statuses'))) {
            mkdir(storage_path('statuses'));
        }

        // Store in CSV file
        $file = fopen(storage_path('statuses') . '/statuses_' . date('Y-m-d-H-i-s') . '.csv', 'w+');

        foreach ($data as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    }
}
