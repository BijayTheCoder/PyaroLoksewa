<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ComposerUpdateCommand extends Command
{
    protected $signature = 'composer:update';
    protected $description = 'Update composer dependencies';

    public function handle()
    {
        // Execute composer update command
        $this->info('Running composer update...');

        $output = shell_exec('composer update');

        if ($output === null) {
            $this->error('Error occurred while running composer update.');
        } else {
            $this->info($output);
            $this->info('Composer update completed.');
        }
    }
}
