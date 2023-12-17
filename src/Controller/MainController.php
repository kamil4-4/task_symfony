<?php 

namespace App\Controller;

use App\Entity\Dolar;
use App\Entity\Euro;
use App\Form\CurrencyConversionType;
use App\Form\LowestRateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        return $this->render('homepage.html.twig');
    }

    #[Route('/lovest-rate', name: 'lovest_rate')]
    public function lowestRate(Request $request, EntityManagerInterface $em): Response
    {
        $highest = null;
        $lowest = null;
        $form = $this->createForm(LowestRateType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($data['period'] == 'week')
                $limit = 7;
            elseif ($data['period'] == 'month')
                $limit = 30;
            else
                $limit = 90;

            if ($data['currency'] == 'usd')
            {
                $items = $this->em->getRepository(Dolar::class)->findByRate($limit);
                $highest = ($items[count($items) - 1])->getExchangeRate();
                $lowest = ($items[0])->getExchangeRate();
            }
            else 
            {
                $items = $this->em->getRepository(Euro::class)->findByRate($limit);
                $highest = ($items[count($items) - 1])->getExchangeRate();
                $lowest = ($items[0])->getExchangeRate();
            }
        }

        return $this->renderForm('lovest-rate.html.twig', [
            'form' => $form,
            'highest' => $highest,
            'lowest' => $lowest
        ]);
    }

    #[Route('/currency-conversion', name: 'currency_conversion')]
    public function currencyConversion(Request $request): Response
    {
        $sum = null;
        $currency = null;
        $form = $this->createForm(CurrencyConversionType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $sum = $data['sum'];
            $currencyFirst = $this->getCourse($data['currency_first']);
            $currencySecound = $this->getCourse($data['currency_secound']);
            $currency = $data['currency_secound'];

            $sum = ($sum * $currencyFirst) / $currencySecound;
        }

        return $this->renderForm('currency-conversion.html.twig', [
            'form' => $form,
            'sum' => $sum,
            'currency' => $currency
        ]);
    }

    public function getCourse(string $item)
    {
        if ($item == 'usd') 
            return ($this->em->getRepository(Dolar::class)->findBy([], ['id' => 'DESC'], 1, 0)[0])->getExchangeRate();
        if ($item == 'eur') 
            return ($this->em->getRepository(Dolar::class)->findBy([]. ['id' => 'DESC'], 1, 0)[0])->getExchangeRate();
        else
            return 1;
    }
}