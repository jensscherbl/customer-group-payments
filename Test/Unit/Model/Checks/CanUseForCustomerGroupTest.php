<?php
namespace Smaex\CustomerGroupPayments\Test\Unit\Model\Checks;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Payment\Model\MethodInterface;
use Magento\Quote\Model\Quote;
use PHPUnit\Framework\MockObject\MockObject;
use Smaex\CustomerGroupPayments\Model\Checks\CanUseForCustomerGroup;

/**
 * @covers \Smaex\CustomerGroupPayments\Model\Checks\CanUseForCustomerGroup
 */
class CanUseForCustomerGroupTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CanUseForCustomerGroup
     */
    private $instance;

    /**
     * @var MockObject|CustomerInterface
     */
    private $mockCustomer;

    /**
     * @var MockObject|MethodInterface
     */
    private $mockPaymentMethod;

    /**
     * @var MockObject|Quote
     */
    private $mockQuote;

    /**
     * @param int    $customerGroup
     * @param mixed  $customerGroups
     * @param bool   $isApplicable
     *
     * @return void
     *
     * @dataProvider provideTestIsApplicable
     */
    public function testIsApplicable(int $customerGroup, $customerGroups, bool $isApplicable): void
    {
        $this->mockCustomer->method('getGroupId')->willReturn(
            $customerGroup
        );
        $this->mockPaymentMethod->method('getConfigData')->willReturn(
            $customerGroups
        );
        $this->assertSame(
            $isApplicable,
            $this->instance->isApplicable($this->mockPaymentMethod, $this->mockQuote)
        );
    }

    /**
     * @return array
     */
    public function provideTestIsApplicable(): array
    {
        return [
            [ 2, null,  true  ],
            [ 2, '2,3', true  ],
            [ 2, '',    false ],
            [ 2, '3',   false ]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mockCustomer      = $this->createMock(CustomerInterface::class);
        $this->mockPaymentMethod = $this->createMock(MethodInterface::class);
        $this->mockQuote         = $this->createMock(Quote::class);
        $this->instance          = new CanUseForCustomerGroup;

        $this->mockQuote->method('getCustomer')->willReturn(
            $this->mockCustomer
        );
    }
}
