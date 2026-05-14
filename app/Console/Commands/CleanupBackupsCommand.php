<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanupBackupsCommand extends Command
{
    protected $signature =
        'backup:cleanup';

    protected $description =
        'Delete old database backups';

    public function handle()
    {
        $path =
            storage_path('app/backups');

        if (!File::exists($path)) {

            $this->warn(
                'Backup folder does not exist.'
            );

            return;
        }

        $files = File::files($path);

        foreach ($files as $file) {

            $lastModified =
                Carbon::createFromTimestamp(
                    $file->getMTime()
                );

            if (
                now()->diffInDays(
                    $lastModified
                ) > 7
            ) {

                File::delete(
                    $file->getPathname()
                );
            }
        }

        $this->info(
            'Old backups deleted successfully.'
        );
    }
}