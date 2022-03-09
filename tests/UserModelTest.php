<?php

require_once __DIR__ . '/../src/init.php';
require_once __DIR__ . '/SchemaTest.php';

final class UserModelTest extends SchemaTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
    }

    protected function tearDown(): void
    {
    }

    public function testUserCanBeCreated()
    {
        $this->assertInstanceOf(User::class, new User());

        $user = new User(
            [
                'name' => 'Alice Archer',
                'email' => 'alice@example.edu',
                'isAdmin' => true
            ]
        );

        $this->assertCount(0, $user->errors());
        $this->assertTrue($user->save());
        $this->assertEquals(1, User::count());

        $user = new User();
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isAdvisor());
        $this->assertFalse($user->isReviewer());
        $this->assertTrue($user->isStudent());
    }

    public function testCurrentUser()
    {
        $user = new User();
        $user->email = 'alice@example.edu';
        $user->name = 'Alice Archer';
        $user->isAdmin = true;
        $user->isReviewer = true;
        $user->save();

        $this->assertNull(User::current());

        $_SESSION['id'] = '00100001';
        $_SESSION['name'] = 'Student Name';
        $_SESSION['email'] = 'student@example.edu';

        $user = User::current();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('00100001', $user->id);
        $this->assertEquals('Student Name', $user->name);
        $this->assertEquals('student@example.edu', $user->email);
        $this->assertFalse($user->isAdmin());
        $this->assertFalse($user->isAdvisor());
        $this->assertFalse($user->isReviewer());
        $this->assertTrue($user->isStudent());
        $this->assertEquals(['student'], $user->roles());

        $_SESSION['id'] = '00100001';
        $_SESSION['name'] = 'Alice Archer';
        $_SESSION['email'] = 'alice@example.edu';

        $user = User::current();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('00100001', $user->id);
        $this->assertEquals('Alice Archer', $user->name);
        $this->assertEquals('alice@example.edu', $user->email);
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isAdvisor());
        $this->assertTrue($user->isReviewer());
        $this->assertFalse($user->isStudent());
        $this->assertContains('reviewer', $user->roles());
        $this->assertContains('admin', $user->roles());
        $this->assertCount(2, $user->roles());
        $this->assertTrue($user->hasRole('admin'));
        $this->assertTrue($user->hasRole('reviewer'));
        $this->assertFalse($user->hasRole('advisor'));
        $this->assertFalse($user->hasRole('student'));
    }

    public function testNotLoggedIn()
    {
        $this->assertNull(User::current());

        $_SESSION['id'] = '00100001';
        $this->assertNull(User::current());

        $_SESSION['name'] = 'Student Name';
        $this->assertNull(User::current());

        $_SESSION['email'] = 'student@example.edu';
        $this->assertInstanceOf(User::class, User::current());
    }

    public function testUserSave()
    {
        $user = new User(
            [
                'name' => 'Robert Baker',
                'email' => 'robert@example.edu',
                'isAdmin' => true,
                'isReviewer' => false
            ]
        );

        $this->assertTrue($user->save());

        $user = User::first();
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isReviewer());
        $this->assertFalse($user->isAdvisor());
        $this->assertFalse($user->isStudent());
    }
}
