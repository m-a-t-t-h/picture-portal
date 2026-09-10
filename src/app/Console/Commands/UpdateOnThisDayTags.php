<?php namespace App\Console\Commands;

use App\Models\Tags;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

/**
 * Update the tags for OnThisDay
 */
#[Signature('app:update-on-this-day {--month=} {--day=}')]
#[Description('Update the tags for OnThisDay')]
class UpdateOnThisDayTags extends Command
{
    public function handle()
    {
        $day   = $this->option("day");
        $month = $this->option("month");

        try {
            Tags::updateOnThisDayTags($month, $day);

        } catch (Throwable $e) {
            $this->error("\nError: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
        }
    }
}
