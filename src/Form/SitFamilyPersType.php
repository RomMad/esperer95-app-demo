<?php

namespace App\Form;

use App\Form\Utils\Choices;
use App\Entity\SitFamilyPers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SitFamilyPersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add("maritalStatus", ChoiceType::class, [
                "choices" => Choices::getChoices(SitFamilyPers::MARITAL_STATUS),
                "placeholder" => "-- Select --",
                "required" => false
            ])
            ->add("childcareSchool", ChoiceType::class, [
                "choices" => Choices::getChoices(SitFamilyPers::CHILDCARE_SCHOOL),
                "placeholder" => "-- Select --",
                "required" => false
            ])
            ->add("childcareSchoolLocation")
            ->add("childToHost", ChoiceType::class, [
                "choices" => Choices::getChoices(SitFamilyPers::CHILD_TO_HOST),
                "placeholder" => "-- Select --",
                "required" => false
            ])
            ->add("childDependance", ChoiceType::class, [
                "choices" => Choices::getChoices(SitFamilyPers::CHILD_DEPENDANCE),
                "placeholder" => "-- Select --",
                "required" => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            "data_class" => SitFamilyPers::class,
            "translation_domain" => "sitFamily",
        ]);
    }
}
