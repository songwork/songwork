<?php
require_once 'test.php';

class TagTest extends PHPUnit_Framework_TestCase
	{

	function setUp() { shell_exec('sh regen-tests.sh'); }

	function testTagGet()
		{
		$x = new Tag(1);
		$this->assertEquals('words', $x->name());
		}

	function testTagAdd()
		{
		$name = 'fresh';
		$x = new Tag(false);
		$set = array('name' => $name);
		$new_id = $x->add($set);
		$x = new Tag($new_id);
		$this->assertEquals($name, $x->name());
		}

	function testTagAddDupe()
		{
		$x = new Tag(false);
		$set = array('name' => 'words');
		$this->assertEquals(1, $x->add($set)); 
		}

	function testAll()
		{
		$y = Tag::popular();
		$this->assertType('array', $y);
		$this->assertEquals(3, count($y));
		$x = array_shift($y);
		$this->assertType('Tag', $x);
		$this->assertEquals('words', $x->name());
		}

	function testDocuments()
		{
		$x = new Tag(1);
		$y = $x->documents();
		$this->assertEquals(3, count($y));
		$z = array_shift($y);
		$this->assertType('Document', $z);
		$this->assertEquals(3, $z->id);
		}

	function testTagOutOfRange()
		{
		$y = Document::with_tag(999);
		$this->assertType('array', $y);
		$this->assertEquals(0, count($y));
		}

	function testDocTags()
		{
		$x = new Document(1);
		$y = $x->tags();
		$this->assertType('array', $y);
		$this->assertEquals(2, count($y));
		$x = array_shift($y);
		$this->assertType('Tag', $x);
		}

	function testDocAddTag()
		{
		$x = new Document(1);
		$x->add_tag(3);
		$y = $x->tags();
		$this->assertEquals(3, count($y));
		}

	function testDocDelTag()
		{
		$x = new Document(1);
		$x->remove_tag(2);
		$y = $x->tags();
		$this->assertEquals(1, count($y));
		}

	function testDocTagless()
		{
		$x = new Document(2);
		$x->remove_tag(1);
		$y = Document::tagless();
		$this->assertEquals(1, count($y));
		$z = array_shift($y);
		$this->assertType('Document', $z);
		$this->assertEquals(2, $z->id);
		}

	}
?>
