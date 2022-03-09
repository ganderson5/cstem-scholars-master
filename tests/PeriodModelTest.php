<?php

require_once __DIR__ . '/../src/init.php';
require_once __DIR__ . '/SchemaTest.php';

final class PeriodModelTest extends SchemaTest
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
        $this->assertInstanceOf(Period::class, new Period());

        $period = new Period(
            [
                'beginDate' => '2020-05-01',
                'deadline' => '2020-06-01',
                'advisorDeadline' => '2020-06-15',
                'budget' => 1000000
            ]
        );

        $this->assertCount(0, $period->errors());
        $this->assertTrue($period->save());
        $this->assertIsNumeric($period->id);
        $this->assertEquals(1, Period::count());
    }

    public function testCurrentPeriod()
    {
        $period = new Period(
            [
                'beginDate' => date('Y-m-d', strtotime('yesterday')),
                'deadline' => date('Y-m-d', strtotime('tomorrow')),
                'advisorDeadline' => date('Y-m-d', strtotime('tomorrow')),
                'budget' => 1000000
            ]
        );

        $this->assertCount(0, $period->errors());
        $this->assertTrue($period->save());

        $currentPeriod = Period::current();
        $this->assertInstanceOf(Period::class, $currentPeriod);
        $this->assertEquals(1000000, $currentPeriod->budget);
    }

    public function testCurrentPeriodForAdvisors()
    {
        $period = new Period(
            [
                'beginDate' => date('Y-m-d', strtotime('yesterday')),
                'deadline' => date('Y-m-d', strtotime('tomorrow')),
                'advisorDeadline' => date('Y-m-d', strtotime('tomorrow')),
                'budget' => 1000000
            ]
        );

        $this->assertCount(0, $period->errors());
        $this->assertTrue($period->save());

        $currentPeriod = Period::currentForAdvisors();
        $this->assertInstanceOf(Period::class, $currentPeriod);
        $this->assertEquals(1000000, $currentPeriod->budget);
    }

    public function testInvalidPeriod()
    {
        $period = new Period(
            [
                'beginDate' => '2020-06-01',
                'deadline' => '2020-05-01',
                'advisorDeadline' => '2020-04-15',
                'budget' => -1
            ]
        );

        $this->assertCount(3, $period->errors());
    }
}
