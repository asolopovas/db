<?php

namespace Tests\Unit;

use Tests\TestCase;

class OrderDiscountPercentTest extends TestCase
{
    private function renderPaymentDetails(float $totalPrice, float $discount, float $vat = 20): string
    {
        $order = new \stdClass();
        $order->reverse_charge = false;
        $order->new_build = false;

        $company = new \stdClass();
        $company->bank = null;

        return view('components.order-payment-details', [
            'paid' => 0,
            'payments' => collect([]),
            'proforma' => false,
            'due_amount' => 0,
            'dueNow' => 0,
            'tax_deductions' => collect([]),
            'payment_terms' => null,
            'company' => $company,
            'totalPrice' => $totalPrice,
            'grand_total' => round(($totalPrice - $discount) * (1 + $vat / 100), 2),
            'order' => $order,
            'discount' => $discount,
            'vat_total' => round(($totalPrice - $discount) * $vat / 100, 2),
            'vat' => $vat,
        ])->render();
    }

    /** @test */
    public function pdf_discount_line_includes_percentage()
    {
        $html = $this->renderPaymentDetails(10000, 1000);

        $this->assertStringContainsString(
            'Discount (10.00%):',
            $html,
            'PDF should display discount with percentage like Discount (10.00%):'
        );
    }

    /** @test */
    public function pdf_discount_percentage_is_correctly_formatted()
    {
        // 100 / 3000 = 3.33%
        $html = $this->renderPaymentDetails(3000, 100);

        $this->assertStringContainsString(
            'Discount (3.33%):',
            $html,
            'PDF should display fractional percentages with 2 decimal places'
        );
    }

    /** @test */
    public function pdf_hides_discount_when_zero()
    {
        $html = $this->renderPaymentDetails(5000, 0);

        $this->assertStringNotContainsString('Discount', $html);
    }

    /** @test */
    public function pdf_discount_shows_discounted_total()
    {
        $html = $this->renderPaymentDetails(10000, 2500);

        $this->assertStringContainsString('Discount (25.00%):', $html);
        $this->assertStringContainsString('Discounted Total:', $html);
    }

    /** @test */
    public function pdf_discount_percentage_matches_frontend_formula()
    {
        $totalPrice = 5000;
        $discount = 250;

        // This is the formula used in the blade template:
        // $discount_percent = $totalPrice > 0 ? round($discount / $totalPrice * 100, 2) : 0
        $expectedPercent = round($discount / $totalPrice * 100, 2);

        // Same formula as frontend: (state.discount / subtotal) * 100
        $this->assertEquals(5.0, $expectedPercent);

        $html = $this->renderPaymentDetails($totalPrice, $discount);
        $this->assertStringContainsString('Discount (5.00%):', $html);
    }
}
