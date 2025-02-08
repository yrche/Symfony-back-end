<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'readme:show', description: 'Displays the content of the README.md file.')]
class ReadMeCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Получаем путь к файлу README.md
        $readmePath = $this->getApplication()->getKernel()->getProjectDir() . '/README.md';

        // Проверяем, существует ли файл
        if (!file_exists($readmePath)) {
            $output->writeln('<error>Error: README.md file not found at ' . $readmePath . '!</error>');
            return Command::FAILURE;
        }

        // Читаем содержимое файла
        $readmeContent = file_get_contents($readmePath);

        // Выводим содержимое
        $output->writeln('<info>Content of README.md:</info>');
        $output->writeln($readmeContent);

        return Command::SUCCESS;
    }
}
