<?php
/**
 * Link controller file.
 */

namespace App\Controller;

use App\Entity\Counter;
use App\Entity\Link;
use App\Entity\User;
use App\Form\LinkType;
use App\Repository\CounterRepository;
use App\Repository\LinkRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class LinkController.
 */
class LinkController extends AbstractController
{
    /**
     * User link list action.
     *
     * @Route("/link-list", name="link_list")
     *
     * @IsGranted("ROLE_LINK_EDITOR")
     *
     * @param Request            $request
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        /** @var Link[] $links */
        $links = $user->getLinks();

        return $this->render('link/list.html.twig', [
            'user' => $user,
            'pagination' => $paginator->paginate($links, $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * User link add action.
     *
     * @Route(
     *     "/link-add",
     *     name="link_add",
     *     methods={"GET", "POST"}
     * )
     *
     * @IsGranted("ROLE_LINK_EDITOR")
     *
     * @param Request             $request
     * @param LinkRepository      $linkRepository
     * @param TranslatorInterface $translator
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Request $request, LinkRepository $linkRepository, TranslatorInterface $translator): Response
    {
        /** @var Link $link */
        $link = new Link();

        $form = $this->createForm(LinkType::class, $link, [
            'inherit_data' => false,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = base_convert(md5($link->getUrl().strtotime(date('c'))), 7, 16);
            $link->setHash($hash);
            $link->setUser($this->getUser());

            $linkRepository->save($link);

            $this->addFlash('success', $translator->trans('message.success.link.add'));

            return $this->redirectToRoute('link_list');
        }

        return $this->render('link/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * User link edit action.
     *
     * @Route(
     *     "/link-edit,{id}",
     *     name="link_edit",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "PUT"}
     * )
     *
     * @IsGranted("ROLE_LINK_EDITOR")
     *
     * @param Request             $request
     * @param Link                $link
     * @param LinkRepository      $linkRepository
     * @param TranslatorInterface $translator
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function edit(Request $request, Link $link, LinkRepository $linkRepository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(LinkType::class, $link, ['method' => 'PUT', 'inherit_data' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($link->getUrl() !== $form->get('url')) {
                $hash = base_convert(md5($link->getUrl().strtotime(date('c'))), 7, 16);
                $link->setHash($hash);
            }

            $linkRepository->save($link);

            $this->addFlash('success', $translator->trans('message.success.link.edit'));

            return $this->redirectToRoute('link_list');
        }

        return $this->render('link/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * User link remove action.
     *
     * @Route(
     *     "/link-remove,{id}",
     *     name="link_remove",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "DELETE"}
     * )
     *
     * @IsGranted("ROLE_LINK_EDITOR")
     *
     * @param Request             $request
     * @param Link                $link
     * @param LinkRepository      $linkRepository
     * @param TranslatorInterface $translator
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Request $request, Link $link, LinkRepository $linkRepository, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(LinkType::class, $link, [
            'method' => 'DELETE',
            'disabled' => true,
            'inherit_data' => false,
        ]);

        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $linkName = $link->getUrl();

            $linkRepository->delete($link);

            $this->addFlash('success', $translator->trans('message.success.link.remove', ['%name%' => $linkName]));

            return $this->redirectToRoute('link_list');
        }

        return $this->render('link/remove.html.twig', [
            'form' => $form->createView(),
            'link' => $link,
        ]);
    }

    /**
     * Public link redirect to original from hash action.
     *
     * @Route(
     *     "/link,{hash}",
     *     name="link_redirect",
     *     requirements={"hash": "[a-z0-9]*"},
     *     methods={"GET"}
     * )
     *
     * @param Link              $link
     * @param CounterRepository $repository
     *
     * @return RedirectResponse
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function moveTo(Link $link, CounterRepository $repository): RedirectResponse
    {
        if (null === $link) {
            return $this->redirectToRoute('app_homepage');
        }

        $url = $link->getUrl();

        /** @var Counter $counter */
        $counter = new Counter();

        $counter->setLink($link);
        $repository->save($counter);

        return new RedirectResponse($url, Response::HTTP_FOUND);
    }
}
