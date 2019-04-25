<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.tagbee
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once('tagbee-auto-proposals-request.php');
require_once('tagbee-update-tags-request.php');

final class Tagbee_Client
{
    protected $apiKey;
    protected $secretKey;

    public function __construct($apiKey, $secretKey)
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
    }

    public function putTags($tagbeeApiId, Tagbee_Update_Tags_Request $tagbeeRequest)
    {
        $httpClient = \Joomla\CMS\Http\HttpFactory::getHttp($this->requestArguments($tagbeeRequest));

        return $httpClient->put(
            'https://tagbee.co/api/article/' . $tagbeeApiId . '/tags',
            json_encode($tagbeeRequest->buildBody())
        );
    }

    public function postAutoProposals(Tagbee_Auto_Proposals_Request $tagbeeRequest)
    {
        $httpClient = \Joomla\CMS\Http\HttpFactory::getHttp($this->requestArguments($tagbeeRequest));

        return $httpClient->post(
            'https://tagbee.co/api/article/auto-proposals',
            json_encode($tagbeeRequest->buildBody())
        );
    }

    protected function requestArguments(Tagbee_Request_Interface $tagbeeRequest)
    {
        $jsonData = json_encode($tagbeeRequest->buildBody());

        return new Joomla\Registry\Registry(['headers' => [
            'Accept'  => 'application/json',
            'Content-Type'  => 'application/json; charset=utf-8',
            'X-TagBee-PubKey' => $this->apiKey,
            'X-TagBee-Signature' => $this->signature($this->secretKey, $jsonData),
        ]]);
    }

    protected function signature($secretKey, $jsonData)
    {
        return base64_encode(hash_hmac('sha256', $jsonData, $secretKey, true));
    }
}
