import inherits from 'inherits';
import ContextPadProvider from 'bpmn-js/lib/features/context-pad/ContextPadProvider';

export default function CustomContextPadProvider(injector) {
    injector.invoke(ContextPadProvider, this);
}

inherits(CustomContextPadProvider, ContextPadProvider);

CustomContextPadProvider.$inject = ['injector'];

CustomContextPadProvider.prototype.getContextPadEntries = function (element) {
    const actions = ContextPadProvider.prototype.getContextPadEntries.call(this, element);

    console.log(element.type);

    if (element.type === 'bpmn:Task') {
        actions['change.type'] = {
            ...actions['change.type'],
            items: {
                ...actions['change.type'].items,
                'custom-task': {
                    group: 'change',
                    className: 'bpmn-icon-task',
                    title: 'Change to Custom Task',
                    action: {
                        click: (event, element) => {
                            this._modeling.updateProperties(element, {
                                custom: true,
                            });
                        },
                    },
                },
            },
        };
    }

    return actions;
};
