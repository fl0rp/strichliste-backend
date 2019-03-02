<?php
/**
 * Created by PhpStorm.
 * User: flo
 * Date: 01.03.19
 * Time: 23:59
 */

namespace App\EventListener;


use App\Event\TransactionCreatedEvent;
use App\Service\MqttService;
use App\Service\SettingsService;

class TransactionCreatedListener
{

    /**
     * @var SettingsService
     */
    private $settingsService;

    /**
     * @var MqttService
     */
    private $mqttService;

    public function __construct(SettingsService $settingsService, MqttService $mqttService)
    {
        $this->settingsService = $settingsService;
        $this->mqttService = $mqttService;
    }

    public function onTransactionCreated(TransactionCreatedEvent $event)
    {
        if($this->mqttService->isEnabled()) {
            $this->mqttService->notify(
                $event->getTransaction()
            );
        }
    }
}