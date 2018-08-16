<?php
/**
 * @project BasePHP Core
 * @file RouteTest.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Tests\Core;

use Base\Core\Route;
use Base\Tools\HttpRequest;
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function testRegex()
    {
        $tests = [
            [
                "pattern" => "",
                "path" => "",
                "expectedParamCount" => 0
            ],
            [
                "pattern" => "ranking",
                "path" => "",
                "expectedParamCount" => false
            ],
            [
                "pattern" => "",
                "path" => "ranking",
                "expectedParamCount" => false
            ],
            [
                "pattern" => "Ranking",
                "path" => "ranking",
                "caseSensitive" => true,
                "expectedParamCount" => false
            ],
            [
                "pattern" => "Ranking",
                "path" => "ranking",
                "caseSensitive" => false,
                "expectedParamCount" => 0
            ],
            [
                "pattern" => "ranking/{year}/{month}/{day}",
                "path" => "ranking/2018/07/18",
                "expectedParamCount" => 3
            ],
            [
                "pattern" => "ranking/{year}/{month}/{day}",
                "path" => "blog/2018/07",
                "expectedParamCount" => false
            ]
        ];
        
        for ($index = 0; $index < count($tests); ++$index)
        {
            // Take test info.
            $pattern = $tests[$index]["pattern"];
            $path = $tests[$index]["path"];
            $caseSensitive = isset($tests[$index]["caseSensitive"]) ? $tests[$index]["caseSensitive"] : false;
            $expectedParamCount = $tests[$index]["expectedParamCount"];
            
            // Execute test.
            $routeObject = new Route(HttpRequest::GET, $pattern, "ExampleClass::method");
            $params = $routeObject->match(HttpRequest::GET, $path, $caseSensitive);
            $paramCount = is_array($params) ? count($params) : false;
            
            // Check result.
            $this->assertTrue($expectedParamCount === $paramCount, "Test at index $index failed.");
        }
    }
}
