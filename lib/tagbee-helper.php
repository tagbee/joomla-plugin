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
}