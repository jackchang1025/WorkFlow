<?php

namespace App\Services\Engine;

use ProcessMaker\Nayra\Contracts\EventBusInterface;

class EventEngine implements EventBusInterface
{
    /**
     * 存储已注册的事件监听器。键是事件名称，值是一个包含监听器的数组
     * @var array
     */
    protected array $listeners = [];

    /**
     * 用于存储已推送但尚未处理的事件和其相关数据。键是事件名称，值是一个包含事件数据的数组
     * @var array
     */
    protected array $queuedListeners = [];

    /**
     * 用于注册事件监听器。它接受一个事件名称（字符串）或多个事件名称（数组），以及一个监听器（通常是一个回调函数）。方法会遍历事件名称并将监听器添加到 $listeners 数组中
     * @param $events
     * @param $listener
     * @return void
     */
    public function listen($events, $listener)
    {
        $events = is_array($events) ? $events : [$events];
        foreach ($events as $event) {
            $this->listeners[$event][] = $listener;
        }
    }

    /**
     * 检查给定事件是否有注册的监听器。如果有，返回 true；否则返回 false。
     * @param $eventName
     * @return bool
     */
    public function hasListeners($eventName): bool
    {
        return isset($this->listeners[$eventName]) && count($this->listeners[$eventName]) > 0;
    }

    /**
     * 用于订阅事件，但在此示例中未实现。
     * @param $subscriber
     * @return void
     */
    public function subscribe($subscriber)
    {
        // Not implemented
    }

    /**
     * 调用 dispatch 方法，但将 $halt 参数设置为 true。这意味着在分发事件时，如果有一个监听器返回非空值，该方法将立即返回该值，而不再处理剩余的监听器。
     * @param $event
     * @param $payload
     * @return array|mixed|null
     */
    public function until($event, $payload = [])
    {
        return $this->dispatch($event, $payload, true);
    }

    /**
     * 负责分发事件。它接受事件名称、事件数据和一个可选的 $halt 参数。如果 $halt 为 true，则在处理事件时，一旦有一个监听器返回非空值，分发将停止。该方法返回事件处理的结果。
     * @param $event
     * @param $payload
     * @param $halt
     * @return array|mixed|null
     */
    public function dispatch($event, $payload = [], $halt = false)
    {
        if (!$this->hasListeners($event)) {
            return null;
        }

        $responses = [];
        foreach ($this->listeners[$event] as $listener) {
            $response = call_user_func($listener, $event, $payload);
            if ($halt && !is_null($response)) {
                return $response;
            }
            $responses[] = $response;
        }

        return $halt ? null : $responses;
    }

    /**
     * 将事件推入队列，稍后处理。它接受事件名称和事件数据。推送的事件将添加到 $queuedListeners 数组中。
     * @param $event
     * @param $payload
     * @return void
     */
    public function push($event, $payload = [])
    {
        $this->queuedListeners[$event][] = $payload;
    }

    /**
     * 处理队列中的所有指定事件。它会遍历队列中的事件并调用 dispatch 方法进行处理。处理完成后，它会调用 forget 方法清除事件监听器。
     * @param $event
     * @return void
     */
    public function flush($event)
    {
        if (isset($this->queuedListeners[$event])) {
            foreach ($this->queuedListeners[$event] as $payload) {
                $this->dispatch($event, $payload);
            }
            $this->forget($event);
        }
    }

    /**
     *  $listeners 数组中删除指定事件的监听器。
     * @param $event
     * @return void
     */
    public function forget($event)
    {
        unset($this->listeners[$event]);
    }

    /**
     * 清空 $queuedListeners 数组，删除所有推送的事件及其数据。
     * @return void
     */
    public function forgetPushed()
    {
        $this->queuedListeners = [];
    }
}
