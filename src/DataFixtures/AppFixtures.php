<?php

namespace App\DataFixtures;

use App\Repository\CustomerRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use App\Entity\Phone;
use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture {

    private UserPasswordHasherInterface $userPasswordHasher;
    private CustomerRepository $customerRepository;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher, CustomerRepository $customerRepository) {

        $this->userPasswordHasher = $userPasswordHasher;
        $this->customerRepository = $customerRepository;
    }

    public function load(ObjectManager $manager): void {

        $faker = Faker\Factory::create('fr_FR');

        $phoneNames = ['Honor Magic 4 Pro', 'Apple iPhone 14 Pro', 'Google Pixel 7 Pro', 'Asus Zenfone 9', 'Google Pixel 7', 'Google Pixel 6',
            'Nothing Phone (1)', 'Google Pixel 6a', 'Xiaomi Redmi Note 11 Pro', 'Xiaomi Redmi Note 10 5G', 'Huawei Mate 50 Pro',
            'Huawei P20 Lite'];

        $phoneBrands = ['Honor', 'Apple', 'Google', 'Asus', 'Google', 'Google', 'Nothing', 'Google', 'Xiaomi', 'Xiaomi', 'Huawei', 'Huawei'];

        $phoneDescriptions = [
            'Le Honor Magic 4 Pro est une excellente surprise et son style tape-à-l\'œil est à la hauteur de
            ce qui se cache sous le capot. Si l\'on retrouve tous les composants qui font d\'un smartphone un flagship à posséder,
            il propose une partie photo vraiment intéressante ainsi qu\'une technologie de charge ultra-pratique. En bref, carton
            plein pour le terminal de la marque chinoise.',
            'Cette année, il se passe quelque chose de nouveau sur l’écran du 14 Pro, l\'encoche a été remplacée par une découpe
            qui affiche désormais les alertes système et les activités en arrière-plan. Et notre engouement pour ce mini
            centre de notifications ne fait que croître à mesure que nous l’utilisons. L\'iPhone 14 Pro profite également de la
            mise à jour incrémentale habituelle, avec une configuration photo améliorée, un processeur plus performant, un écran
            toujours actif, iOS 16 ainsi que de nouvelles fonctions de sécurité, notamment la détection des collisions et le
            SOS d\'urgence par satellite.',
            'Le Pixel 7 Pro n\'a peut-être pas la même saveur que le Pixel 6 Pro de l\'année dernière, mais il présente encore
            des améliorations notables. Le supplément de 250€ que le Pro impose par rapport au Pixel 7 se défend avec le
            téléobjectif de 48 mégapixels, ainsi que le grand écran.',
            'L\'Asus Zenfone 9 peut donner satisfaction aux fans de smartphones compacts puisqu\'il embarque presque tout
            ce qui se fait de mieux sur le marché. Pourtant, le nouveau téléphone d\'Asus est dépourvu de plusieurs fonctionnalités
            que la plupart des autres téléphones à ce prix possèdent. Le principal d\'entre eux est l\'absence de recharge
            sans fil et seulement deux ans de mises à jour Android.',
            'Le Pixel 7 s\'inscrit dans la continuité du Pixel 6, offrant une bonne combinaison de fonctionnalités utiles,
            une technologie photo solide et un prix correct. Il arrive après que Google ait essayé quelques stratégies différentes
            pour que ses téléphones Pixel se démarquent au cours des dernières années.',
            'Finitions impeccables, appareil photo remarquable, un bon mix performances/autonomie et surtout un excellent rapport
            qualité/prix, les innovations de Google pour en faire un smartphone utile et agréable au quotidien ont indéniablement
            porté leur fruit, bien aidé en cela par le nouveau processeur Tensor qui a ouvert le jeu en matière d’intelligence
            artificielle. Nous recommandons le Pixel 6 sans réserve à tous ceux qui recherchent un smartphone Android à la fois
            plaisant et pratique, avec des capacités hors-pair en photo.',
            'La jeune marque Nothing est parvenue à délivrer un premier smartphone abouti. Son design atypique est une chose,
            mais ce que l\'on retient avant tout, c\'est l\'excellente qualité de l\'écran, l\'interface épurée et fluide et un
            module photo qui s\'en sort correctement surtout pour un milieu de gamme. Il mérite de progresser au niveau de
            l\'autonomie et la chauffe excessive doit aussi être corrigée. Lorsque son tarif flirte avec les 500€
            (et moins), il devient hautement recommandable.',
            'Génération après génération, Google peaufine sa recette de déclinaison de ses smartphones haut de gamme.
            Les inévitables compromis qui ont été faits pour rendre le Pixel 6a plus abordable que ses grands frères n’ont
            absolument rien de rédhibitoire. Le niveau de finition est très bon, de même que les performances. Les principaux
            atouts techniques et fonctionnalités qui font la force des Pixel 6 et 6 Pro, on pense notamment à la
            photo, l\'autonomie et la primeur des mises à jour Android, sont au rendez-vous. Le meilleur choix à ce tarif.',
            'Le Xiaomi Redmi Note 11 Pro 5G est un flagship d\'entrée/milieu de gamme présentant une solide fiche technique :
            écran AMOLED 120 Hz de qualité, bonnes performances, capteur principal efficace, autonomie honorable et charge ultra-rapide.',
            'Le Xiaomi Redmi Note 10 5G est l\'un des smartphones 5G les moins chers du marché. Il profite d\'un écran 90 Hz, d\'un
            capteur principal de 48 mégapixels et d\'une batterie de 5000 mAh. On aurait aimé que Xiaomi apporte le même soin
            à la fiche technique de ce modèle qu\'à la version 4G.',
            'C\'est un euphémisme que de souligner la rareté de Huawei à ce jour sur le marché du smartphone. La marque
            chinoise, qui y a commercialisé un P50 Pro et un P50 Pocket cette année — tout en délaissant les Nova 10 et 10 Pro
            qui devaient y être vendus —, entend montrer son savoir-faire en matière de téléphonie avec son dernier fleuron,
            le Mate 50 Pro. Celui-ci, présenté en Chine il y a quelques mois, est accompagné d\'une gamme complète qui toutefois
            ne sera pas disponible en France ni en Europe.',
            'Comme chaque année, Huawei présente une version Lite de son smartphone phare. Le P20 Lite est donc une version
            édulcorée du P20, venant occuper le terrain du milieu de gamme avec un écran 19:9 amputé de la petite surface de
            l\'encoche. Dans l\'ensemble, le P20 Lite s\'en sort plutôt bien.'];


        for ($i = 0; $i < 12; $i++) {
            $phone = new Phone();

            for ($n = 0; $n < 12; $n++) {
                $phone->setName($phoneNames[$i]);
            }

            for ($b = 0; $b < 12; $b++) {
                $phone->setBrand($phoneBrands[$i]);
            }

            for ($d = 0; $d < 12; $d++) {
                $phone->setDescription($phoneDescriptions[$i]);
            }

            $phone->setPrice($faker->randomFloat(2,100,1400));
            $phone->setCreatedAt(date_create_immutable());
            $manager->persist($phone);
        }
        $manager->flush();

        for ($i = 1; $i < 13; $i++) {
            $customer = new Customer();
            $customer->setName($faker->lastName);
            $customer->setFirstname($faker->firstName);
            $customer->setLogin("Customer-".$i);
            $customer->setPassword($this->userPasswordHasher->hashPassword($customer, "Password-".$i));
            $customer->setMail($faker->email);
            $customer->setRegistrationAt(date_create_immutable());
            $customer->setRole(["ROLE_CUSTOMER"]);
            $manager->persist($customer);
        }
        $manager->flush();

        $allCustomers = $this->customerRepository->findAll();

        foreach ($allCustomers as $customers) {
            for ($i = 0; $i < 25; $i++) {
                $user = new User();
                $user->setName($faker->lastName);
                $user->setFirstname($faker->firstName);
                $user->setCreatedAt(date_create_immutable());
                $user->setCustomer($customers);
                $manager->persist($user);
            }
        }
        $manager->flush();
    }
}
