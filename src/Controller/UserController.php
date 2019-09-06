<?php
/**
 * User controller file.
*/

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
class UserController extends AbstractController
{
    /**
     * Index action.
     *
     * @Route(
     *     "/user",
     *     name="user_index"
     * )
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * User edit action.
     *
     * @param Request                      $request             HTTP request
     * @param UserRepository               $repository          User repository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param TranslatorInterface          $translator
     *
     * @return RedirectResponse|Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/user/change-password",
     *     name="user_change_password",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"}
     *     )
     * @IsGranted("ROLE_USER")
     */
    public function changePassword(
        Request $request,
        UserRepository $repository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        TranslatorInterface $translator
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('oldPassword')->getData();
            $plainPassword = $form->get('plainPassword')->getData();

            if ($userPasswordEncoder->isPasswordValid($user, $oldPassword)) {
                $user->setPassword(
                    $userPasswordEncoder->encodePassword(
                        $user,
                        $plainPassword
                    )
                );

                $repository->save($user);

                $this->addFlash('success', $translator->trans('message.success.user.change_password'));

                return $this->redirectToRoute('user_index');
            }

            $this->addFlash('danger', $translator->trans('message.danger.user.incorrect_password'));
        }

        return $this->render('user/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
