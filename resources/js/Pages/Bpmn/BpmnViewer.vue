<template>

    <div class="content with-diagram" id="js-drop-zone">

        <div ref="canvas" class="canvas" id="js-canvas"></div>
        <div ref="propertiesPanel" class="properties-panel-parent" id="js-properties-panel"></div>

    </div>

    <ul class="buttons">
        <li>
            download
        </li>
        <li>
            <a id="js-download-diagram" href title="download BPMN diagram">
                BPMN diagram
            </a>
        </li>
        <li>
            <a id="js-download-svg" href title="download as SVG image">
                SVG image
            </a>
        </li>

        <li>
            <a @click="save" href="javascript:" title="save">
                save---{{ date }}
            </a>
        </li>
    </ul>

</template>

<script setup>

import {ref, onMounted} from 'vue';
import {
    BpmnPropertiesPanelModule,
    BpmnPropertiesProviderModule,
    ZeebePropertiesProviderModule,
    CamundaPlatformPropertiesProviderModule,
} from 'bpmn-js-properties-panel';

import BpmnModeler from 'bpmn-js/lib/Modeler';
import 'bpmn-js-properties-panel/dist/assets/element-templates.css';
import 'bpmn-js-properties-panel/dist/assets/properties-panel.css';
import 'bpmn-js/dist/assets/diagram-js.css';
import 'bpmn-js/dist/assets/bpmn-js.css';
import 'bpmn-js/dist/assets/bpmn-font/css/bpmn-embedded.css';

import ZeebeBpmnModdle from 'zeebe-bpmn-moddle/resources/zeebe.json';
import CamundaBpmnModdle from 'camunda-bpmn-moddle/resources/camunda.json'


import CustomContextPadProvider from "../../Tasks/CustomContextPadProvider";
import CustomContextPadModule from "../../Tasks/CustomContextPadModule";



const canvas = ref(null);
const propertiesPanel = ref(null);
let modeler = null;
const date = ref(new Date().toDateString());
const props = defineProps({
    csrf_token: {
        type: String,
        required: true
    },
    xml: {
        type: String,
        default: ''
    }
})

onMounted(() => {
    init();
});

function init() {
    modeler = new BpmnModeler({
        container: canvas.value,
        propertiesPanel: {
            parent: propertiesPanel.value
        },
        additionalModules: [
            BpmnPropertiesPanelModule,
            BpmnPropertiesProviderModule,
            ZeebePropertiesProviderModule,

            // CustomContextPadProvider,
            // CustomContextPadModule,
            // CamundaPlatformPropertiesProviderModule,
        ],
        moddleExtensions: {
            zeebe: ZeebeBpmnModdle,
            // camunda: CamundaBpmnModdle
        }
    });

    modeler.importXML(props.xml);
}

function extendReplaceMenuProvider() {
    const replaceMenuProvider = modeler.get("replaceMenuProvider");
    const originalGetEntries = replaceMenuProvider.getEntries;

    replaceMenuProvider.getEntries = function (element) {
        const entries = originalGetEntries.apply(this, [element]);

        // Create a handler for the custom task
        function createCustomTaskHandler(event, element) {
            const elementFactory = modeler.get("elementFactory");
            const ids = modeler.get("ids");
            const uniqueId = ids.next("CustomTask");

            const customTask = elementFactory.createShape({
                id: uniqueId, // Generate a unique ID for the custom task
                type: "bpmn:Task",
            });

            customTask.businessObject.name = "Custom Task";
            const position = { x: event.x, y: event.y };

            modeler.get("modeling").createShape(customTask, position, element.parent);

            modeler.get("canvas").addMarker(customTask, "needs-discussion");

            return customTask;
        }



        entries.push({
            id: "replace-with-custom-task",
            label: "Custom Task",
            className: "bpmn-icon-custom-task",
            action: {
                click: (event) => createCustomTaskHandler(event, element), // Update this line
                dragstart: (event) => createCustomTaskHandler(event, element), // Update this line
            },
        });

        return entries;
    };
}

async function save() {

    await modeler.saveXML({format: true}, async function (err, xml) {
        if (err) {
            return console.error('could not save BPMN 2.0 diagram', err);
        }
        console.log(xml);


        try {
            const response = await fetch("/workflow/test", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": props.csrf_token,
                },
                body: JSON.stringify({xml}),

            });

            const data = await response.json();
            console.log(data);
        } catch (error) {
            console.error("Error saving BPMN XML:", error);
        }

    });
}


</script>

<style>
body,
html {
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 12px;
    height: 100%;
    max-height: 100%;
    padding: 0;
    margin: 0;
}

#js-properties-panel {
    width: 400px;
}

a:link {
    text-decoration: none;
}

.content {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
}

.content > .message {
    width: 100%;
    height: 100%;
    text-align: center;
    display: table;
    font-size: 16px;
    color: #111;
}

.content > .message .note {
    vertical-align: middle;
    text-align: center;
    display: table-cell;
}

.content > .message.error .details {
    max-width: 500px;
    font-size: 12px;
    margin: 20px auto;
    text-align: left;
    color: #BD2828;
}

.content > .message.error pre {
    border: solid 1px #BD2828;
    background: #fefafa;
    padding: 10px;
    color: #BD2828;
}

.content:not(.with-error) .error,
.content.with-error .intro,
.content.with-diagram .intro {
    display: none;
}

.content .canvas {
    width: 100%;
}

.content .canvas,
.content .properties-panel-parent {
    display: none;
}

.content.with-diagram .canvas,
.content.with-diagram .properties-panel-parent {
    display: block;
}

.buttons {
    position: fixed;
    bottom: 20px;
    left: 20px;
    padding: 0;
    margin: 0;
    list-style: none;
}

.buttons > li {
    display: inline-block;
    margin-right: 10px;
}

.buttons > li > a {
    background: #DDD;
    border: solid 1px #666;
    display: inline-block;
    padding: 5px;
}

.buttons a {
    opacity: 0.3;
}

.buttons a.active {
    opacity: 1.0;
}

.properties-panel-parent {
    border-left: 1px solid #ccc;
    overflow: auto;
}

.properties-panel-parent:empty {
    display: none;
}

.properties-panel-parent > .djs-properties-panel {
    padding-bottom: 70px;
    min-height: 100%;
}

</style>
