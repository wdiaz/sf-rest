<?php


namespace App\Controller;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
	/**
	 * ArticleController constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * @Route("/", name="app_article_homepage")
	 */
	public function homepage()
    {
        return $this->render('article/homepage.html.twig');
    }

	/**
	 * @Route("news/{slug}", name="app_article_show")
	 */
	public function show($slug)
	{
		$comments = [
			'Lorem ipsum is a pseudo-Latin text used in web design, typography, layout, and printing in place of English to emphasise design elements over content.',
			' been changed by addition or removal, so to deliberately render its content nonsensical; it\'s not genuine, correct, or comprehensible La',
			'The are likely to focus on the text, disregarding the layout and its elements. Besides, random text risks to be unintendedly hu'
		];
		return $this->render('article/show.html.twig', [
			'title' => ucwords(str_replace('-', ' ', $slug)),
			'comments' => $comments,
			'slug' => $slug
		]);
    }

	/**
	 * @Route("news/{slug}/heart", name="app_article_toggle_heart", methods={"POST"})
	 */
	public function toggleArticleHeart($slug, LoggerInterface $logger)
	{
		$logger->info('Article is being hearted');
		// TODO - actually heart/unheart the article
		return new JsonResponse(['hearts'=> rand(4, 100)]);
    }
}