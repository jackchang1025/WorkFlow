import inherits from 'inherits';
import ContextPadProvider from 'bpmn-js/lib/features/context-pad/ContextPadProvider';


function CustomContextPadProvider(injector) {
    injector.invoke(ContextPadProvider, this);
}

inherits(CustomContextPadProvider, ContextPadProvider);

CustomContextPadProvider.$inject = ['injector'];

CustomContextPadProvider.prototype.getContextPadEntries = function (element) {
    const actions = ContextPadProvider.prototype.getContextPadEntries.call(this, element);

    console.log(element.type);
    console.log(element.type === 'bpmn:ServiceTask');
    console.log(actions);
    console.log(actions.replace);
    console.log(actions['replace']);

    if (element.type === 'bpmn:Task' || element.type === 'bpmn:ServiceTask' && actions['replace']) {
        actions['replace'] = {
            ...actions['replace'],
            items: {
                ...actions['replace'].items,
                'custom-task': {
                    group: 'edit',
                    className: 'bpmn-icon-task',
                    title: 'Custom Task',
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


export default {
    __init__: ['contextPadProvider'],
    contextPadProvider: ['type', CustomContextPadProvider]
};
