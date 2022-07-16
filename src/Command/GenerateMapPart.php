<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\TileFactory;
use BlackScorp\SimplexNoise\Noise2D;
use League\Flysystem\FilesystemOperator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


final class GenerateMapPart extends Command
{
    protected static $defaultName = 'ot:generate-map:part';
    private const MAP_DATA_FILE_NAME = 'map-part-%d-%d.txt';

    public function __construct(
        private readonly string $mapDirectory,
        private TileFactory $tileFactory,
        private Noise2D $noise2D,
        string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->addOption('top', 'T', InputOption::VALUE_OPTIONAL, 'Set the first top position', 0);
        $this->addOption('left', 'L', InputOption::VALUE_OPTIONAL, 'Set the first left position', 0);
        $this->addOption('width', 'W', InputOption::VALUE_REQUIRED, 'Set the width of the part');
        $this->addOption('height', 'H', InputOption::VALUE_REQUIRED, 'Set the height of the part');

        $this->setHelp('Generates a map part');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $width = $input->getOption('width');
        $height = $input->getOption('height');

        if ($height === null && $width === null) {
            throw new \InvalidArgumentException('Map width or height is missing');
        }

        $top = (int)$input->getOption('top');
        $left = (int)$input->getOption('left');

        $start = microtime(true);
        $maxSteps = $height * $width;

        $progressBar = new ProgressBar($output, $maxSteps);


        $fileNameX = (int)floor($left / $width);
        $fileNameY = (int)floor($top / $height);
        $mapFilePath = $this->mapDirectory . '/' . sprintf(self::MAP_DATA_FILE_NAME, $fileNameX, $fileNameY);
        file_put_contents($mapFilePath,'');
        $mapDataStream = fopen($mapFilePath, 'wb');


        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $locationX = $x + $left;
                $locationY = $y + $top;
                $greyValue = $this->noise2D->getGreyValue($locationX, $locationY);
                $tile = $this->tileFactory->findTilesByGreyValue($greyValue);
                $data = (int)$tile->getData();
                fwrite($mapDataStream, decbin($data));
                $progressBar->advance();
            }
        }
        fclose($mapDataStream);

        $progressBar->finish();
        $finish = microtime(true) - $start;

        $output->writeln("");
        $output->writeln("Filesize: " . filesize($mapFilePath));
        $output->writeln(sprintf("Generation Complete in %f S ",$finish));
        return Command::SUCCESS;
    }
}