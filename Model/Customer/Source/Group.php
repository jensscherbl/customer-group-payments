<?php
namespace Smaex\CustomerGroupPayments\Model\Customer\Source;

use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Model\Customer;

/**
 * Extends Magento’s customer group source model,
 * omits option for “all customer groups”.
 */
class Group implements \Magento\Customer\Model\Customer\Source\GroupSourceInterface
{
    /**
     * @var Customer\Source\Group
     */
    private $origin;

    /**
     * Constructor.
     *
     * @param Customer\Source\Group $origin
     *
     * @codeCoverageIgnore
     */
    public function __construct(Customer\Source\Group $origin)
    {
        $this->origin = $origin;
    }

    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $options = $this->origin->toOptionArray();

        foreach ($options as $index => $option) {
            if ((int) $option['value'] === GroupInterface::CUST_GROUP_ALL) {
                unset($options[$index]);
                break;
            }
        }
        return array_values($options);
    }
}
