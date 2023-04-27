<template>
    <div>
        <div ref="canvas" class="bpmn-viewer"></div>
        <button @click="exportDiagram">print to console</button>
    </div>
</template>

<script>
import { ref, onMounted } from "vue";
import BpmnJS from "bpmn-js";
import "bpmn-js/dist/assets/diagram-js.css";
import "bpmn-js/dist/assets/bpmn-js.css";
import "bpmn-js/dist/assets/bpmn-font/css/bpmn.css";

export default {
    setup() {
        const canvas = ref(null);
        let bpmnModeler;

        onMounted(() => {
            init();
            loadDiagram();
        });

        async function init() {
            bpmnModeler = new BpmnJS({
                container: canvas.value,
                keyboard: {
                    bindTo: window,
                },
            });
        }

        async function loadDiagram() {
            const diagramUrl =
                "https://cdn.staticaly.com/gh/bpmn-io/bpmn-js-examples/dfceecba/starter/diagram.bpmn";
            try {
                const response = await fetch(diagramUrl);
                const bpmnXML = await response.text();
                await openDiagram(bpmnXML);
            } catch (err) {
                console.error("Error loading diagram", err);
            }
        }

        async function openDiagram(bpmnXML) {
            try {
                await bpmnModeler.importXML(bpmnXML);

                const canvas = bpmnModeler.get("canvas");
                const overlays = bpmnModeler.get("overlays");

                canvas.zoom("fit-viewport");

                overlays.add("SCAN_OK", "note", {
                    position: {
                        bottom: 0,
                        right: 0,
                    },
                    html: '<div class="diagram-note">Mixed up the labels?</div>',
                });

                canvas.addMarker("SCAN_OK", "needs-discussion");
            } catch (err) {
                console.error("could not import BPMN 2.0 diagram", err);
            }
        }

        async function exportDiagram() {
            try {
                const result = await bpmnModeler.saveXML({ format: true });
                alert("Diagram exported. Check the developer tools!");
                console.log("DIAGRAM", result.xml);
            } catch (err) {
                console.error("could not save BPMN 2.0 diagram", err);
            }
        }

        return {
            canvas,
            exportDiagram,
        };
    },
};
</script>

<style scoped>
.bpmn-viewer {
    height: 100%;
    padding: 0;
    margin: 0;
}

.diagram-note {
    background-color: rgba(66, 180, 21, 0.7);
    color: white;
    border-radius: 5px;
    font-family: Arial;
    font-size: 12px;
    padding: 5px;
    min-height: 16px;
    width: 50px;
    text-align: center;
}

.needs-discussion:not(.djs-connection) .djs-visual > :nth-child(1) {
    stroke: rgba(66, 180, 21, 0.7) !important;
}
</style>
