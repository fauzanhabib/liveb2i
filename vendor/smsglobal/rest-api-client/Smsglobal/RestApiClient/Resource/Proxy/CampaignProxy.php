<?php
namespace Smsglobal\RestApiClient\Resource\Proxy;

use Smsglobal\RestApiClient\Resource\Campaign;
use Smsglobal\RestApiClient\RestApiClient;

class CampaignProxy extends Campaign
{
    private $rest;

    public function __construct($resourceUri, RestApiClient $rest)
    {
        $this->resourceUri = $resourceUri;
        $this->rest = $rest;

        // Get the ID from the resource URI
        // /v1/resource/id/ -> id
        $this->id = substr($resourceUri, 0, -1);
        $this->id = (int) substr($this->id, strrpos('/', $this->id) + 1, -1);
    }

    private function load()
    {
        if (isset($this->rest)) {
            $options = $this->rest->get($this->getResourceName(), $this->id);
            $this->setOptions($options);

            unset($this->rest);
        }
    }

    public function getCustomId()
    {
        $this->load();
        return parent::getCustomId();
    }

    public function getDateTime()
    {
        $this->load();
        return parent::getDateTime();
    }

    public function getDateTimeScheduled()
    {
        $this->load();
        return parent::getDateTimeScheduled();
    }

    public function getGroup()
    {
        $this->load();
        return parent::getGroup();
    }

    public function getMessage()
    {
        $this->load();
        return parent::getMessage();
    }

    public function getName()
    {
        $this->load();
        return parent::getName();
    }

    public function getOrigin()
    {
        $this->load();
        return parent::getOrigin();
    }

    public function getStaggerBatchSize()
    {
        $this->load();
        return parent::getStaggerBatchSize();
    }

    public function getStaggerEndTime()
    {
        $this->load();
        return parent::getStaggerEndTime();
    }

    public function getStaggerGap()
    {
        $this->load();
        return parent::getStaggerGap();
    }

    public function getStaggerStartTime()
    {
        $this->load();
        return parent::getStaggerStartTime();
    }

}
