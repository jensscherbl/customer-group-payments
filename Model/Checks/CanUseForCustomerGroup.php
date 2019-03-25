<?php
namespace Smaex\CustomerGroupPayments\Model\Checks;

use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Model\Quote;

/**
 * Checks if a given payment method is applicable
 * based on the customer’s customer group.
 */
class CanUseForCustomerGroup implements \Magento\Payment\Model\Checks\SpecificationInterface
{
    const CUSTOMER_GROUPS = 'customer_groups';

    /**
     * @inheritDoc
     */
    public function isApplicable(MethodInterface $paymentMethod, Quote $quote)
    {
        $isApplicable   = true;
        $customerGroups = $paymentMethod->getConfigData(self::CUSTOMER_GROUPS);

        if (is_string($customerGroups)) {
            $customerGroup  = $quote->getCustomer()->getGroupId();
            $customerGroups = explode(
                ',',
                $customerGroups
            );
            if (in_array((string) $customerGroup, $customerGroups, true) === false) {
                $isApplicable = false;
            }
        }
        return $isApplicable;
    }
}
