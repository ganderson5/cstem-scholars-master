<?php

require_once __DIR__ . '/../src/init.php';
require_once __DIR__ . '/SchemaTest.php';

final class ApplicationModelTest extends SchemaTest
{
    private $validApp;

    protected function setUp(): void
    {
        parent::setUp();

        DB::pdo()->exec(
            "
            INSERT INTO User (name, email, isAdvisor, isReviewer, isAdmin) 
            VALUES ('Advisor', 'advisor@email.com', true, false, false);
        "
        );

        $this->validApp = [
            // Basic Details
            'name' => 'Assigned Name',
            'email' => 'valid@email.com',
            'title' => 'Valid Project Title',

            // Major & GPA
            'major' => 'Computer Science',
            'gpa' => '4.0',
            'graduationDate' => date('Y-m-d', strtotime('+1 year')),

            // Advisor Information
            'advisorName' => 'Ad Visor',
            'advisorEmail' => 'advisor@email.com',

            // Objective & Results
            'description' => 'Valid project description',
            'timeline' => 'Valid timeline',

            // Budget
            'justification' => 'Valid budget justification',
            'totalBudget' => 123.45,
            'requestedBudget' => '123.45',
            'fundingSources' => 'Valid sources',

            'terms' => 'agree'
        ];
    }

    protected function tearDown(): void
    {
    }

    public function testApplicationCanBeCreated()
    {
        $this->assertInstanceOf(Application::class, new Application());

        $app = new Application($this->validApp);

        $this->assertNull($app->name);

        $app->name = 'Student Name';
        $app->studentID = '007123456';
        $app->periodID = 1;
        $app->status = 'submitted';
        $app->budgetTable = json_encode([['item' => 'Budget Item 1', 'itemDesc' => '', 'itemCost' => 1.99]]);

        $this->assertCount(0, $app->errors());
        $this->assertTrue($app->save());
        $this->assertIsNumeric($app->id);

        $this->assertEquals(1, Application::count());
        $this->assertEquals('Student Name', Application::first()->name);
    }

    public function testApplicationTerms()
    {
        $this->assertInstanceOf(Application::class, new Application());

        $invalidApp = $this->validApp;
        unset($invalidApp['terms']);

        $app = new Application($invalidApp);
        $app->studentID = '007123456';
        $app->periodID = 1;
        $app->status = 'submitted';
        $app->budgetTable = json_encode([['item' => 'Budget Item 1', 'itemDesc' => '', 'itemCost' => 1.99]]);

        $this->assertFalse($app->save());
        $this->assertNull($app->id);
        $this->assertCount(1, $app->errors());

        $this->assertEquals(0, Application::count());
    }

    public function testApplicationNullStatus()
    {
        $this->expectException(InvalidArgumentException::class);

        $app = new Application($this->validApp);
        $app->save();
    }

    public function testApplicationInvalidStatus()
    {
        $this->expectException(InvalidArgumentException::class);

        $app = new Application($this->validApp);
        $app->status = 'invalid_status';
        $app->save();
    }
}
