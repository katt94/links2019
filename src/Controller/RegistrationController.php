<?php
/**
 * Registration controller file.
*/

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    /**
     * register action.
     *
     * @Route("/register", name="app_register")
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param UserRepository               $repository
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserRepository $repository): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setFirstName(
                $form->get('firstName')->getData()
            );

            $user->setRoles($user->getRoles());

            $repository->save($user);

            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
