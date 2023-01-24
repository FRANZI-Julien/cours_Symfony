<?php

namespace App\Controller\Admin;

use App\Entity\Game;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class GameCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Game::class;
    }

   
    public function configureFields(string $pageName): iterable
    {
        return [
           
            TextField::new('title'),
            TextField::new('players'),
            IntegerField::new('time'),
            IntegerField::new('age'),
            AssociationField::new('categories'),
            DateField::new('released'),
            TextEditorField::new('description'),
            TextEditorField::new('content'),
            ImageField::new('picture')->setBasePath('/uploads/')->setUploadDir('public/uploads/')->setRequired(false)
        ];
    }
    
}
