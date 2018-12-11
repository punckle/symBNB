<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", 'Taper un titre'))
            ->add('slug', TextType::class, $this->getConfiguration("Adresse web", "Taper adresse web", [
                'required' => false
            ]))
            ->add('introduction', TextareaType::class, $this->getConfiguration("Introduction", "Donner une description globale de l'annonce"))
            ->add('content', TextareaType::class, $this->getConfiguration("Description", "Donner une description détaillée du logement"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambre", "Indiquer le nombre de chambre(s) disponible(s)"))
            ->add('price', MoneyType::class, $this->getConfiguration("Prix", "Indiquer le prix par nuit"))
            ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image de couverture", "Entrer l'adresse URL d'une image qui illustrera l'annonce"))
            ->add('images', CollectionType::class, [
                'entry_type' => ImagesType::class,
                'allow_add' => true,
                'allow_delete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
