<?php

namespace App\Controller;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
    public function productos(HttpClientInterface $product, Request $request): Response
    {
        $products = $this->fetchFakeApiInformation($product, $request);

        return $this->render('productos.html.twig', [
            'products' => $products,
        ]);
    }

    public function fetchFakeApiInformation(HttpClientInterface $product, Request $request): array
    {
        $quantity = $request->query->getInt('quantity', 30);
        $minPrice = $request->query->getInt('min_price', 0);
        $maxPrice = $request->query->getInt('max_price', 999999);
        $taxes = $request->query->getInt('taxes', 12);

        $url = sprintf('https://fakerapi.it/api/v1/products?_quantity=%d&_taxes=%d&_categories_type=uuid&_price_min=%d&_price_max=%d', $quantity, $taxes, $minPrice, $maxPrice);

        $response = $product->request('GET', $url);

        $products = $response->toArray();

        return $products;
    }

 
/**
 * @Route("/clientes", name="clientes")
 */
public function clientes(HttpClientInterface $httpClient, Request $request): Response
{
    $clientes = $this->fetchFakeApiInformationClientes($httpClient, $request);

    return $this->render('clientes.html.twig', [
        'clientes' => $clientes,
    ]);
}

public function fetchFakeApiInformationClientes(HttpClientInterface $httpClient, Request $request): array
{
    $quantity = $request->query->getInt('quantity');
    $gender = $request->query->get('gender', 'all');
    $minBirthdate = $request->query->get('min_birthdate', '1950-01-01');
    $maxBirthdate = $request->query->get('max_birthdate', '2000-12-31');

    $url = sprintf('https://fakerapi.it/api/v1/persons?_quantity=%d&gender=%s&birthdate_min=%s&birthdate_max=%s', $quantity, $gender, $minBirthdate, $maxBirthdate);

    $response = $httpClient->request('GET', $url);

    $clientes = $response->toArray();

    return $clientes;
}

}
