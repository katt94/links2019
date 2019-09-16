<?php
/**
 * Admin controller file.
*/

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdminController.
 */
class AdminController extends AbstractController
{
    /**
     * Index action.
     *
     * @return Response
     *
     * @Route("/admin", name="admin_index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * User list action.
     *
     * @param Request            $request
     * @param PaginatorInterface $paginator
     * @param UserRepository     $userRepository
     *
     * @return Response
     *
     * @Route("/admin/users", name="admin_user_list")
     */
    public function userList(Request $request, PaginatorInterface $paginator, UserRepository $userRepository): Response
    {
        /** @var User[] $users */
        $users = $userRepository->findAll();

        return $this->render('admin/user_list.html.twig', [
            'pagination' => $paginator->paginate($users, $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * User add action.
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
     * @Route("/admin/user/new", name="admin_user_new", methods={"GET", "POST"})
     */
    public function newUser(Request $request, UserRepository $repository, UserPasswordEncoderInterface $userPasswordEncoder, TranslatorInterface $translator): Response
    {
        /** @var User $user */
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $repository->save($user);

            $this->addFlash('success', $translator->trans('message.success.user.add'));

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * User edit action.
     *
     * @param Request                      $request             HTTP request
     * @param User                         $user
     * @param UserRepository               $repository          User repository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param TranslatorInterface          $translator
     *
     * @return RedirectResponse|Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route("/admin/user/{id}/edit", name="admin_user_edit", methods={"GET", "PUT"}, requirements={"id": "[1-9]\d*"})
     */
    public function editUser(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $userPasswordEncoder, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            if (null !== $plainPassword) {
                $user->setPassword(
                    $userPasswordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
            }

            $repository->save($user);

            $this->addFlash('success', $translator->trans('message.success.user.edit'));

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * User delete action.
     *
     * @param Request             $request    HTTP request
     * @param User                $user       User entity
     * @param UserRepository      $repository User repository
     * @param TranslatorInterface $translator
     *
     * @return RedirectResponse|Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     *
     * @Route(
     *     "/admin/user/{id}/delete",
     *     name="admin_user_delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"}
     *     )
     */
    public function deleteUser(Request $request, User $user, UserRepository $repository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(UserType::class, $user, ['method' => 'DELETE', 'disabled' => true]);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $userEmail = $user->getEmail();
            $repository->delete($user);
            $this->addFlash('success', $translator->trans('message.success.user.remove', ['%email%' => $userEmail]));

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user_delete.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
