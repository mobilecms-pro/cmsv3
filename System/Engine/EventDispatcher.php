<?php
/**
 * MobileCMS - это бесплатная система управления мобильных сайтов с открытым исходным кодом
 * @author MobileCMS Team <team@mobilecms.pro>
 * @copyright Copyright (c) 2011-2020, MobileCMS Team
 * @link http://mobilecms.pro Официальный сайт
 * @license MIT License
 */

namespace System\Engine;


use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

/**
 * Description of EventDispatcher
 *
 * @author 
 */
class EventDispatcher implements 
    EventDispatcherInterface, 
    ListenerProviderInterface
{   
    /**
     * Массив слушателей
     * @var array
     */
    protected $listeners = [];
    /**
     * Обрабатывает событие и возвращает его 
     * @param object $event
     * @return object
     */
    public function dispatch(object $event) {
        foreach ($this->getListenersForEvent($event) as $listener) {
            $listener($event);
            if (
                $event instanceof StoppableEventInterface 
                && ($event->isPropagationStopped() === true)
            ) {
                break;
            }
        }
        return $event;
    }
    
    /**
     * Возвращает всех слушателей прикрепленных к обьекту
     * @param object $event
     * @return iterable
     */
    public function getListenersForEvent(object $event): iterable {
        if (isset($this->listeners[get_class($event)])) {
            return $this->listeners[get_class($event)];
        }
    }
    /**
     * Прикрепляет слушателя к указанному событию
     * @param string $eventName
     * @param array|void $listener
     * @return mixed
     */
    public function attachListener(string $eventName, $listener) {
        if ($listener instanceof \Closure) {
            return $this->listeners[$eventName][] = $listener;
        } elseif (is_array($listener) && (count($listener) === 2)) {
            return $this->listeners[$eventName][] = 
            function (object $event) use ($listener){
                list($class, $method) = $listener;
                return $class->$method($event);
            };
        }
        throw new \InvalidArgumentException('');
    }
}
