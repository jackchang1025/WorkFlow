import inherits from 'inherits';
import BpmnPropertiesProvider from 'https://cdn.skypack.dev/bpmn-js-properties-panel/lib/provider/bpmn';

export default function CustomPropertiesProvider(eventBus, bpmnFactory, elementRegistry) {
    BpmnPropertiesProvider.call(this, eventBus, bpmnFactory, elementRegistry);
}

inherits(CustomPropertiesProvider, BpmnPropertiesProvider);

CustomPropertiesProvider.$inject = ['eventBus', 'bpmnFactory', 'elementRegistry'];

// Override the getTabs function
CustomPropertiesProvider.prototype.getTabs = function (element) {
    const tabs = BpmnPropertiesProvider.prototype.getTabs.call(this, element);

    // Add the custom drop-down single-select property for gateways
    if (element.type === 'bpmn:Gateway') {
        const customGroup = {
            id: 'custom',
            label: 'Custom',
            entries: [],
        };

        customGroup.entries.push({
            id: 'customDropDown',
            description: 'Select a custom property',
            label: 'Custom Property',
            modelProperty: 'customProperty',
            selectOptions: [
                { name: 'Option 1', value: 'option1' },
                { name: 'Option 2', value: 'option2' },
            ],

            get: function (element, node) {
                const selectedOption = element.businessObject.customProperty || '';
                return { customProperty: selectedOption };
            },

            set: function (element, values, node) {
                const bo = element.businessObject;
                const customProperty = values.customProperty;

                return { businessObject: bo, properties: { customProperty: customProperty } };
            },

            hidden: function (element) {
                return false;
            },

            options: function (element) {
                return this.selectOptions;
            },
        });

        tabs.push({ id: 'customProperties', label: 'Custom', groups: [customGroup] });
    }

    return tabs;
};
