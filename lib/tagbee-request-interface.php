<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.tagbee
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
interface Tagbee_Request_Interface
{
    const TAGBEE_API_VERSION = 1;

    public function buildBody();
}

trait Tagbee_Trait
{
    protected function buildRequestContent()
    {
        $joomlaVersion = new \Joomla\CMS\Version();

        return [
            'third_party_id' => $this->contentId,
            'title' => $this->contentTitle,
            'body' => $this->contentBody,
            'category' => $this->contentCategory,
            'meta_description' => $this->contentMetaDescription,
            'meta_keywords' => $this->contentMetaKeywords,
            'reference' => 'Joomla ' . $joomlaVersion->getShortVersion(),
            'permalink' => $this->permalink
        ];
    }

    protected function createCategoriesString($article, $delimiter = ',')
    {
        return Tagbee_Helper::getCategoryName($article->catid);
    }

    protected function createMetaKeywordsString($article, $delimiter = ',')
    {
        return implode($delimiter, Tagbee_Helper::getArticleMetaKeywordsArray($article->metakey));
    }

    protected function buildRequestTags()
    {
        return array_map(function($tag) {
            return ['third_party_id' => $tag->id, 'tag' => $tag->title];
        }, $this->tags);
    }
}