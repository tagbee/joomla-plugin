<?php

defined('_JEXEC') or die;

final class Tagbee_Helper
{
    public static function updateMeta($article, $metaKey, $metaValue)
    {
        $meta = json_decode($article->metadata, true);
        $meta[$metaKey] = $metaValue;
        $article->metadata = json_encode($meta);
        return $article;
    }

    public static function getCategoryName($catId)
    {
        $db = JFactory::getDbo();
        $db->setQuery('SELECT cat.title FROM #__categories cat WHERE cat.id=' . (int) $catId);
        $category_title = $db->loadResult();

        return $category_title;
    }

    public static function getArticleTags($article)
    {
        $tags = new JHelperTags();
        $tags->getItemTags('com_content.article', $article->id);
        return $tags->itemTags ? $tags->itemTags : [];
    }

    public static function getArticleMetadata($articleObj)
    {
        return json_decode($articleObj->metadata, true);
    }

    public static function getArticleMetaKeywordsArray($metaString)
    {
        return array_map(function($metaKeyword) {
            return trim($metaKeyword);
        }, explode(',', $metaString));
    }

    public static function getArticleBody($article)
    {
        return $article->introtext . $article->fulltext;
    }

    public static function appendNewTags($article, $responseData)
    {
        $tagsInResponse = $responseData['tags'];
        $tagsInResponseText = array_map(function ($tag) {
            return $tag['tag'];
        }, $tagsInResponse);

        $oldTags = self::getArticleTags($article);
        $oldTagsText = array_map(function ($tag) {
            return $tag->title;
        }, $oldTags);

        $newTags = array_filter($tagsInResponseText, function ($tag)  use ($oldTagsText) {
            return !in_array($tag, $oldTagsText);
        });

        if (count($newTags)) {
            $tags = array_merge($tagsInResponseText, $oldTagsText);
            $article->newTags = array_map(function ($tag) {
                return "#new#" . $tag;
            }, $tags);
        }

        return $article;
    }
}