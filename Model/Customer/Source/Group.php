<?php
namespace Smaex\CustomerGroupPayments\Model\Customer\Source;

use Magento\Framework\Exception\LocalizedException;

/**
 * Extends Magento’s customer group source model,
 * omits option for “all customer groups”.
 */
class Group extends \Magento\Customer\Model\Customer\Source\Group
{
    /**
     * @inheritDoc
     *
     * @throws LocalizedException
     */
    public function toOptionArray()
    {
        $customerGroups = [];

        if ($this->moduleManager->isEnabled('Magento_Customer')) {
            $groups = $this->groupRepository->getList(
                $this->searchCriteriaBuilder->create()
            );
            foreach ($groups->getItems() as $group) {
                $customerGroups[] = [
                    'label' => $group->getCode(),
                    'value' => $group->getId()
                ];
            }
        }
        return $customerGroups;
    }
}
