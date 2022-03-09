<?php

require_once __DIR__ . '/../src/init.php';
require_once __DIR__ . '/SchemaTest.php';

final class ReviewModelTest extends SchemaTest
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
    }

    public function testPeriodCanBeCreated()
    {
        $this->assertInstanceOf(Review::class, new Review());

        $review = new Review(
            [
                'q1' => '0',
                'q2' => '0',
                'q3' => '0',
                'q4' => '0',
                'q5' => '0',
                'q6' => '0',
                'comments' => '',
                'fundingRecommended' => true
            ]
        );

        $review->applicationID = 0;
        $review->periodID = 0;
        $review->submitted = true;

        $this->assertCount(0, $review->errors());
        $this->assertTrue($review->save());
        $this->assertIsNumeric($review->id);
        $this->assertEquals(1, Review::count());
    }

    public function testInvalidPeriod()
    {
        $review = new Review(
            [
                'q1' => '99',
                'q2' => '-99'
            ]
        );

        $this->assertCount(7, $review->errors());
    }
}
