// CustomTask.js
export default function CustomTask(eventBus, bpmnFactory, elementFactory, create) {
    this._eventBus = eventBus;
    this._bpmnFactory = bpmnFactory;
    this._elementFactory = elementFactory;
    this._create = create;
}

CustomTask.prototype.createCustomTask = function (element) {
    const businessObject = this._bpmnFactory.create('bpmn:Task');
    businessObject.set('custom:taskType', 'customTask');

    const shape = this._elementFactory.createShape({
        type: 'bpmn:Task',
        businessObject: businessObject,
    });

    shape.businessObject.di.set('custom:taskType', 'customTask');

    return shape;
};

CustomTask.$inject = ['eventBus', 'bpmnFactory', 'elementFactory', 'create'];
