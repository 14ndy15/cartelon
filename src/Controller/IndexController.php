<?php

namespace App\Controller;

use App\Entity\Poster;
use App\Entity\ProjectInfo;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Event;
use App\Entity\ExternalSites;
use App\Entity\News;
use App\Entity\Slide;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Video;
use App\Entity\PosterEvent;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    private $amountSlidesDefault = 3;
    private $amountNewsDefault = 2;
    private $amountPosterEventDefault = 3;
    private $amountEventsDefault = 3;
    private $amountVideosDefault = 4;

    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $projectInfo = $this->getDoctrine()
            ->getRepository(ProjectInfo::class)
            ->findOne();

        $amountSlides = $projectInfo->getAmountSlides();
        $amountNews = $projectInfo->getAmountNews();
        $amountPosterEvents = $projectInfo->getAmountPosterEvent();
        $amountEvents = $projectInfo->getAmountEvents();
        $amountVideos = $projectInfo->getAmountVideos();

        if ($amountSlides === null or $amountSlides < 0)
            $amountSlides = $this->amountSlidesDefault;

        if ($amountNews === null or $amountNews < 0)
            $amountNews = $this->amountNewsDefault;

        if ($amountPosterEvents === null or $amountPosterEvents < 0)
            $amountPosterEvents = $this->amountPosterEventDefault;

        if ($amountEvents === null or $amountEvents < 0)
            $amountEvents = $this->amountEventsDefault;

        if ($amountVideos === null or $amountVideos < 0)
            $amountVideos = $this->amountVideosDefault;



        $slides = $this->getDoctrine()
                  ->getRepository(Slide::class)
                  ->orderedByDate($amountSlides);

        $news = $this->getDoctrine()
                ->getRepository(News::class)
                ->orderedByDate($amountNews);

        $moreNews = $this->getDoctrine()
                    ->getRepository(News::class)
                    ->count([]) > $amountNews;


        $posterEvents = $this->getDoctrine()
                        ->getRepository(PosterEvent::class)
                        ->orderByDate($amountPosterEvents);
        $morePosterEvents = $this->getDoctrine()
            ->getRepository(PosterEvent::class)
            ->count([]) > $amountPosterEvents;


        $events = $this->getDoctrine()
                    ->getRepository(Event::class)
                    ->orderedByDate($amountEvents);
        $moreEvents = $this->getDoctrine()
            ->getRepository(Event::class)
            ->count([]) > $amountEvents;


        $videos = $this->getDoctrine()
                ->getRepository(Video::class)
                ->orderedByDate($amountVideos);
        $moreVideos = $this->getDoctrine()
            ->getRepository(Video::class)
            ->count([]) > $amountVideos;

        $sites = $this->getDoctrine()
                ->getRepository(ExternalSites::class)
                ->findAll();


        return $this->render('index/index.html.twig', [
            'projectInfo' => $projectInfo,
            'slides' => $slides,
            'news' => $news,
            'moreNews' => $moreNews,
            'posterEvents' => $posterEvents,
            'morePosterEvents' => $morePosterEvents,
            'events' => $events,
            'moreEvent' => $moreEvents,
            'videos' => $videos,
            'moreVideos' => $moreVideos,
            'sites' => $sites,
            'date' => new \DateTime()
        ]);
    }

    /**
     * @Route("/news/{amount}")
     * @Route("/news/{pos}/{amount}", name="news")
     */
    public function moreNews($pos = null, $amount){
        date_default_timezone_set('Europe/Madrid');
        // Unix
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        if($pos==null) {
            $projectInfo = $this->getDoctrine()
                ->getRepository(ProjectInfo::class)
                ->findOne();

            $amountNews = $projectInfo->getAmountNews();
            if ($amountNews === null or $amountNews < 0)
                $amountNews = $this->amountNewsDefault;

            $pos = $amountNews;
        }

        $news = $this->getDoctrine()
            ->getRepository(News::class)
            ->findByDateFieldPosition($pos, $amount);

        $countNews = $this->getDoctrine()
                ->getRepository(News::class)
                ->count([]);

        $data = array();
        $posCount = 0;
        foreach ($news as $new){
            array_push($data, array(
                'currentIndex' => $pos+$posCount++,
                'title' => $new->getTitle(),
                'date' => $new->getDateTime()? strftime("%A, %d de %B del %Y", strtotime($new->getDatetime()->format('Y-m-d') )) : '',
                'text' => nl2br($new->getText()),
                'image' => $new->getWebPath(null),
                'file' => $new->getFileWebPath(),
                'maxWidth' => $new->getConstraintWideDimensions()[count($new->getConstraintWideDimensions()) - 1],
                'more' => $countNews > $pos+$posCount
            ));
        }

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/posters/{amount}")
     * @Route("/posters/{pos}/{amount}", name="posters")
     */
    public function morePosterEvents($pos = null, $amount){

        if($pos==null) {
            $projectInfo = $this->getDoctrine()
                ->getRepository(ProjectInfo::class)
                ->findOne();

            $amountPosterEvents = $projectInfo->getAmountPosterEvent();
            if ($amountPosterEvents == null or $amountPosterEvents < 1)
                $amountPosterEvents = $this->amountPosterEventDefault;

            $pos = $amountPosterEvents;
        }

        $posterEvents = $this->getDoctrine()
            ->getRepository(PosterEvent::class)
            ->findByDateFieldPosition($pos, $amount);
        $countPosterEvent = $this->getDoctrine()
            ->getRepository(PosterEvent::class)
            ->count([]);

        $data = array();
        $posCount = 0;
        foreach ($posterEvents as $posterEvent)
        {
            $posters = array();
            foreach ($posterEvent->getPosters() as $poster)
            {
                array_push($posters, array(
                    'author' => $poster->getAuthor(),
                    'year' => $poster->getDate()? $poster->getDate()->format('Y') : '',
                    'description' => nl2br($poster->getDescription()),
                    'image' => $poster->getWebPath(null),
                    'maxWidth' => $poster->getConstraintWideDimensions()[count($poster->getConstraintWideDimensions()) - 1],
                    'imageDetail1' => $poster->getWebPathImageDetail1(null),
                    'imageDetail1MaxWidth' => $poster->getOriginalImageWidthImageDetails1(),
                    'imageDetail2' => $poster->getWebPathImageDetail2(null),
                    'imageDetail2MaxWidth' => $poster->getOriginalImageWidthImageDetails2(),
                ));
            }
            array_push($data, array(
                'currentIndex' => $pos+$posCount++,
                'name' => $posterEvent->getName(),
                'image' => $posterEvent->getWebPath(null),
                'maxWidth' => $posterEvent->getConstraintWideDimensions()[count($posterEvent->getConstraintWideDimensions()) - 1],
                'posters' => $posters,
                'more' => $countPosterEvent > $pos+$posCount
                )
            );
        }



        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/events/{amount}")
     * @Route("/events/{pos}/{amount}", name="events")
     */
    public function moreEvents($pos = null, $amount){

        date_default_timezone_set('Europe/Madrid');
        // Unix
        setlocale(LC_TIME, 'es_ES.UTF-8');
        // En windows
        setlocale(LC_TIME, 'spanish');

        if($pos==null) {
            $projectInfo = $this->getDoctrine()
                ->getRepository(ProjectInfo::class)
                ->findOne();

            $amountEvents = $projectInfo->getAmountEvents();
            if ($amountEvents === null or $amountEvents < 0)
                $amountEvents = $this->amountEventsDefault;

            $pos = $amountEvents;
        }

        $events  = $this->getDoctrine()
            ->getRepository(Event::class)
            ->findByDateFieldPosition($pos, $amount);
        $countEvents  = $this->getDoctrine()
            ->getRepository(Event::class)
            ->count([]);

        $data = array();
        $posCount = 0;
        foreach ($events as $event){

            $images = array();
            foreach ($event->getImageGalleries() as $imageGallery)
                array_push($images, array(
                    'image' => $imageGallery->getWebPath(null),
                    'maxWidth' => $imageGallery->getConstraintWideDimensions()[count($imageGallery->getConstraintWideDimensions()) - 1]
                ));

            array_push($data, array(
                'currentIndex' => $pos+$posCount++,
                'name' => $event->getName(),
                'dateTime' => strftime("%A, %d de %B del %Y", strtotime($event->getDatetime()->format('Y-m-d') )),
                'place' => $event->getPlace(),
                'description' => nl2br($event->getDescription()),
                'image' => $event->getWebPath(null),
                'maxWidth' => $event->getConstraintWideDimensions()[count($event->getConstraintWideDimensions()) - 1],
                'images' => $images,
                'more' => $countEvents > $pos+$posCount
            ));
        }

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @Route("/videos/{amount}")
     * @Route("/videos/{pos}/{amount}", name="videos")
     */
    public function moreVideos($pos = null, $amount){

        if($pos==null) {
            $projectInfo = $this->getDoctrine()
                ->getRepository(ProjectInfo::class)
                ->findOne();

            $amountVideos = $projectInfo->getAmountVideos();
            if ($amountVideos === null or $amountVideos < 0)
                $amountVideos = $this->amountVideosDefault;

            $pos = $amountVideos;
        }

        $videos  = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findByDateFieldPosition($pos, $amount);
        $countVideos  = $this->getDoctrine()
            ->getRepository(Video::class)
            ->count([]);

        $data = array();
        $posCount = 0;
        foreach ($videos as $video){
            array_push($data, array(
                'currentIndex' => $pos+$posCount++,
                'title' => $video->getName(),
                'url' => urlencode($video->getIframe()),
                'more' => $countVideos > $pos+$posCount
            ));
        }

        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @Route("/mail", name="mail")
     */
    public function mailSuscription(Request $request, \Swift_Mailer $mailer){

        $email = $request->get('email');
        $response = ['response'=>'error'];

        if (filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            mail('carteloncuba@gmail.com',
                'Nuevo subscriptor',
                'Hola!, Tienes un nuevo subscriptor su correo es '.$email);

            $response = ['response'=>'success'];
        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/search/{query}", name="search")
     */
    public function search($query){

        $query = strtolower($query);
        $replaceThis = Array(
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            '(' => '',
            ')' => ''
        );
        $query = str_replace(array_keys($replaceThis), $replaceThis, $query);

        $posters = $this->getDoctrine()
            ->getRepository(Poster::class)
            ->findBySearch($query);

        $data = array();

        foreach ($posters as $poster)
        {
            array_push($data, array(
                'eventName' => $poster->getAssociateEvent()->getName(),
                'author' => $poster->getAuthor(),
                'year' => $poster->getDate()? $poster->getDate()->format('Y') : '',
                'description' => nl2br($poster->getDescription()),
                'image' => $poster->getWebPath(null),
                'maxWidth' => $poster->getConstraintWideDimensions()[count($poster->getConstraintWideDimensions()) - 1],
                'imageDetail1' => $poster->getWebPathImageDetail1(null),
                'imageDetail1MaxWidth' => $poster->getOriginalImageWidthImageDetails1(),
                'imageDetail2' => $poster->getWebPathImageDetail2(null),
                'imageDetail2MaxWidth' => $poster->getOriginalImageWidthImageDetails2()
            ));
        }


        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/build", name="build")
     */
    public function makeSearchableText(){
        $em = $this->getDoctrine()->getManager();
        $posters = $this->getDoctrine()
            ->getRepository(Poster::class)
            ->findAll();

        $data = array();

        foreach ($posters as $poster){
            $poster->buildSearchableText();
            array_push($data, $poster->getSearchableText());
            $em->persist($poster);
            $em->flush();
        }


        $response = new Response();
        $response->setContent(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
