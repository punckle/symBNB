<?php

namespace App\Service;

use Doctrine\Common\Persistence\ObjectManager;

class Pagination
{
    //Représente l'entité sur laquelle on va faire la pagination :
    private $entityClass;

    private $limit = 10;

    private $currentPage = 1;

    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * 1.Connaître le total des enregistrements de la table
     * 2.Faire la division, l'arrondi et le renvoyer
     */
    public function getPages()
    {
        //1.
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());
        //2.
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    /**
     * 1.Calculer l'offset
     * 2.Demander au repository de trouver les éléments
     * 3.Envoyer les éléments
     */
    public function getData()
    {
        //1.
        $offset = $this->currentPage * $this->limit - $this->limit;
        //2.
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);
        //3.
        return $data;

    }

    public function getEntityClass()
    {
        return $this->entityClass;
    }

    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getPage()
    {
        return $this->currentPage;
    }

    public function setPage(int $page)
    {
        $this->currentPage = $page;

        return $this;
    }


}