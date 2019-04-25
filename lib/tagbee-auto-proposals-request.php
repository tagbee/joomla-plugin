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
require_once JPATH_SITE . '/components/com_content/helpers/route.php';

final class Tagbee_Auto_Proposals_Request implements Tagbee_Request_Interface
{
    use Tagbee_Trait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var int
     */
    protected $contentId;

    /**
     * @var string
     */
    protected $contentTitle;

    /**
     * @var string
     */
    protected $contentBody;

    /**
     * @var string
     */
    protected $contentCategory;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var string
     */
    protected $contentMetaDescription;

    /**
     * @var string
     */
    protected $contentMetaKeywords;

    /**
     * @var string
     */
    protected $permalink;

    public function __construct($data, $tags, $meta)
    {
        $this->id = !empty($meta['tagbee_api_id']) ? $meta['tagbee_api_id'] : null;
        $this->contentId = $data->id;
        $this->contentTitle = $data->title;
        $this->contentBody = Tagbee_Helper::getArticleBody($data);
        $this->contentCategory = $this->createCategoriesString($data);
        $this->permalink = $this->getArticleURL($data->id, $data->catid, $data->language);

        $this->contentMetaDescription = $data->metadesc;
        $this->contentMetaKeywords = $this->createMetaKeywordsString($data);
        $this->tags = $tags;
    }

    public function buildBody()
    {
        return [
            'id' => $this->id,
            'content' => $this->buildRequestContent(),
            'version' => self::TAGBEE_API_VERSION,
            'tags' => $this->buildRequestTags()
        ];
    }

    protected function getArticleURL($id, $categoryId, $lang)
    {
        $url = ContentHelperRoute::getArticleRoute($id, $categoryId, $lang);
        return JURI::root() . $url;
    }
}