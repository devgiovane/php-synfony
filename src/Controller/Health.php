<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
/**
 * Class Health
 * @package App\Controller
 *
 * @Route("/health")
 */
final class Health extends AbstractController
{
    /**
     * @Route("/", name="health", methods={"GET", "OPTIONS"})
     */
    public function index(): Response
    {
        return new Response('', Response::HTTP_OK);
    }
}
