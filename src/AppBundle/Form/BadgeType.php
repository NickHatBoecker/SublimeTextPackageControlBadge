<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use AppBundle\Service\Badge;
use Symfony\Component\Form\FormBuilderInterface;

class BadgeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('package', TextType::class, [
                'label' => 'Name of sublime package',
                'attr' => [
                    'placeholder' => 'Copy Filepath With Line Numbers',
                ],
            ])
            ->add('badgeType', ChoiceType::class, [
                'choices'  => [
                    'Total' => Badge::BADGETYPE_TOTAL,
                    'Linux' => Badge::BADGETYPE_LINUX,
                    'Mac OS' => Badge::BADGETYPE_MACOS,
                    'Windows' => Badge::BADGETYPE_WINDOWS,
                ],
            ])
        ;
    }
}
