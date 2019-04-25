<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.tagbee
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once("tagbee-request-interface.php");

final class Tagbee_Update_Tags_Request implements Tagbee_Request_Interface
{
    use Tagbee_Trait;

    /**
     * @var array
     */
    protected $tags;

    public function __construct($tags)
    {
        $this->tags = $tags;
    }

    public function buildBody()
    {
        return [
            'version' => self::TAGBEE_API_VERSION,
            'tags' => $this->buildRequestTags()
        ];
    }
}