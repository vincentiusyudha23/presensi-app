<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
// use Illuminate\Support\Facades\Process;
use Symfony\Component\Process\Process;

class gitpull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gitpull';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $output = [];

        $process = new Process(['git', 'pull']);
        $process->run();
        $output[] = $process->getOutput();
        // $this->info($output);

        return Command::SUCCESS;
    }
}
