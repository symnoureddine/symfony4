<?php

namespace App\Controller;

use Symfony\Component\DependencyInjection\{
    Loader\XmlFileLoader ,
    Loader\YamlFileLoader as MyFileLoader
};

use Symfony\Component\HttpFoundation\{
    Request ,
    Response 
};

use Symfony\Component\Config\ConfigCache;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\Routing\Annotation\Route;



class HomeController extends AbstractController
{
    private $cache;
    public function __construct(AdapterInterface $cacheClient)
    {
        $this->cache = $cacheClient;
    }
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {



        $routeName = $request->attributes->get('_route');
        $routeParameters = $request->attributes->get('_route_params');

        // use this to get all the available attributes (not only routing ones):
        $allAttributes = $request->attributes->all();
        dump($routeParameters);

        die();


        $itemCache = $this->cache->getItem('cached');
        $cached = 'no';
        if (!$itemCache->isHit()) {
            $itemCache->set('yes');
            $this->cache->save($itemCache);
        } else {
            $cached = $itemCache->get();
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'cached' => $cached,
        ]);
    }

    /**
     * @Route("/product", name="")
     */
    public function create()
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);

        $product = $repository->find(1);

        $hash = "reference:" . $product->getId();;

        if($this->redis->exists($hash))
        {
            throw new \Exception('An redis hash  "' . $hash . ' already exist');
        }
        $values = $this->transformObjectToArray($product);
        $valuesReadyToRedis = $this->redislizeArray($values);
        $this->redis->hmset($hash, $valuesReadyToRedis);
    }

     /**
     * @Route({
     *     "en": "/about-us",
     *     "nl": "/over-ons"
     * }, name="about_us")
     */
    public function about()
    {

        dump('abount');die();

    }
}