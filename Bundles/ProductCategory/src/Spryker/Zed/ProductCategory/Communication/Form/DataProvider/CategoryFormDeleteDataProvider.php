<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Communication\Form\DataProvider;

use Spryker\Zed\ProductCategory\Communication\Form\CategoryFormDelete;

class CategoryFormDeleteDataProvider extends CategoryFormEditDataProvider
{

    /**
     * @param int|null $idCategory
     *
     * @return array
     */
    public function getData($idCategory)
    {
        $data = parent::getData($idCategory);
        $data[CategoryFormDelete::FIELD_FK_PARENT_CATEGORY_NODE] = null;

        return $data;
    }

}