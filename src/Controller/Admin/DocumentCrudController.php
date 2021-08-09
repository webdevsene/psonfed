<?php

namespace App\Controller\Admin;

use App\Entity\Document;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
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
            //FormField::addPanel('Informations importantes'),
            AssociationField::new('dossier'),
            TextField::new('titre')->hideOnIndex(),
            TextEditorField::new('description'),
            TextField::new('type')->onlyWhenCreating(),
            TextField::new('auteur')->onlyWhenCreating(),

            //FormField::addPanel('Autres informations'),
            TextareaField::new('thumbnailFile')->setFormType(VichFileType::class, [
                'delete_label' => 'supprimer?'
                ])->setLabel('Charger un fichier')
                ->onlyWhenCreating(),
            SlugField::new('thumbnail')
                    ->setTargetFieldName('titre')
                    ->setLabel('Fichier')
                    ->setTemplatePath('thumbnail.html.twig')
                    ->setCustomOption('base_path', $this->params->get('uploads_path')),

            //FormField::addPanel('Autres informations'),
            DateField::new('publishedAt')->setLabel('Date de publication')->hideOnIndex(),
            DateField::new('updatedAt')->setLabel('Dernière modification')->hideOnForm(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setDefaultSort(['createdAt' => "DESC"])
        ;
    }

}
