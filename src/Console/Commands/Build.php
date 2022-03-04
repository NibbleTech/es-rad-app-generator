<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\Console\Commands;

use NibbleTech\EsRadAppGenerator\AppBuilder;
use NibbleTech\EsRadAppGenerator\Configuration\XmlProviders\NativeXML;
use NibbleTech\EsRadAppGenerator\InstructionProviders\InstructionProvider;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @psalm-suppress UnusedClass
 */
class Build extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'build';

    protected function configure(): void
    {
        // ...
        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'Path to configuration file'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rootPath = (string) $input->getArgument('path');

        $xmlConfigPath = $input->hasArgument('config_path')
            ? (string) $input->getArgument('config_path')
            : $rootPath . '/esradapp.xml';

        if (file_exists($xmlConfigPath) === false) {
            throw new InvalidArgumentException("Config file does not exist at " . $xmlConfigPath);
        }

        $output->writeln('Path is ' . realpath($rootPath));

        $buildPath = $input->hasArgument('build_path')
            ? (string) $input->getArgument('build_path')
            : $rootPath . '/esradapp_output';

        if (is_dir($buildPath) === false) {
            if (mkdir($buildPath) === false) {
                throw new RuntimeException("Failed to create missing build directory at " . $buildPath);
            }
        }

        $output->writeln("Reading config from " . realpath($xmlConfigPath));
        $output->writeln("Building in " . realpath($buildPath));

        $appBuilder = new AppBuilder(
            $buildPath,
            new InstructionProvider(
                new NativeXML($xmlConfigPath)
            )
        );

        $appBuilder->build();

        $output->writeln("Done!");

        // ... put here the code to create the user

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}
