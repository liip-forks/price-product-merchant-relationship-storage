<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\Propel\PropelFilterCriteria;

/**
 * @method \Spryker\Zed\Customer\Persistence\CustomerPersistenceFactory getFactory()
 */
class CustomerRepository extends AbstractRepository implements CustomerRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function getCustomerCollection(CustomerCollectionTransfer $customerCollectionTransfer): CustomerCollectionTransfer
    {
        $customerQuery = $this->getFactory()
            ->createSpyCustomerQuery();

        $customerQuery = $this->applyFilterToQuery($customerQuery, $customerCollectionTransfer->getFilter());
        $customerQuery = $this->applyPagination($customerQuery, $customerCollectionTransfer->getPagination());

        $customerQuery = $this->buildQueryFromCriteria($customerQuery);
        $customerEntityTransfers = $customerQuery->find();

        $customerCollectionTransfer = $this->hydrateCustomerListWithCustomers($customerCollectionTransfer, $customerEntityTransfers);

        return $customerCollectionTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerQuery $spyCustomerQuery
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function applyFilterToQuery(SpyCustomerQuery $spyCustomerQuery, ?FilterTransfer $filterTransfer): SpyCustomerQuery
    {
        $criteria = new Criteria();
        if ($filterTransfer !== null) {
            $criteria = (new PropelFilterCriteria($filterTransfer))
                ->toCriteria();
        }

        $spyCustomerQuery->mergeWith($criteria);

        return $spyCustomerQuery;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerQuery $spyCustomerQuery
     * @param \Generated\Shared\Transfer\PaginationTransfer|null $paginationTransfer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    protected function applyPagination(SpyCustomerQuery $spyCustomerQuery, PaginationTransfer $paginationTransfer = null): SpyCustomerQuery
    {
        if (empty($paginationTransfer)) {
            return $spyCustomerQuery;
        }

        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        $paginationModel = $spyCustomerQuery->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults());
        $paginationTransfer->setFirstIndex($paginationModel->getFirstIndex());
        $paginationTransfer->setLastIndex($paginationModel->getLastIndex());
        $paginationTransfer->setFirstPage($paginationModel->getFirstPage());
        $paginationTransfer->setLastPage($paginationModel->getLastPage());
        $paginationTransfer->setNextPage($paginationModel->getNextPage());
        $paginationTransfer->setPreviousPage($paginationModel->getPreviousPage());

        return $paginationModel->getQuery();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerListTransfer
     * @param array $customerEntityTransfers
     *
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    public function hydrateCustomerListWithCustomers(CustomerCollectionTransfer $customerListTransfer, array $customerEntityTransfers): CustomerCollectionTransfer
    {
        $customers = new ArrayObject();

        foreach ($customerEntityTransfers as $customerEntityTransfer) {
            $customerTransfer = $this->getFactory()
                ->createCustomerMapper()
                ->mapCustomerEntityToCustomer($customerEntityTransfer);
            $customers->append($customerTransfer);
        }

        return $customerListTransfer->setCustomers($customers);
    }
}
