<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Checkout\Test\Constraint;

use Magento\Checkout\Test\Page\CheckoutOnepage;
use Magento\Checkout\Test\TestStep\FillShippingAddressStep;
use Magento\Mtf\Client\BrowserInterface;
use Magento\Mtf\Constraint\AbstractConstraint;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepFactory;

/**
 * Asserts that shipping methods are present on checkout page.
 */
class AssertShippingMethodsSuccessEstimate extends AbstractConstraint
{
    /**
     * Asserts that shipping methods are present on checkout page.
     *
     * @param CheckoutOnepage $checkoutOnepage
     * @param BrowserInterface $browser
     * @return void
     */
    public function processAssert(
        CheckoutOnepage $checkoutOnepage,
        BrowserInterface $browser
    ) {
        if ($this->shouldOpenCheckout($checkoutOnepage, $browser)) {
            $checkoutOnepage->open();
        }

        \PHPUnit_Framework_Assert::assertFalse(
            $checkoutOnepage->getShippingMethodBlock()->isErrorPresent(),
            'Shipping estimation error is present.'
        );

        $methods = $checkoutOnepage->getShippingMethodBlock()->getAvailableMethods();
        \PHPUnit_Framework_Assert::assertNotEmpty(
            $methods,
            'No shipping methods are present.'
        );
    }

    /**
     * Should open checkout page or not.
     *
     * @param CheckoutOnepage  $checkoutOnepage
     * @param BrowserInterface $browser
     * @return bool
     */
    private function shouldOpenCheckout(CheckoutOnepage $checkoutOnepage, BrowserInterface $browser)
    {
        $result = true;

        foreach (['checkout/', $checkoutOnepage::MCA] as $path) {
            $length = strlen($path);
            if (substr($browser->getUrl(), -$length) === $path) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return "Shipping methods are present on checkout page.";
    }
}
