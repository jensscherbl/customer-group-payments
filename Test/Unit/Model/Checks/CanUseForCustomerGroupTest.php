<?php
namespace Smaex\CustomerGroupPayments\Test\Unit\Model\Checks;

use Magento\Customer\Model\Session;
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
     * @var MockObject|Session
     */
    private $mockCustomerSession;

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
        $this->mockCustomerSession->method('getCustomerGroupId')->willReturn(
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

        $this->mockCustomerSession = $this->createMock(Session::class);
        $this->mockPaymentMethod   = $this->createMock(MethodInterface::class);
        $this->mockQuote           = $this->createMock(Quote::class);
        $this->instance            = new CanUseForCustomerGroup($this->mockCustomerSession);
    }
}
