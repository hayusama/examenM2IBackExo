<?php

namespace App\Controller;

use App\Entity\Produit;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use SessionHandler;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CommerceController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $produits = $doctrine->getRepository(Produit::class)->findBy(['actif'=>true],['id'=>'ASC']);
        return $this->render('commerce/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/p/{id}/{slug}', name: 'app_produit', requirements: ['id' => "\d+", "slug" => '.{1,}'])]
    #[ParamConverter('Produit',class: Produit::class)]
    public function produit(Produit $produit,Request $request, SessionInterface $session): Response
    {
        if($request->request->get('ajout')){
            // dump($request->request->get('quantite'));
            // dump($request->request->get('produit'));
            // $session->set('panier', [$request->request->get('quantite'),$request->request->get('produit')]);
            // dump($session->get('panier'));
            // $session->clear();
            //RECUPERER PANIER EXISTANT
            $panier = $session->get('panier');
            $ajout = false;
            if($panier) {
                foreach($panier as $nb => $infoProduit){
                    if($infoProduit['produit']->getId() == $produit->getId()){
                        $panier[$nb]['quantite'] += $request->request->get('quantite');
                        $ajout = true;
                    }
                }
                if(!$ajout){
                    $panier[] = ['quantite'=>$request->request->get('quantite'), 'produit'=>$produit];
                }
                $session->set('panier',$panier);
            } else {
                $session->set('panier',[ ['quantite' => $request->request->get('quantite'), 'produit' => $produit]]);
            }
            $this->addFlash('info', 'Vous avez ajouté '.$request->request->get('quantite').' X '.$produit->getNom().' à votre panier');
        }

        return $this->render('commerce/produit.html.twig', [
            'produit' => $produit,
        ]);
    }

    #[Route('/panier', name: 'app_panier')]
    public function panier(): Response
    {
        return $this->render('commerce/panier.html.twig', [
            'controller_name' => 'CommerceController',
        ]);
    }

    #[Route('/commande', name: 'app_commande')]
    public function commande(SessionInterface $session,ManagerRegistry $doctrine): Response
    {

        $session->clear();
        $this->addFlash('info', 'Votre commande a été validé');
        return $this->redirectToRoute('app_accueil');
    }


    public function menu(): Response {
        $listMenu = [
            ['title'=> "Site E-commerce", "text"=>'Accueil', "url"=> $this->generateUrl('app_accueil')],
            ['title'=> "Mon panier", "text"=>'Mon panier', "url"=> $this->generateUrl('app_panier')],
        ];

        return $this->render("parts/menu.html.twig", ["listMenu" => $listMenu]);
    }
}
