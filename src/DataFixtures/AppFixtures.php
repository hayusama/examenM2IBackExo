<?php

namespace App\DataFixtures;

use App\Entity\Produit;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $produit1 = new Produit;
        $produit1->setReference("75318")
                 ->setImage("https://www.coindugeek.com/5211-large_default/lego-l-enfant-the-mandalorian-baby-yoda-grogu-75318.jpg")
                 ->setNom("Lego L'enfant The Mandalorian")
                 ->setDescriptionCourte("Il est trop mignon ! ... et même en Lego ! Grogu, alias Baby Yoda débarque en briques dans ce coffret de 1075 pièces. Assemblez-les toutes et découvrez ce magnifique Baby Yoda avec la boule du Razor Crest dans les mains.")
                 ->setDescriptionLongue("Assemblez les 1075 pièces de ce kit Lego 75318 et obtenez un merveilleux Baby Yoda en chair et en briques !Ce kit Lego 75318 va littéralement vous faire fondre ! Avec sa bouille ultra mignonne et la boule du Razor Crest dans la main, Grogu vous regarde d'une manière attendrissante à faire tomber The Mandalorian himself !")
                 ->setTarif(84.99)
                 ->setActif(true);

        $produit2 = new Produit;
        $produit2->setReference("GSC90908")
                ->setImage("https://www.coindugeek.com/4045-large_default/figurine-harley-quinn-10cm-nendoroid.jpg")
                ->setNom("Figurine Severus Snape 10cm Nendoroid")
                ->setDescriptionCourte("Figurine articulée au look kawaï représentant Severus Snape (Harry Potter). De la collection Nendoroid, elle est livrée avec accessoires et socle.")
                ->setDescriptionLongue("Mesurant 10cm de hauteur, cette figurine articulée est livrée avec des accessoires et un socle pour la mise en scène.")
                ->setTarif(83.99)
                ->setActif(true);

        $produit3 = new Produit;
        $produit3->setReference("GSC90690")
                ->setImage("https://www.coindugeek.com/4051-large_default/figurine-hermione-granger-10cm-nendoroid.jpg")
                ->setNom("Figurine Hermione Granger 10cm Nendoroid")
                ->setDescriptionCourte("Figurine articulée au look kawaï de 10cm reproduisant le personnage de Hermione Granger dans l'univers Harry Potter. Collection Nendoroid avec accessoires et socle.")
                ->setDescriptionLongue("Gamme Nendoroid de chez Good Smile Company, elle mesure 10cm de hauteur et est livrée avec quelques accessoires et un socle.")
                ->setTarif(49.24)
                ->setActif(true);

        $produit4 = new Produit;
        $produit4->setReference("JCCDBZ0328")
                ->setImage("https://www.coindugeek.com/2889-large_default/starter-dragon-ball-puissance-des-saiyans.jpg")
                ->setNom("Starter Dragon Ball Puissance des Saiyans")
                ->setDescriptionCourte("Deck de démarrage 32 cartes dont 2 holographiques de l'univers de Dragon Ball. Collection Puissance des Saiyans.")
                ->setDescriptionLongue("Nouveauté : deux personnages sur la même carte pour une plus grande puissance au combat et des effets plus dévastateurs !")
                ->setTarif(7.30)
                ->setActif(true);

        $produit5 = new Produit;
        $produit5->setReference("P1191512653-2")
                    ->setImage("https://www.coindugeek.com/2706-large_default/t-shirt-welcome-to-arkham-femme.jpg")
                    ->setNom("T-Shirt Welcome to Arkham (Femme)")
                    ->setDescriptionCourte("Très beau T-Shirt rendant hommage à la déjantée d'Arkham : Harley Quinn ! On la voit en ombre chinoise, batte de Baseball à la main, avec en arrière plan la ville d'Arkham. Modèle femme.")
                    ->setDescriptionLongue("Sublime T-Shirt rendant hommage à la célébrissime Harley Quinn ! On y voit sa silhouette (batte de baseball à la main) avec en arrière plan la ville d'Arkham, celle hébergeant l'asile psychiatrique dont elle s'est évadée.")
                    ->setTarif(19.99)
                    ->setActif(true);

        $produit6 = new Produit;
        $produit6->setReference("7HGKJ1")
                ->setImage("https://www.coindugeek.com/5014-large_default/t-shirt-cest-pas-faux-femme.jpg")
                ->setNom("T-Shirt C'est pas Faux (Femme)")
                ->setDescriptionCourte("La best punchline ever par Perceval dans Kaamelott, sur un T-Shirt. C'est pas faux ! Homme à la mythique série comico-médiévale. Version femme.")
                ->setDescriptionLongue("Personnage phare de la série Kaamelott, Perceval est ... un homme étrange. Mais surtout doté d'une punchline qui fait mouche à tous les coups : c'est pas faux ! Car comme il le dit lui même, ce n'est pas forcément vrai, ce n'est pas forcément faux, c'est donc pas faux !")
                ->setTarif(19.99)
                ->setActif(true);

        //PERMET DE FAIRE UN PERSIST SUR CHAQUE ELEMENT (ARRAY WALK UN PEU COMME FOREACH EN JS ,LA FONCTION DERRIERE EST UN CALLBACK PLUS EXACTEMENT UNE CLOSURE)
        $tabP = [$produit1,$produit2,$produit3,$produit4,$produit5,$produit6];
        array_walk($tabP, function($entity) use ($manager) {$manager->persist($entity);});

        $manager->flush();
    }
}
