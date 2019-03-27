# Magento 2: Customer Group Payments

Restricts payment methods to specific customer groups in [Magento 2][1].

## Intro

Adding additional checks to payment methods – to decide if a certain payment method is applicable to a certain customer or not – is pretty easy and straight forward in Magento 2.

Magento [provides a rather simplistic interface][2] for custom payment method checks, and [uses a composite check][3] to process these individual checks. Adding a custom check is therefore just a matter of injecting it into Magento’s composite check via dependency injection.

This extension implements such a custom check to decide if a certain payment method is applicable to a customer based on the customer group, along with a corresponding system configuration field for payment methods.

![Screenshot: Magento system configuration field for payment methods that restricts a payment method to selected customer groups.][4]

## Prerequisite

Unfortunately, there’s currently a limitation in Magento’s code regarding this mechanism that requires us to use a small workaround, so this extension depends on another extension providing this workaround. See [smaex/additional-payment-checks][5] for more information.

## How to install

Simply require the extension via [Composer][6].

```sh
$ composer require smaex/customer-group-payments ^2.0
```

Finally, enable the module via [Magento’s CLI][7].

```sh
$ magento module:enable Smaex_CustomerGroupPayments
```

## How to use

While the extension provides you with all the tools you need to restrict payment methods to specific customer groups, it doesn’t do anything without some custom configuration on your part. That’s because adding system configuration fields [is done via XML configuration][8] in Magento.

To properly configure your payment methods, the best way going forward is probably to [set up your own custom module][9] (e.g., `Acme_Payment`) under `app/code`.

Declaring a dependency on `Magento_Payment` and `Smaex_CustomerGroupPayments` in your `module.xml` is kinda mandatory, dependencies on other modules like `Magento_OfflinePayments` depend on the payment methods actually used in your project.

```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:framework:Module/etc/module.xsd">
    <module name="Acme_Payment" setup_version="1.0.0">
        <sequence>
            <module name="Magento_OfflinePayments"/>
            <module name="Magento_Payment"/>
            <module name="Smaex_CustomerGroupPayments"/>
        </sequence>
    </module>
</config>
```

The next and already final step is then to provide your own `system.xml` under `etc/adminhtml` in your custom module and extend the existing configuration for each payment method used in your project.

```xml
<config xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="urn:magento:module:Magento_Config:etc/system_file.xsd">
    <system>
        <section id="payment">
            <!--
            Check / Money Order
            -->
            <group id="checkmo">
                <field id="customer_groups" type="multiselect" translate="label comment" showInDefault="1" showInWebsite="1" showInStore="1" canRestore="1" sortOrder="52">
                    <include path="Smaex_CustomerGroupPayments::system/customer_groups.xml"/>
                </field>
            </group>
            <!--
            Cash On Delivery Payment
            -->
            <group id="cashondelivery">
                <field id="customer_groups" type="multiselect" translate="label comment" showInDefault="1" showInWebsite="1" showInStore="1" canRestore="1" sortOrder="52">
                    <include path="Smaex_CustomerGroupPayments::system/customer_groups.xml"/>
                </field>
            </group>
        </section>
    </system>
</config>
```

## Alternative

If ~~you’re a lazy sloth~~ this looks like too much work (i.e., XML configuration), there’s also [another extension tackling the same problem from a very different angle][10].

## We’re hiring!

Wanna work for [one of Germany’s leading Magento partners][11]? With agile methods, small teams and big clients? We’re currently looking for experienced ~~masochists~~ **PHP & Magento developers in Munich**. Sounds interesting? Just drop me a line via j.scherbl@techdivision.com

 [1]: https://github.com/magento/magento2
 [2]: https://github.com/magento/magento2/blob/2.3/app/code/Magento/Payment/Model/Checks/SpecificationInterface.php
 [3]: https://github.com/magento/magento2/blob/2.3/app/code/Magento/Payment/Model/Checks/Composite.php
 [4]: https://user-images.githubusercontent.com/1640033/47964356-25660200-e039-11e8-81dc-7ccc9785c2bc.png
 [5]: https://github.com/smaex/additional-payment-checks
 [6]: https://getcomposer.org
 [7]: https://devdocs.magento.com/guides/v2.3/install-gde/install/cli/install-cli-subcommands-enable.html
 [8]: https://github.com/magento/magento2-samples/blob/master/sample-module-payment-gateway/etc/adminhtml/system.xml
 [9]: https://devdocs.magento.com/guides/v2.3/architecture/archi_perspectives/components/modules/mod_intro.html
[10]: https://github.com/galacticlabs/customer-group-payment-filters
[11]: https://www.techdivision.com/karriere/offene-stellen/magento-developer-m-w.html
