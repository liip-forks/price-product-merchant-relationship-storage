<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Business;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\Product\Business\Exception\MissingProductException;
use Spryker\Zed\ProductCategory\Business\Exception\MissingCategoryNodeException;
use Spryker\Zed\ProductCategory\Business\Exception\ProductCategoryMappingExistsException;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;

interface ProductCategoryManagerInterface
{

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @return bool
     */
    public function hasProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale);

    /**
     * @param string $sku
     * @param string $categoryName
     * @param LocaleTransfer $locale
     *
     * @throws ProductCategoryMappingExistsException
     * @throws MissingProductException
     * @throws MissingCategoryNodeException
     * @throws PropelException
     *
     * @return int
     */
    public function createProductCategoryMapping($sku, $categoryName, LocaleTransfer $locale);

    /**
     * @param int $idCategory
     * @param LocaleTransfer $locale
     *
     * @return SpyProductCategoryQuery[]
     */
    public function getProductsByCategory($idCategory, LocaleTransfer $locale);

    /**
     * @param ProductAbstractTransfer $productAbstractTransfer
     *
     * @return SpyProductCategory[]
     */
    public function getCategoriesByProductAbstract(ProductAbstractTransfer $productAbstractTransfer);

    /**
     * @param int $idCategory
     * @param int $idProductAbstract
     *
     * @return SpyProductCategoryQuery
     */
    public function getProductCategoryMappingById($idCategory, $idProductAbstract);

    /**
     * @param int $idCategory
     * @param array $productIdsToAssign
     *
     * @throws PropelException
     *
     * @return void
     */
    public function createProductCategoryMappings($idCategory, array $productIdsToAssign);

    /**
     * @param int $idCategory
     * @param array $productIdsToUnAssign
     *
     * @return void
     */
    public function removeProductCategoryMappings($idCategory, array $productIdsToUnAssign);

    /**
     * @param $idCategory
     * @param array $productOrderList
     *
     * @throws PropelException
     *
     * @return void
     */
    public function updateProductMappingsOrder($idCategory, array $productOrderList);

    /**
     * @param int $idCategory
     * @param array $productPreConfigList
     *
     * @throws PropelException
     *
     * @return void
     */
    public function updateProductMappingsPreConfig($idCategory, array $productPreConfigList);

    /**
     * @param NodeTransfer $sourceNodeTransfer
     * @param NodeTransfer $destinationNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function moveCategoryChildrenAndDeleteNode(NodeTransfer $sourceNodeTransfer, NodeTransfer $destinationNodeTransfer, LocaleTransfer $localeTransfer);

    /**
     * @param CategoryTransfer $categoryTransfer
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return int
     */
    public function addCategory(CategoryTransfer $categoryTransfer, NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer);

    /**
     * @param int $idCategory
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function deleteCategoryRecursive($idCategory, LocaleTransfer $localeTransfer);

    /**
     * @param int $idCategoryNode
     * @param int $fkParentCategoryNode
     * @param bool $deleteChildren
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function deleteCategory($idCategoryNode, $fkParentCategoryNode, $deleteChildren, LocaleTransfer $localeTransfer);

}