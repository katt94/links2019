<?php
/**
 * Admin tag controller file.
 */

namespace App\Controller;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
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
 * Class AdminTagController.
 */
class AdminTagController extends AbstractController
{
    /**
     * @Route("/admin/tag-list", name="admin_tag_list")
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request            $request
     * @param PaginatorInterface $paginator
     * @param TagRepository      $tagRepository
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator, TagRepository $tagRepository): Response
    {
        /** @var Tag[] $tags */
        $tags = $tagRepository->findAll();

        return $this->render('tag/list.html.twig', [
            'pagination' => $paginator->paginate($tags, $request->query->getInt('page', 1)),
        ]);
    }

    /**
     * @Route(
     *     "/admin/tag-add",
     *     name="admin_tag_add",
     *     methods={"GET", "POST"}
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request             $request
     * @param TagRepository       $tagRepository
     * @param TranslatorInterface $translator
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Request $request, TagRepository $tagRepository, TranslatorInterface $translator): Response
    {
        /** @var Tag $tag */
        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository->save($tag);

            $this->addFlash('success', $translator->trans('message.success.tag.add'));

            return $this->redirectToRoute('admin_tag_list');
        }

        return $this->render('tag/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "/admin/tag/{id}/edit",
     *     name="admin_tag_edit",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "PUT"}
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request             $request
     * @param Tag                 $tag
     * @param TagRepository       $tagRepository
     * @param TranslatorInterface $translator
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function edit(
        Request $request,
        Tag $tag,
        TagRepository $tagRepository,
        TranslatorInterface $translator
    ): Response {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tagRepository->save($tag);

            $this->addFlash('success', $translator->trans('message.success.tag.edit'));

            return $this->redirectToRoute('admin_tag_list');
        }

        return $this->render('tag/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route(
     *     "/admin/tag/{id}/remove",
     *     name="admin_tag_remove",
     *     requirements={"id": "[1-9]\d*"},
     *     methods={"GET", "DELETE"}
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request             $request
     * @param Tag                 $tag
     * @param TagRepository       $tagRepository
     * @param TranslatorInterface $translator
     *
     * @return Response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(
        Request $request,
        Tag $tag,
        TagRepository $tagRepository,
        TranslatorInterface $translator
    ): Response {
        $form = $this->createForm(TagType::class, $tag, ['method' => 'DELETE', 'disabled' => true]);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $tagName = $tag->getName();

            $tagRepository->delete($tag);

            $this->addFlash('success', $translator->trans('message.success.tag.remove', ['%name%' => $tagName]));

            return $this->redirectToRoute('admin_tag_list');
        }

        return $this->render('tag/remove.html.twig', [
            'form' => $form->createView(),
            'tag' => $tag,
        ]);
    }
}
