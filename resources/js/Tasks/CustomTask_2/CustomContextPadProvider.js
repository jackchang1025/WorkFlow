// CustomContextPadProvider.js
import inherits from 'inherits';
import ContextPadProvider from 'bpmn-js/lib/features/context-pad/ContextPadProvider';

import CustomReplaceMenuProvider from './CustomReplaceMenuProvider';

export default function CustomContextPadProvider(contextPad, config, injector, eventBus, contextMenu) {
    injector.invoke(ContextPadProvider, this, { contextPad });

    const cached = this.getContextPadEntries;

    this.getContextPadEntries = function(element) {
        const actions = cached.call(this, element);

        // 删除不需要的默认项
        // delete actions['replace'];

        // 使用自定义的ReplaceMenuProvider替换默认的ReplaceMenuProvider
        actions.replace.action = () => {
            contextMenu.open(element, CustomReplaceMenuProvider.$inject);
        };

        return actions;
    };
}

inherits(CustomContextPadProvider, ContextPadProvider);

CustomContextPadProvider.$inject = [
    'contextPad',
    'config',
    'injector',
    'eventBus',
    'contextMenu'
];
