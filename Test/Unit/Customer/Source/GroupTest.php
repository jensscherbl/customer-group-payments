<?php
namespace Smaex\CustomerGroupPayments\Test\Unit\Customer\Source;

use Magento\Customer\Api\Data\GroupInterface;
use Magento\Customer\Model\Customer;
use PHPUnit\Framework\MockObject\MockObject;
use Smaex\CustomerGroupPayments\Model\Customer\Source\Group;

/**
 * @covers \Smaex\CustomerGroupPayments\Model\Customer\Source\Group
 */
class GroupTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Group
     */
    private $instance;

    /**
     * @var MockObject|Customer\Source\Group
     */
    private $mockOrigin;

    /**
     * @param array $optionsOriginal
     * @param array $optionsExpected
     *
     * @return void
     *
     * @dataProvider provideTestToOptionArray
     */
    public function testToOptionArray(array $optionsOriginal, array $optionsExpected): void
    {
        $this->mockOrigin->method('toOptionArray')->willReturn(
            $optionsOriginal
        );
        $this->assertSame(
            $optionsExpected,
            $this->instance->toOptionArray()
        );
    }

    /**
     * @return array
     */
    public function provideTestToOptionArray(): array
    {
        $option1 = [ 'label' => 'Foo', 'value' => GroupInterface::CUST_GROUP_ALL   ];
        $option2 = [ 'label' => 'Bar', 'value' => GroupInterface::NOT_LOGGED_IN_ID ];

        return [
            [[                    ], [          ]],
            [[ $option1           ], [          ]],
            [[           $option2 ], [ $option2 ]],
            [[ $option1, $option2 ], [ $option2 ]]
        ];
    }

    /**
     * @inheritDoc
     */
    protected function setUp()
    {
        parent::setUp();

        $this->mockOrigin = $this->createMock(Customer\Source\Group::class);
        $this->instance   = new Group($this->mockOrigin);
    }
}
