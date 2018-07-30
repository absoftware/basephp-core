<?php
/**
 * @project BasePHP Core
 * @file PhpInfo.php created by Ariel Bogdziewicz on 29/07/2018
 * @author Ariel Bogdziewicz
 * @copyright Copyright © 2018 Ariel Bogdziewicz. All rights reserved.
 * @license MIT
 */
namespace Base\Responses;

/**
 * Class PhpInfo represents HTML response with PHP info.
 * @package Base\Responses
 */
class PhpInfo implements Response
{
    /**
     * Returns PHP info.
     * @return string
     */
    public function body(): string
    {
        ob_start();
        phpinfo();
        return ob_get_clean();
    }

    /**
     * Displays PHP info into output buffer.
     */
    public function display(): void
    {
        phpinfo();
    }
}
