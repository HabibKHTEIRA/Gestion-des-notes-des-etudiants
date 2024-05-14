<?php

namespace App\Form;

use App\Entity\AnneeUniversitaire;
use App\Entity\Groupe;
use App\Entity\Filiere;
use App\Entity\Etudiant;
use App\Entity\Resultatbac;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use App\Validator\Constraints\UniqueEmail;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class EtudiantType extends AbstractType
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numetd',NumberType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=>'8',
                    'maxlength'=>'8',
                    'required' => true,
                    'multiple' => true,
                    'id'=>'inputemail'

                ],
                'label'=>'Numéro étudiant :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4',
                    'for'=>'inputemail'
                ],
                'constraints'=>[
                    new Regex([
                        'pattern' => '/^[0-9]{8}$/',
                        'message' => 'Le champ doit contenir 8 chiffres'
                    ]),
                ]
            ])
            ->add('nom',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=>'2',
                    'maxlength'=>'50',
                    'required' => true,
                    'id'=>'inputemail'
                    
                ],
                'label'=>'Nom :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4',
                    'for'=>'inputnom'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(["min"=>2,"max"=>50,"minMessage"=>"erreur"])

                ]
            ])
            ->add('prenom',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'minlength'=>'2',
                    'maxlength'=>'50',
                    'required' => true,
                ],
                'label'=>'Prenom :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                    new Assert\Length(["min"=>2,"max"=>50,"minMessage"=>"erreur"])

                ]
            ])
            ->add('email',EmailType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'required' => true,
                ],
                'label'=>'Email :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                   new Assert\Email(message:"L'email '{{ value }}' n'est pas valide",mode:"strict")
                   
                   
                ]
            ])
            ->add('sexe',ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Homme' => 'M',
                    'Femme' => 'F',
                    'autre' => 'O',
                ],
                'placeholder' => 'Selectionner le genre',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('adresse',TextType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                ],
                'label'=>'Adresse :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["max"=>50,"minMessage"=>"erreur"])

                ]
            ])
            ->add('tel',TelType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                ],
                'label'=>'Télephone :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["max"=>60,"maxMessage"=>"erreur"])

                ]
            ])
            ->add('datnaiss',DateType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                    'required' => true,
                ],
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'widget' => 'choice',
                'format' => 'dd-MM-yyy',
                'label'=>'Date de naissance :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'years' => $this->getYears()
            ])
            ->add('depnaiss',TextType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                ],
                'label'=>'Depatement de naissance :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
        

                ]
            ])
            ->add('villnaiss',TextType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                ],
                'label'=>'Ville de naissance :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["max"=>60,"minMessage"=>"erreur"])

                ]
            ])
            ->add('paysnaiss',TextType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                ],
                'label'=>'Pays de naissance :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["max"=>60,"minMessage"=>"erreur"])

                ],
                
            ])
            ->add('nationalite',TextType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                    'required' => false,
                ],
                'label'=>'Nationnalité :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["max"=>60,"maxMessage"=>"erreur"])

                ]
            ])
            ->add('sports',TextType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                ],
                'label'=>'Sport :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["max"=>60,"minMessage"=>"erreur"])

                ]
            ])
            ->add('handicape',TextType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                ],
                'label'=>'Handicape :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["max"=>60,"minMessage"=>"erreur"])
                    

                ]
            ])
            ->add('derdiplome',TextType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                ],
                'label'=>'Dernier diplome :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["min"=>3, "max"=>60,"minMessage"=>"erreur"]),
                    new Assert\NotBlank()

                ]
            ])
            ->add('dateinsc',DateType::class,[
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                    'required' => true,
                ],
                'placeholder' => [
                    'year' => 'Année', 'month' => 'Mois', 'day' => 'Jour',
                ],
                'widget' => 'choice',
                'format' => 'dd-MM-yyy',
                'label'=>'Date d\'inscription :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'years' => $this->getYearsinsc()
                
            ])
            ->add('registre',TextType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                ],
                'label'=>'Registre :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["max"=>60,"minMessage"=>"erreur"])
                    

                ]
            ])
            ->add('statut',TextType::class,[
                'required' => false,
                'attr'=>[
                    'class'=>'form-control',
                    'maxlength'=>'60',
                    'required' => true,
                ],
                'label'=>'Statut :  ',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Length(["max"=>60,"minMessage"=>"erreur"]),

                ]
            ])
            ->add('groupe',EntityType::class,[
                'class' => Groupe::class,
                'label'=>'Groupe :  ',
                'choice_label' => 'nomgrp',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
            ])
            ->add('filiere', ChoiceType::class,[
                'label'=>'Filiere: ',
                'choices' => $this->getFiliereChoices(),
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'placeholder' => 'Sélectionner une filière',

            ])
            /*->add('anne', ChoiceType::class,[
                'label'=>'Année universitaire : ',
                'choices' => $this->getAnneeChoices(),
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'placeholder' => 'Sélectionner l\'année universitaire',

            ]);
            ->add('ResultatBac',EntityType::class,[
                'class' => Resultatbac::class,
                'choice_label' => 'bac'
            ])*/
        ;
    }
    private function getYears()
    {
        $currentYear = date('Y');
        $years = [];

        for ($year = $currentYear - 45; $year <= $currentYear - 17; $year++) {
            $years[] = $year;
        }

        return $years;
    }
    private function getYearsinsc()
    {
        $currentYear = date('Y');
        $years = [];

        for ($year = $currentYear; $year >= $currentYear - 15; $year--) {
            $years[] = $year;
        }

        return $years;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class
        ]);
    }
    /**
     * retourne la liste de tous les pays
     *
     * @return array
     */
    public function pays():array
   {
    // Effectuer la requête à l'API Geonames pour récupérer la liste de tous les pays
    $httpClient = HttpClient::create();
    $response = $httpClient->request('GET', 'http://api.geonames.org/countryInfoJSON?username=demo');
    $data = $response->toArray();

    // Récupérer la liste des pays à partir des données de la réponse
    $countries = $data['geonames'];

       return $countries;
   }
   public function getFiliereChoices()
    {
        $filiereRepository = $this->entityManager->getRepository(Filiere::class);
        $filieres = $filiereRepository->findAll();

        $choices = [];
        foreach ($filieres as $filiere) {
            $choices[$filiere->getNomfiliere()] = $filiere->getNomfiliere();
        }
    
        return $choices;
    }
    public function getAnneeChoices()
    {
        $anneRepository = $this->entityManager->getRepository(AnneeUniversitaire::class);
        $annes = $anneRepository->findAll();

        $choices = [];
        foreach ($annes as $anne) {
            $choices[$anne->getAnnee()] = $anne->getAnnee();
        }
    
        return $choices;
    }
}
