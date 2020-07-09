<?php

use Strukt\Console\Color;

class CommandTest extends PHPUnit\Framework\TestCase{

	public function setUp():void{

		$this->app = new Strukt\Console\Application();
		$this->app->add(new Command\DoctrineGenerateEntities);

		$name = "Strukt Console";
		$isWin = $this->app->isWindows();
		$this->$name = sprintf(($isWin)?"\n%s\n":"\033[1;32m%s\n%s\033[0m\n", $name, str_repeat("=", strlen($name)));
	}

	public function testRunFullValidCommand(){

		$mysqlCmd = "console orm:convert-mapping --from-database --namespace Payroll\AuthModule\Model xml app/src/Payroll/AuthModule/Model";

		$result = $this->app->run(explode(" ", $mysqlCmd));
		$respLines = explode("\n", trim((string)$result));
		$hash = ltrim(end($respLines), "\033[0m");
		$expected = 'from-db:ns[Payroll\AuthModule\Model]:type[xml]:path[app/src/Payroll/AuthModule/Model]';
		
		$this->assertEquals($expected, $hash);
	}

	public function testWithoutBooleanSwithFromDataBase(){

		$mysqlCmd = "console orm:convert-mapping --namespace Payroll\AuthModule\Model xml app/src/Payroll/AuthModule/Model";

		$result = $this->app->run(explode(" ", $mysqlCmd));
		$respLines = explode("\n", trim((string)$result));
		$hash = ltrim(end($respLines), "\033[0m");
		$expected = "ns[Payroll\AuthModule\Model]:type[xml]:path[app/src/Payroll/AuthModule/Model]";
		
		$this->assertEquals($expected, $hash);
	}

	public function testWithoutInputSwitchNamespace(){

		$mysqlCmd = "console orm:convert-mapping xml app/src/Payroll/AuthModule/Model";

		$result = $this->app->run(explode(" ", $mysqlCmd));
		$respLines = explode("\n", trim((string)$result));
		$hash = ltrim(end($respLines), "\033[0m");
		
		$this->assertEquals("type[xml]:path[app/src/Payroll/AuthModule/Model]", $hash);
	}

	public function testValidationWrongFileGenerationType(){

		$mysqlCmd = "console orm:convert-mapping json app/src/Payroll/AuthModule/Model";

		$result = $this->app->run(explode(" ", $mysqlCmd));
		$respLines = explode("\n", trim((string)$result));
		$hash = end($respLines);
		
		// $this->assertEquals(sprintf(Color::write("bg-red:bold", "%s"), "Invalid type [json]! Supported types are (xml|yaml|annotation)!"), $hash);

		$this->assertEquals("Invalid type [json]! Supported types are (xml|yaml|annotation)!", $hash);
	}

	public function testValidationNoInputOrInsufficientInput(){

		$mysqlCmd = "console orm:convert-mapping";

		$result = $this->app->run(explode(" ", $mysqlCmd));
		$respLines = explode("\n", trim((string)$result));
		$hash = end($respLines);
		
		// $this->assertEquals(sprintf(Color::write("bg-red:bold", "%s"), "Argument [path_to_entities] is required!"), $hash);

		$this->assertEquals("Argument [path_to_entities] is required!", $hash);
	}
}