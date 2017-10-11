<?php

namespace Bixie\PkFramework\Plugin;

use Pagekit\Application as App;
use Pagekit\Content\Event\ContentEvent;
use Pagekit\Event\EventSubscriberInterface;
use Pagekit\Widget\Model\Widget;

class WidgetLoaderPlugin implements EventSubscriberInterface
{

    /**
     * Content plugins callback.
     *
     * @param ContentEvent $event
     */
    public function onContentPlugins(ContentEvent $event)
    {
        $event->addPlugin('widget', [$this, 'applyPlugin']);
    }

    /**
     * Defines the plugins callback.
     *
     * @param  array $options
     * @return string
     */
    public function applyPlugin(array $options)
    {
        if (!isset($options['id'])
            or !$widget = Widget::where(['id' => $options['id'],'status' => 1])->first()
            or !$widget->hasAccess(App::user())
            or !$type = App::widget($widget->type)
        ) {
            return '';
        }

		return $type->render($widget);
	}

    /**
     * {@inheritdoc}
     */
    public function subscribe()
    {
        return [
            'content.plugins' => ['onContentPlugins', 25],
        ];
    }
}
