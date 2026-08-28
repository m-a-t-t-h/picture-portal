<?php namespace App\Console\Commands;

use App\Models\TagChain;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

/**
 * Rebuild the tag_chain table from the current hierarchy of tags
 */
#[Signature('app:rebuild-tag-chain')]
#[Description('Rebuild tag_chain table')]
class RebuildTagChain extends Command
{
    public function handle()
    {
        try {
            // Not using the normal $this->info() to suppress the trailing line break
            $this->getOutput()->write("Rebuilding tag chain ...");
            TagChain::rebuild();
            $this->getOutput()->write("\e[3Ddone\n");
        } catch (Throwable $e) {
            $this->error("\nError: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
        }
    }
}
