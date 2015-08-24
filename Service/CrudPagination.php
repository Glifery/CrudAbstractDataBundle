<?php

namespace Glifery\CrudAbstractDataBundle\Service;

use Glifery\CrudAbstractDataBundle\Tools\Datagrid;
use Glifery\CrudAbstractDataBundle\Tools\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CrudPagination
{
    const ATTRIBUTE_PAGE_NAME = '_page';

    const DEFAULT_PAGE = 1;
    const DEFAULT_AMOUNT = 15;

    /**
     * @param Request $request
     * @return array
     */
    public function getOffset(Request $request)
    {
        $page = $request->get(self::ATTRIBUTE_PAGE_NAME, null);
        $defaultOffset = array(
            'page' => $page ? $page : self::DEFAULT_PAGE,
            'amount' => self::DEFAULT_AMOUNT
        );

        return $defaultOffset;
    }

    /**
     * @return Paginator
     */
    public function getPaginator(Datagrid $datagrid)
    {
        $currentPage = $datagrid->getCriteria()->offset()->get('page');
        $perPageAmount = $datagrid->getCriteria()->offset()->get('amount');

        $elementsAmount = $datagrid->getCollection()->getAmount();
        $pageAmount = ceil($elementsAmount / $perPageAmount);

        $paginator = new Paginator();
        if ($pageAmount <= 1) {
            return $paginator;
        }

        for ($index = 1; $index <= $pageAmount; $index++) {
            $page = array(
                'index' => $index,
                'current' => ($index == $currentPage) ? true : false
            );

            $paginator->addPage($page);
        }

        return $paginator;
    }
}