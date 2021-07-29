<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DocumentCrudController extends AbstractCrudController
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public static function getEntityFqcn(): string
    {
        return Document::class;
    }


    public function configureFields(string $pageName): iterable
    {
        //$thumbnailFile = TextareaField::new('thumbnailFile')->setFormType(VichFileType::class);
        //$thumbnail = TextareaField::new('thumbnail')->setTemplatePath('thumbnail.html.twig')->setCustomOption('base_path', $this->params->get('uploads_path'));

        return [
            TextField::new('titre'),
            TextEditorField::new('description'),
            TextField::new('type')->onlyWhenCreating(),
            TextField::new('auteur')->onlyWhenCreating(),
            TextareaField::new('thumbnailFile')->setFormType(VichFileType::class, [
                'delete_label' => 'supprimer?'
                ])->setLabel('Charger un fichier')
                  ->onlyOnForms(),
            TextareaField::new('thumbnail')->setLabel('Nom du fichier')->setTemplatePath('thumbnail.html.twig')->setCustomOption('base_path', $this->params->get('uploads_path')),
            DateField::new('publishedAt')->setLabel('Date de publication')->hideOnIndex(),
            DateField::new('updatedAt')->setLabel('Dernière modification')->onlyOnIndex(),
        ];
    }

}
