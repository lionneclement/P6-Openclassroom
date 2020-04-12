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
use Symfony\Component\HttpFoundation\Response;
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
     * @param object $user 
     * @param object $request 
     * @param object $file 
     * 
     * @Route("/auth/profile", name="profile")
     * 
     * @return response
     */
    public function index(UserInterface $user, Request $request, File $file): Response
    {
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'Votre profile à été modifié');
            $profile = $form->getData();
            
            $image =$form['imageName']->getData();
            if ($image) {
                if ($user->getImageName() != 'default-user.png') {
                    $file->removeImage($user->getImageName());
                }
                $imageFileName = $file->uploadImage($image);
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
            'user' => $user,
            ]
        );
    }
}
