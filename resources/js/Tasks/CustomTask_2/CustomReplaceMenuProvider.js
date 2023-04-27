// CustomReplaceMenuProvider.js
import inherits from 'inherits';
import ReplaceMenuProvider from 'bpmn-js/lib/features/popup-menu/ReplaceMenuProvider';

export default function CustomReplaceMenuProvider(popupMenu, modeling, moddle, bpmnReplace, rules, translate) {
    ReplaceMenuProvider.call(this, popupMenu, modeling, moddle, bpmnReplace, rules, translate);

    const cached = this._createEntries;

    this._createEntries = function(element) {
        const entries = cached.call(this, element);

        // 修改以下代码以使用您自己的自定义任务类型
        const customTaskEntry = {
            label: 'Custom Task',
            className: 'bpmn-icon-task custom-task-icon',
            action: {
                name: 'replace-with-custom-task',
                img: 'bpmn-icon-task',
                handler: () => {
                    bpmnReplace.replaceElement(element, {
                        type: 'bpmn:Task',
                        custom: true // 自定义属性，以便在其他地方识别此任务类型
                    });
                }
            }
        };

        if (entries['replace-with-task']) {
            entries['replace-with-custom-task'] = customTaskEntry;
        }

        return entries;
    };
}

inherits(CustomReplaceMenuProvider, ReplaceMenuProvider);

CustomReplaceMenuProvider.$inject = [
    'popupMenu',
    'modeling',
    'moddle',
    'bpmnReplace',
    'rules',
    'translate'
];
