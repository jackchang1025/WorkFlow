// CustomContextPadProvider.js
import inherits from 'inherits';
import ContextPadProvider from 'bpmn-js/lib/features/context-pad/ContextPadProvider';

export default function CustomContextPadProvider(contextPad, customTask, translate) {
    ContextPadProvider.call(this, contextPad, translate);
    this._customTask = customTask;
}

inherits(CustomContextPadProvider, ContextPadProvider);

CustomContextPadProvider.$inject = [
    'contextPad',
    'customTask',
    'translate',
];

CustomContextPadProvider.prototype.getContextPadEntries = function (
    element
) {
    const actions = ContextPadProvider.prototype.getContextPadEntries.call(
        this,
        element
    );

    const changeTypeActions = actions['change.type'].items;

    // 创建自定义任务操作
    const customTaskAction = {
        label: 'Create Custom Task',
        action: {
            name: 'replace-with-custom-task',
            handler: () => {
                const customTaskShape = this._customTask.createCustomTask(element);
                this._create.start(event, customTaskShape);
            },
        },
    };

    // 向 changeTypeActions 添加自定义任务操作
    changeTypeActions.unshift(customTaskAction);

    // 更新 actions['change.type'].items 的值
    assign(actions['change.type'], {
        items: changeTypeActions,
    });

    return actions;
};
