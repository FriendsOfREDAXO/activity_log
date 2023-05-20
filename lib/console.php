<?php

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class activity_clear extends rex_console_command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('activity:clear');
    }

    /**
     * @throws rex_exception
     * @throws rex_api_exception
     * @throws rex_sql_exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $daysQuestion = new Question('Delete logs older than n days (default: -1 -> delete all): ', -1);
        $daysQuestion->setValidator(static function ($answer) {
            if (!is_numeric($answer) || '' === $answer) {
                throw new \RuntimeException('You must enter a number.');
            }

            return $answer;
        });

        $days = (int) $helper->ask($input, $output, $daysQuestion);
        $table = \rex::getTable('activity_log');
        $deleted = 0;

        if ($days < 0) {
            $sql = \rex_sql::factory();
            $sql->setTable($table);
            $sql->delete();
            $deleted = $sql->getRows();
        } elseif ($days > 0) {
            $now = (new \DateTime());
            $now->modify("-$days day");
            $date = $now->format('Y-m-d H:i:s');

            $sql = \rex_sql::factory();
            $sql->setTable($table);
            $sql->setWhere("created_at <= '$date'");
            $sql->delete();
            $deleted = $sql->getRows();
        }

        $output->writeln("Deleted $deleted log(s).");

        return Command::SUCCESS;
    }
}
