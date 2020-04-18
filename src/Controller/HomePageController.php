<?php

/** 
 * The file is for home page
 * 
 * PHP version 7.3.5
 * 
 * @category Controller
 * @package  Controller
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tricks;
use Symfony\Component\HttpFoundation\Response;

/** 
 * The class is for home page
 * 
 * @category Controller
 * @package  Controller
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class HomePageController extends AbstractController
{
    /**
     * Render home page
     * 
     * @Route("/", name="home_page")
     * 
     * @return response
     */
    public function index(): Response
    {
        $tricks = $this->getDoctrine()
            ->getRepository(Tricks::class)
            ->tricksPagination(0, 4);

        return $this->render(
            'home_page/index.html.twig',
            [
                'tricks' => $tricks
            ]
        );
    }
}
