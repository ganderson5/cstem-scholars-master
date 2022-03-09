<?php

require_once __DIR__ . '/../src/init.php';

use PHPUnit\Framework\TestCase;
use Respect\Validation\ValidatorFunction as v;

class TestModel extends Model
{
    // public $bar;

    function __construct($form = [])
    {
        $this->fillable = ['foo', 'bar'];
        $this->guarded = ['baz'];

        // $this->bar should not be overwritten by Model::__construct();
        $this->bar = 'unfilled';
        parent::__construct($form);
    }
}

class ValidatedModel extends Model
{
    protected static $table = 'validated_model';

    function __construct($form = [])
    {
        $this->fillable = [
            'foo' => v::number()->setName('foo'),
            'bar' => fn($v) => ($v == 'valid') ? null : 'bar is not valid'
        ];

        parent::__construct($form);
    }
}

class CompositeKeyModel extends Model
{
    protected static $primaryKey = ['foo', 'bar'];
    protected $fillable = ['bar', 'baz'];
}

class SimpleModel extends Model
{
    protected $fillable = ['foo', 'bar'];
}

class BadModel extends Model
{
    // Both $fillable and $guarded contain 'bar', which should not be allowed
    protected $fillable = ['foo', 'bar'];
    protected $guarded = ['bar', 'baz'];
}

class NullModel extends Model
{
}

final class ModelTest extends TestCase
{
    protected function setUp(): void
    {
        DB::configure('sqlite::memory:', null, null, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $query = '
            CREATE TABLE `TestModel` (
                `id` integer PRIMARY KEY AUTOINCREMENT,
                `foo` varchar(10) DEFAULT NULL,
                `bar` int DEFAULT NULL,
                `baz` varchar(10) DEFAULT NULL
            );

            CREATE TABLE `validated_model` (
                `id` integer PRIMARY KEY AUTOINCREMENT,
                `foo` int DEFAULT NULL,
                `bar` varchar(10) DEFAULT NULL
            );

            CREATE TABLE `CompositeKeyModel` (
                `foo` integer NOT NULL,
                `bar` varchar(10) NOT NULL,
                `baz` varchar(10) NOT NULL,
                PRIMARY KEY (foo, bar)
            );
        ';

        DB::pdo()->exec($query);
    }

    protected function tearDown(): void
    {
    }

    public function testModelCanBeCreated()
    {
        $this->assertInstanceOf(Model::class, new NullModel());
    }

    public function testFillableAndGuardedDoNotIntersect()
    {
        ini_set('assert.exception', true);
        $this->expectException(AssertionError::class);
        new BadModel();
    }

    public function testModelCanBeFilled()
    {
        // TODO: Test filling guarded columns
        $m = new TestModel(['foo' => '1', 'baz' => '3']);

        $this->assertEquals('1', $m->foo);
        $this->assertEquals('unfilled', $m->bar);
        $this->assertEquals(null, $m->baz);

        $m->baz = '3';
        $this->assertEquals('3', $m->baz);

        $m = new SimpleModel(['foo' => '1']);

        $this->assertEquals('1', $m->foo);
        $this->assertEquals(null, $m->bar);

        $m->bar = '2';
        $this->assertEquals('2', $m->bar);

        $m = new NullModel();
        $this->assertInstanceOf(Model::class, $m);
    }

    public function testModelCanBeSavedAndRetrieved()
    {
        $m = new TestModel(['foo' => '1', 'bar' => 2]);
        $m->baz = '3';

        $this->assertTrue($m->save());
        $this->assertTrue($m->id != null);

        $m = TestModel::get($m->id);
        $this->assertEquals('1', $m->foo);
        $this->assertEquals('2', $m->bar);
        $this->assertEquals('3', $m->baz);

        (new TestModel(['foo' => '3', 'bar' => '4']))->save();
        $this->assertCount(2, TestModel::all());
    }

    public function testManyModelsCanBeRetrieved()
    {
        $ms = [
            new TestModel(['foo' => '1', 'bar' => 5]),
            new TestModel(['foo' => '2', 'bar' => 6]),
            new TestModel(['foo' => '3', 'bar' => 7]),
            new TestModel(['foo' => '4', 'bar' => 8])
        ];

        $id = null;

        foreach ($ms as $m) {
            $id = $m->save();
        }

        $this->assertEquals(4, TestModel::count());
        $this->assertCount(4, TestModel::all());
        $this->assertCount(2, TestModel::all('bar > 6'));

        $m = TestModel::all('foo = 2');
        $this->assertCount(1, $m);
        $this->assertEquals('2', $m[0]->foo);
        $this->assertEquals(6, $m[0]->bar);
    }

    public function testModelCanBeUpdated()
    {
        $m = new TestModel(['foo' => '1', 'bar' => 2]);
        $this->assertTrue($m->save());
        $m->foo = '3';
        $this->assertTrue($m->save());

        $id1 = $m->id;

        $m = TestModel::get($id1);
        $this->assertEquals('3', $m->foo);
        $m->foo = '4';
        $m->save();

        $id2 = $m->id;

        $m = TestModel::get($id2);
        $this->assertEquals('4', $m->foo);
        $this->assertEquals($id1, $id2);
    }

    public function testModelValidation()
    {
        $validModel = new ValidatedModel(['foo' => '123', 'bar' => 'valid']);
        $invalidModel1 = new ValidatedModel(['foo' => 'nope', 'bar' => 'valid']);
        $invalidModel2 = new ValidatedModel(['foo' => '123', 'bar' => 'not valid']);
        $invalidModel3 = new ValidatedModel(['foo' => 'nope', 'bar' => 'not valid']);

        $this->assertTrue($validModel->isValid());
        $this->assertFalse($invalidModel1->isValid());
        $this->assertFalse($invalidModel2->isValid());
        $this->assertFalse($invalidModel3->isValid());

        $this->assertEmpty($validModel->errors());
        $this->assertCount(1, $invalidModel1->errors());
        $this->assertCount(1, $invalidModel2->errors());
        $this->assertEquals('bar is not valid', $invalidModel2->errors()['bar'] ?? null);
        $this->assertCount(2, $invalidModel3->errors());

        $this->assertTrue($validModel->save());
        $this->assertFalse($invalidModel1->save());
        $this->assertFalse($invalidModel2->save());
        $this->assertFalse($invalidModel3->save());

        $this->assertCount(1, ValidatedModel::all());
        $m = ValidatedModel::first();
        $this->assertEquals('123', $m->foo);
        $this->assertEquals('valid', $m->bar);
    }

    public function testCompositePrimaryKey()
    {
        $m = new CompositeKeyModel(['bar' => 'my_key', 'baz' => 'testing']);
        $m->foo = 99;
        $this->assertTrue($m->save());
        $this->assertCount(1, CompositeKeyModel::all());

        $this->assertEquals(null, CompositeKeyModel::get(['foo' => -1, 'bar' => 'bad_key']));

        $m = CompositeKeyModel::get(['foo' => 99, 'bar' => 'my_key']);
        $this->assertInstanceOf(CompositeKeyModel::class, $m);
        $this->assertEquals(99, $m->foo);
        $this->assertEquals('my_key', $m->bar);
        $this->assertEquals('testing', $m->baz);

        $m->baz = 'testing update';
        $this->assertTrue($m->save());
        $m = CompositeKeyModel::get(['foo' => 99, 'bar' => 'my_key']);
        $this->assertInstanceOf(CompositeKeyModel::class, $m);
        $this->assertEquals(99, $m->foo);
        $this->assertEquals('my_key', $m->bar);
        $this->assertEquals('testing update', $m->baz);

        $this->assertFalse(CompositeKeyModel::deleteByKey(['foo' => -1, 'bar' => 'bad_key']));
        $this->assertCount(1, CompositeKeyModel::all());

        $this->assertTrue(CompositeKeyModel::deleteByKey(['foo' => 99, 'bar' => 'my_key']));
        $this->assertCount(0, CompositeKeyModel::all());
    }

    public function testAllNonexistentReturnsEmptyArray()
    {
        $this->assertEquals([], TestModel::all('1=0'));
    }

    public function testModelCanBeDeleted()
    {
        (new TestModel(['foo' => '1', 'bar' => 2]))->save();
        (new TestModel(['foo' => '3', 'bar' => 4]))->save();
        (new TestModel(['foo' => '5', 'bar' => 6]))->save();
        (new TestModel(['foo' => '7', 'bar' => 6]))->save();

        $m = new TestModel(['foo' => '9', 'bar' => 6]);
        $m->save();

        $this->assertEquals(5, TestModel::count());
        TestModel::deleteByKey($m->id);
        $this->assertEquals(4, TestModel::count());
        TestModel::delete('1=1');
        $this->assertEquals(0, TestModel::count());
    }

    public function testModelCanSelectFirst()
    {
        (new TestModel(['foo' => '1', 'bar' => 2]))->save();
        (new TestModel(['foo' => '3', 'bar' => 4]))->save();
        (new TestModel(['foo' => '5', 'bar' => 6]))->save();
        (new TestModel(['foo' => '7', 'bar' => 6]))->save();
        (new TestModel(['foo' => '9', 'bar' => 6]))->save();

        $m = TestModel::first('1=1 ORDER BY id');
        $this->assertEquals('1', $m->foo);
        $this->assertEquals('2', $m->bar);

        $m = TestModel::first('bar = 6 ORDER BY foo');
        $this->assertEquals('5', $m->foo);
        $this->assertEquals('6', $m->bar);

        $m = TestModel::first('1=0');
        $this->assertEquals(null, $m);
    }
}
