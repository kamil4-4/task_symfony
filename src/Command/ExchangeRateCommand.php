<?php 

namespace App\Command;

use App\Entity\Dolar;
use App\Entity\Euro;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(name: 'app:exchange-rate')]
class ExchangeRateCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client, 
        private EntityManagerInterface $em
    )
    {
        $this->client = $client;
        $this->em = $em;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $responseEur = $this->client->request(
            'GET',
            'http://api.nbp.pl/api/exchangerates/rates/a/eur/2023-01-01/2023-12-15/'
        );

        $contentEur = json_decode($responseEur->getContent(), true);
        $rateEur = $contentEur['rates'];

    
        $iEur = 0;
        foreach ($rateEur as $itemEur)
        {
            $eur = (new Euro)
                ->setExchangeRate($itemEur['mid'])
                ->setDate(new DateTime($itemEur['effectiveDate']))
            ;
            $this->em->persist($eur);

            $iEur++;
            if ($iEur == 100 || $iEur == count($rateEur))
                $this->em->flush();
        }
        $output->writeln('Added '.$iEur.' records in the Euro table');

        $responseUsd = $this->client->request(
            'GET',
            'http://api.nbp.pl/api/exchangerates/rates/a/usd/2023-01-01/2023-12-15/'
        );

        $contentUsd = json_decode($responseUsd->getContent(), true);
        $rateUsd = $contentUsd['rates'];

    
        $iUsd = 0;
        foreach ($rateUsd as $itemUsd)
        {
            $usd = (new Dolar)
                ->setExchangeRate($itemUsd['mid'])
                ->setDate(new DateTime($itemUsd['effectiveDate']))
            ;
            $this->em->persist($usd);

            $iUsd++;
            if ($iUsd == 100 || $iUsd == count($rateUsd))
                $this->em->flush();
        }
        $output->writeln('Added '.$iUsd.' records in the Dolar table');

        return Command::SUCCESS;
    }
}