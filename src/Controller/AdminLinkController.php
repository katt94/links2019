<?php
/**
 * Admin link controller file.
 */

namespace App\Controller;

use App\Entity\Link;
use App\Form\LinkWithUserType;
use App\Repository\LinkRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AdminLinkController.
 */
class AdminLinkController extends AbstractController
{
    /**
     * Link list action.
     *
     * @Route("/admin/link-list", name="admin_link_list")
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request            $request
     * @param PaginatorInterface $paginator
     * @param LinkRepository     $linkRepository
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator, LinkRepository $linkRepository): Response
    {
        /** @var Link[] $links */
        $links = $linkRepository->findAll();

        return $this->render('admin/link_list.html.twig', [
            'pagination' => $paginator->paginate($links, $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * Link add action.
     *
     * @Route(
     *     "/admin/link-add",
     *     name="admin_link_add",
     *     methods={"GET", "POST"}
     * )
     *
     * @IsGranted("ROLE_ADMIN")
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

        $form = $this->createForm(LinkWithUserType::class, $link);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = base_convert(md5($link->getUrl().strtotime(date('c'))), 7, 16);
            $link->setHash($hash);

            $linkRepository->save($link);

            $this->addFlash('success', $translator->trans('message.success.link.add'));

            return $this->redirectToRoute('admin_link_list');
        }

        return $this->render('admin/link_add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Link edit action.
     *
     * @Route(
     *     "/admin/link/{id}/edit",
     *     name="admin_link_edit",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "PUT"}
     * )
     *
     * @IsGranted("ROLE_ADMIN")
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
        $form = $this->createForm(LinkWithUserType::class, $link, ['method' => 'PUT']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($link->getUrl() !== $form->get('link')->get('url')) {
                $hash = base_convert(md5($link->getUrl().strtotime(date('c'))), 7, 16);
                $link->setHash($hash);
            }

            $linkRepository->save($link);

            $this->addFlash('success', $translator->trans('message.success.link.edit'));

            return $this->redirectToRoute('admin_link_list');
        }

        return $this->render('admin/link_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Link remove action.
     *
     * @Route(
     *     "/admin/link-remove/{id}",
     *     name="admin_link_remove",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "DELETE"}
     * )
     *
     * @IsGranted("ROLE_ADMIN")
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
        $form = $this->createForm(LinkWithUserType::class, $link, ['method' => 'DELETE', 'disabled' => true]);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $linkName = $link->getUrl();

            $linkRepository->delete($link);

            $this->addFlash('success', $translator->trans('message.success.link.remove', ['%name%' => $linkName]));

            return $this->redirectToRoute('admin_link_list');
        }

        return $this->render('admin/link_remove.html.twig', [
            'form' => $form->createView(),
            'link' => $link,
        ]);
    }
}
