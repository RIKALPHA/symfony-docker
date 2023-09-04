<?php

namespace App\Command;

use App\Service\FileData;
use App\Service\FileDownloader;
use App\Service\FileProcessor;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'importPStars',
    description: 'Imports the people',
)]
class ImportPStarsCommand extends Command
{
    private const BULK_SIZE = 100;

    public function __construct(
        private FileDownloader $downloader,
        private FileProcessor $processor,
        private FileData $fileData,
    ){
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->info('Starting download');

        try {
            $this->downloader->checkAndDownload();
        } catch (\Exception $e) {
            $io->error($e);
            return Command::FAILURE;
        }

        $io->info('Processing FILE');

        $memory_limit = ini_get('memory_limit');
        $io->warning('memory limit:' . $memory_limit);
        ini_set('memory_limit', '1024M');

        try {
            $counter = $this->processor->getDataNumbers();
            $io->info('Number of Entities:' . $counter);

            $progressBar = new ProgressBar($output, $counter);
            $progressBar->setOverwrite(true);

            $handle = fopen($this->fileData->getFileName(), "r");
            if ($handle) {
                $bulkCount = 0;
                while (($line = fgets($handle)) !== false) {
                    $line = json_decode(trim($line, " \n\r\t\v\x00,"), true);
                    if (is_array($line) && array_key_exists('id', $line)) {
                        $this->processor->handleOne($line);
                        $progressBar->advance();
                        $bulkCount++;
                        if ($bulkCount >= self::BULK_SIZE) {
                            $this->processor->flushChunk();
                            gc_collect_cycles();
                            $bulkCount = 0;
                        }
                    }
                    unset($line);
                }
                $this->processor->flushChunk();
                fclose($handle);
            }
            $progressBar->finish();
        } catch (\Exception $e) {
            $io->error($e);
            return Command::FAILURE;
        }

        $io->success('Processing done.');
        return Command::SUCCESS;
    }
}
