<?php

namespace AppBundle\Command;

use Pimcore\Console\AbstractCommand;
use Pimcore\Console\Dumper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
Use Pimcore\Db;

class UserRoleFixCommand extends AbstractCommand
{
    protected function configure()
    {
        $this
            ->setName('userrole:fix')
            ->setDescription('Updating & saving user roles');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // DB connection
            $db = Db::getConnection();
            $sql = 'SELECT id FROM users WHERE roles LIKE "%106%" OR roles LIKE "%123%"';
            $userIds = $db->fetchAll($sql);

            $this->output->writeln('<info> Updating & saving users roles ... </info>');
            $this->output->writeln('<info> Please wait ... </info>');

            $userIds = array_map(function($i){ return $userIds[] = $i['id']; }, $userIds);

            foreach ($userIds as $id) {
                $user = \Pimcore\Model\User::getById($id);
                $getRolesArr = $user->getRoles();
                $setRolesArr = array_diff($getRolesArr, ['106', '123']);
                $user->setRoles($setRolesArr);
                $user->save();
            }

            // Delete Jonas Ledendal from users
            $sql = 'SELECT id FROM users WHERE name="bulw-jle"';
            $jonasId = $db->fetchAll($sql);
            if ($jonasId) {
                $jonas = \Pimcore\Model\User::getByName('bulw-jle');
                $jonas->delete();
            }

            $this->output->writeln('<info> Successfully updated & saved users roles </info>');

        } catch (\Exception $e) {
            $this->output->writeln(sprintf('<error> %s </error>', $e->getMessage()));
        }        
    }
}