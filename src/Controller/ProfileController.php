<?php
/** 
 * The file is for profile
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

use App\Form\ProfileType;
use App\Tools\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
/** 
 * The class is for profile
 * 
 * @category Controller
 * @package  Controller
 * @author   Clement <lionneclement@gmail.com>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     http://localhost:8000
 */
class ProfileController extends AbstractController
{
    /**
     * Render profile
     * 
     * @param object $User 
     * @param object $request 
     * @param object $File 
     * 
     * @Route("/auth/profile", name="profile")
     * 
     * @return response
     */
    public function index(UserInterface $User, Request $request, File $File)
    {
        $form = $this->createForm(ProfileType::class, $User);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre profile à étais modifier');
            $profile = $form->getData();
            
            $image =$form['ImageName']->getData();
            if ($image) {
                if ($User->getImageName() != 'default-user.png') {
                    $File->removeImage($User->getImageName());
                }
                $imageFileName = $File->uploadImage($image);
                $profile->setImageName($imageFileName);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }
        return $this->render(
            'profile/index.html.twig', [
            'form' => $form->createView(),
            'user' => $User,
            ]
        );
    }
}
