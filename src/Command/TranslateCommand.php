<?php

namespace App\Command;

use App\Factory\TranslatorFactory;
use App\Service\FileProcessor;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TranslateCommand extends Command
{
    protected static string $defaultName = 'app:translate';
    private TranslatorFactory $translatorFactory;
    private FileProcessor $fileProcessor;

    public function __construct(TranslatorFactory $translatorFactory, FileProcessor $fileProcessor)
    {
        $this->translatorFactory = $translatorFactory;
        $this->fileProcessor = $fileProcessor;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Translate strings from a file.')
            ->addArgument('inputFile', InputArgument::REQUIRED, 'The file to read strings from.')
            ->addArgument('outputFile', InputArgument::REQUIRED, 'The file to write translated strings to.')
            ->addOption('translator', 't', InputOption::VALUE_REQUIRED, 'The translator service to use.')
            ->addOption('targetLang', 'l', InputOption::VALUE_REQUIRED, 'The target language for translation.', 'en');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputFile = $input->getArgument('inputFile');
        $outputFile = $input->getArgument('outputFile');
        $translatorName = $input->getOption('translator');
        $targetLang = $input->getOption('targetLang');

        if (!file_exists($inputFile)) {
            $output->writeln('<error>Input file does not exist.</error>');

            return Command::FAILURE;
        }

        $translator = $this->translatorFactory->create($translatorName);

        if (!$translator) {
            $output->writeln('<error>Translator service not found.</error>');
            return Command::FAILURE;
        }

        try {
            $this->fileProcessor->process($inputFile, $outputFile, function ($line) use ($translator, $targetLang) {
                return $translator->translate($line, $targetLang);
            });

            $output->writeln('<info>Translation completed successfully.</info>');
        } catch (\RuntimeException $e) {
            $output->writeln('<error>An error occurred: '.$e->getMessage().'</error>');
            
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}