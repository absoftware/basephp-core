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
use PHPUnit\Framework\TestCase;

final class RouteTest extends TestCase
{
    public function testRegex()
    {
        $tests = [
            [
                "route" => "",
                "path" => "",
                "expectedParamCount" => 0
            ],
            [
                "route" => "ranking",
                "path" => "",
                "expectedParamCount" => false
            ],
            [
                "route" => "",
                "path" => "ranking",
                "expectedParamCount" => false
            ],
            [
                "route" => "Ranking",
                "path" => "ranking",
                "caseSensitive" => true,
                "expectedParamCount" => false
            ],
            [
                "route" => "Ranking",
                "path" => "ranking",
                "caseSensitive" => false,
                "expectedParamCount" => 0
            ],
            [
                "route" => "ranking/{year}/{month}/{day}",
                "path" => "ranking/2018/07/18",
                "expectedParamCount" => 3
            ],
            [
                "route" => "ranking/{year}/{month}/{day}",
                "path" => "blog/2018/07",
                "expectedParamCount" => false
            ]
        ];
        
        for ($index = 0; $index < count($tests); ++$index)
        {
            // Take test info.
            $route = $tests[$index]["route"];
            $path = $tests[$index]["path"];
            $caseSensitive = isset($tests[$index]["caseSensitive"]) ? $tests[$index]["caseSensitive"] : false;
            $expectedParamCount = $tests[$index]["expectedParamCount"];
            
            // Execute test.
            $routeObject = new Route($route);
            $result = $routeObject->match($path, $caseSensitive);
            $paramCount = is_array($result) ? count($result) : false;
            
            // Check result.
            $this->assertTrue($expectedParamCount === $paramCount, "Test at index $index failed.");
        }
    }
}
