<?php
    namespace App\Filtrer;

    use App\Entity\Category;

    class Filter{
        /**
         * @var $string
         */
        public $string ='';
        /**
         * @var Category[]
         */
        public array $categories =[];
    }
    //en mettant public array ça a permis de faire l'affiche de mon filtre reste à savoir pourquoi