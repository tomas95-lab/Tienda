<?php

namespace App\Controller;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class mainController extends AbstractController
{
    /**
     * @Route("/tienda2023")
     */
    public function saludo_tienda_2023():Response
    {
        return $this->render('base.html.twig');
    }

    /**
     * @Route("/")
     */
    public function main(): Response
    {
        return new Response("esta es la raiz del proyecto");
    }
   
    /**
     * @Route("/productos", name="productos")
     */
    public function productos(HttpClientInterface $client): Response
    {
        $content = $this->fetchFakeApiInformation($client);

        return $this->render('productos.html.twig', [
            'content' => $content,
        ]);
    }

    public function fetchFakeApiInformation(HttpClientInterface $client): array
    {
        $response = $client->request(
            'GET',
            'https://fakerapi.it/api/v1/products?_quantity=10&_taxes=12&_categories_type=uuid'
        );

        $content = $response->toArray();

        return $content;
    }
}