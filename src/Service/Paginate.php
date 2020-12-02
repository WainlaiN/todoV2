<?php


namespace App\Service;


use App\Repository\TaskRepository;
use Doctrine\ORM\Query;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class Paginate
{

    private PaginatorInterface $paginator;
    private Request $request;

    /**
     * Paginate constructor.
     *
     * @param PaginatorInterface $paginator
     * @param Request $request
     */
    public function __construct(PaginatorInterface $paginator, Request $request)
    {
        $this->paginator = $paginator;
        $this->request = $request;
    }

    public function paginate(Query $query)
    {
        $data = $this->paginator->paginate(
            $query,
            $this->request->query->getInt('page',1),
            5
        );

        return $data;

    }


}