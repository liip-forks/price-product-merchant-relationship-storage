<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Business\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Calculation\Business\Calculator\CalculatorInterface;

class CanceledTotalCalculator implements CalculatorInterface
{

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $calculableObjectTransfer->requireTotals();

        $canceledTotal = $this->calculateItemTotalCanceledAmount($calculableObjectTransfer);
        $canceledTotal += $this->calculateOrderExpenseCanceledAmount($calculableObjectTransfer);

        $calculableObjectTransfer->getTotals()->setCanceledTotal($canceledTotal);

    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function calculateItemTotalCanceledAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $canceledTotal = 0;
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $canceledTotal += $itemTransfer->getCanceledAmount();
        }
        return $canceledTotal;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return int
     */
    protected function calculateOrderExpenseCanceledAmount(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $canceledTotal = 0;
        foreach ($calculableObjectTransfer->getExpenses() as $expenseTransfer) {
            $canceledTotal += $expenseTransfer->getCanceledAmount();
        }
        return $canceledTotal;
    }
}
