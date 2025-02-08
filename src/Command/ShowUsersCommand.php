<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:show-users', description: 'Displays a list of all users.')]
class ShowUsersCommand extends Command
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Получаем всех пользователей из базы данных
        $users = $this->userRepository->findAll();

        if (empty($users)) {
            $io->warning('No users found in the database.');
            return Command::SUCCESS;
        }

        // Формируем таблицу для вывода
        $tableRows = [];
        foreach ($users as $user) {
            $tableRows[] = [
                (string) $user->getId(), // ID пользователя
                $user->getEmail() ?? 'N/A', // Email пользователя (если null, выводим 'N/A')
                implode(', ', $user->getRoles()), // Роли пользователя (преобразуем массив в строку)
            ];
        }

        // Выводим таблицу
        $io->table(
            ['ID', 'Email', 'Roles'], // Заголовки столбцов
            $tableRows                // Данные таблицы
        );

        return Command::SUCCESS;
    }
}