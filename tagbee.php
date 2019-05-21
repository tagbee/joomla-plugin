<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.tagbee
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('Tagbee_Client', dirname(__FILE__) . '/lib/tagbee-client.php');
JLoader::register('Tagbee_Auto_Proposals_Request', dirname(__FILE__) . '/lib/tagbee-auto-proposals-request.php');
JLoader::register('Tagbee_Helper', dirname(__FILE__) . '/lib/tagbee-helper.php');

class PlgContentTagbee extends JPlugin
{
    const ARTICLE_CONTEXT  = 'com_content.article';

    protected $autoloadLanguage = true;

    function onContentPrepareForm($form, $data)
    {
        if (self::ARTICLE_CONTEXT == $form->getName())
        {
            $app = JFactory::getApplication();

            if ($app->isAdmin())
            {
                JForm::addFormPath(__DIR__ . '/forms');
                $form->loadFile('tagbee', false);
            }
        }
    }

    function onContentAfterSave($context, $article, $isNew)
    {
        if (self::ARTICLE_CONTEXT == $context)
        {
            JLog::addLogger([
                'text_file' => 'tagbee.error.php'
            ]);

            $client = new Tagbee_Client(
                $this->params->get('tagbee_api_key'),
                $this->params->get('tagbee_api_key_secret')
            );

            $response = $client->postAutoProposals((new Tagbee_Auto_Proposals_Request(
                $article,
                Tagbee_Helper::getArticleTags($article),
                Tagbee_Helper::getArticleMetadata($article)
            )));

            if (!$response = json_decode($response->body, true)) {
                JLog::add('Pls contact TagBee support at support@tagbee.co', JLog::ERROR);
                return;
            }

            if (isset($response['error'])) {
                JLog::add($response['error']['message'], JLog::ERROR);
                return;
            }

            if (!isset($response['data'])) {
                return;
            }

            $data = $response['data'];
            $remoteId = $data['id'];

            $article = Tagbee_Helper::updateMeta($article, 'tagbee_api_id', $remoteId);
            $article = Tagbee_Helper::appendNewTags($article, $data);
            $article->store();

            $tags = Tagbee_Helper::getArticleTags($article);

            $client->putTags($remoteId, new Tagbee_Update_Tags_Request($tags));
        }
    }
}
