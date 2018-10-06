<?php
namespace Smaex\CustomerGroupPayments\Model\Checks;

use Magento\Customer\Model\Session;
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
     * @var Session
     */
    private $customerSession;

    /**
     * Constructor.
     *
     * @param Session $customerSession
     *
     * @codeCoverageIgnore
     */
    public function __construct(Session $customerSession)
    {
        $this->customerSession = $customerSession;
    }

    /**
     * @inheritDoc
     */
    public function isApplicable(MethodInterface $paymentMethod, Quote $quote)
    {
        $isApplicable   = true;
        $customerGroup  = $this->customerSession->getCustomerGroupId();
        $customerGroups = $paymentMethod->getConfigData(self::CUSTOMER_GROUPS);

        if (is_string($customerGroups)) {
            $customerGroups = explode(
                ',',
                $customerGroups
            );
            if (in_array($customerGroup, $customerGroups) === false) {
                $isApplicable = false;
            }
        }
        return $isApplicable;
    }
}
