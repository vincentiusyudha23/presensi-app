<?php

namespace App\Http\Controllers;

use Symfony\Component\Process\Process;

class PullhookController extends Controller
{
    public function pull()
    {
        $process = new Process(['git', 'pull']);
        $process->run();
        echo ($process->getOutput());
    }
}
