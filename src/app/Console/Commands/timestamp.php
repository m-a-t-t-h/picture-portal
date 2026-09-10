<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:timestamp')]
#[Description('Command description')]
class timestamp extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        \Log::debug("Timestamp: " . date("H:i:s"));
    }
}
