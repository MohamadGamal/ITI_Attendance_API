<?php
namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use AppBundle\Entity\User;
use AppBundle\Entity\Absence;

class FillAbscenceCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('app:fillabscence')
        ->setDescription('Fills abscence for all users.')
        ->setHelp('This command is created to be used by a cronjob')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
          $em = $this->getContainer()->get('doctrine')->getManager();
        $users = $em->getRepository('AppBundle:User')
               
                ->createQueryBuilder('u')
                ->where('u.roles not LIKE :role')
                ->setParameter('role', '%'."ADMIN".'%')
                ->getQuery()
                ->getResult();
        foreach ($users as $user) {
            $permission=$em->getRepository('AppBundle:Permission')
               
            ->createQueryBuilder('p')
            ->where('p.accepted = :acc and p.date= :date and p.user = :user')
            ->setParameter('acc', 1)
            ->setParameter('date', (new \DateTime())->format('Y-m-d'))
            ->setParameter('user', $user->getId())
            ->getQuery()
            ->getResult();
            if (!count($permission)) {
                $absence= new Absence();
                $absence->setUser($user);
                $absence->setDate(new \DateTime());
                $em->persist($absence);
                   $em->flush();
                // $output->writeln([
                // count($permission),
                // 'DONE',
                // ]);
            }
        }
        //   $output->writeln([
       
        // '********',
       
        //   ]);
    }
}
