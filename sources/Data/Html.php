<?php
/**
 * @project BasePHP Core
 * @file Html.php created by Ariel Bogdziewicz on 21/09/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Data;

/**
 * Class Html represents HTML content.
 * @package Base\Data
 */
class Html extends Raw
{
    /**
     * Html constructor.
     * @param string $html
     * @param string $charset
     */
    public function __construct(string $html, string $charset = "utf-8")
    {
        parent::__construct($html, "text/html", $charset);
    }
}
