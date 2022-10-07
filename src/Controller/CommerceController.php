<?php

namespace App\Controller;

use SessionHandler;
use App\Entity\Produit;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
        //ON A BESOIN DU MANAGER POUR FAIRE LE LIEN ENTRE L'OBJET ET LA BDD (AVEC PERSIST FLUSH)
        $em = $doctrine->getManager();

        $commande = new Commande;
        $commande->setNumRef(substr(sha1(mt_rand()),17,6));
        $em->persist($commande);


        //CREATION DES LIGNES
        //SOLUTION POSSIBLE
        $panier = $session->get('panier');
        foreach($panier as $key=>$ligne){
            $produit = $em->getRepository(Produit::class)->find($ligne['produit']->getId());
            ${"ligneC".$key} = new LigneCommande;
            ${"ligneC".$key}->setQuantite($ligne['quantite']);
            ${"ligneC".$key}->setProduit($produit);
            ${"ligneC".$key}->setCommande($commande);
            $em->persist(${"ligneC".$key});
        }

         $em->flush();

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
