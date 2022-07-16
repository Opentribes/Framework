<?php

declare(strict_types=1);

namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class GenerateMap extends Command
{
    protected static $defaultName = 'ot:generate-map';
    private const MAP_DATA_FILE_NAME = 'map.txt';
    private const MAP_PART_DATA_FILE_NAME = 'map-part-%d-%d.txt';

    public function __construct(
        private readonly string $mapDirectory,
        string $name = null
    ) {
        parent::__construct($name);
    }


    protected function configure()
    {
        $this->addOption('width', 'W', InputOption::VALUE_REQUIRED, 'Set the width of the map');
        $this->addOption('height', 'H', InputOption::VALUE_REQUIRED, 'Set the height of the map');

        $this->setHelp('Generates full map');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $width = $input->getOption('width');
        $height = $input->getOption('height');

        if ($height === null && $width === null) {
            throw new \InvalidArgumentException('Map width or height is missing');
        }
        $partWidth = $width / 2;
        $partHeight = $height / 2;
        $totalY = floor($height / $partHeight);
        $totalX = floor($width / $partWidth);
        $output->writeln("Generate full Map");
        /** @var Process $processList */
        $processList = [];
        for ($y = 0; $y < $totalY; $y++) {
            for ($x = 0; $x < $totalX; $x++) {
                $output->writeln(sprintf("Start generating subprocess for %d/%d", $y, $x));
                $process = new Process(
                    [
                        "/usr/bin/php",
                        "bin/adminconsole",
                        "ot:generate-map:part",
                        "-W" . $partWidth,
                        "-H" . $partHeight,
                        "-T" . $y * $partHeight,
                        "-L" . $x * $partWidth
                    ]
                );

                $processList[$process->getPid()] = $process;
            }
        }

        dump($processList);
        return Command::SUCCESS;
        $output->writeln("Waiting for subprocesses");
        do {
            foreach ($processList as $index => $process) {
                if ($process->isRunning()) {
                    continue;
                }
                unset($processList[$index]);
            }
            $allProcessesAreFinished = count($processList) === 0;
            dump($processList, $allProcessesAreFinished);
        } while ($allProcessesAreFinished);
        $output->writeln("everything is generated");
        return Command::SUCCESS;
        $output->writeln("copy parts into main file");
        $fullMap = $this->mapDirectory . '/' . self::MAP_DATA_FILE_NAME;
        $fullMapResource = fopen($fullMap, "w");
        for ($y = 0; $y < $totalY; $y++) {
            for ($x = 0; $x < $totalX; $x++) {
                $mapPartFileName = $this->mapDirectory . '/' . sprintf(self::MAP_PART_DATA_FILE_NAME, $x, $y);
                $mapPart = fopen($mapPartFileName, "r");
                stream_copy_to_stream($mapPart, $fullMapResource);
                fclose($mapPart);
                unlink($mapPartFileName);
            }
        }
        fclose($fullMapResource);
        $output->writeln("Map generated");
        return Command::SUCCESS;
    }


}