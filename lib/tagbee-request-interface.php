<?php

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
        return [
            'third_party_id' => $this->contentId,
            'title' => $this->contentTitle,
            'body' => $this->contentBody,
            'category' => $this->contentCategory,
            'meta_description' => $this->contentMetaDescription,
            'meta_keywords' => $this->contentMetaKeywords
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