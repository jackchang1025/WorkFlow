<?php

namespace App\Console\Commands;


use App\Services\Engine\BpmnEngine;
use App\Services\Engine\EventEngine;
use App\Services\Engine\RepositoryEngine;

use App\Services\Tasks\GetLotteryDataTask;
use App\Services\TestService;
use Illuminate\Console\Command;
use ProcessMaker\Nayra\Bpmn\Models\Process;
use ProcessMaker\Nayra\Contracts\RepositoryInterface;
use ProcessMaker\Nayra\Storage\BpmnDocument;

class WorkFlow extends Command
{



    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'workflow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(protected readonly TestService $testService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

//            <bpmn:serviceTask id="FormTask_1" pm:implementation="BetTask">
//              <bpmn:extensionElements>
//                <form:formData xmlns:form="http://example.com/form">
//                  <form:input id="input1" type="text" />
//                  <form:select id="select1">
//                    <form:option value="option1">Option 1</form:option>
//                    <form:option value="option2">Option 2</form:option>
//                  </form:select>
//                </form:formData>
//              </bpmn:extensionElements>
//            </bpmn:serviceTask>

        //    <bpmn:task id="node_8" name="Form Task" pm:screenRef="2" pm:allowInterstitial="false" pm:assignment="requester" pm:assignmentLock="false" pm:allowReassignment="false">
        //      <bpmn:incoming>node_11</bpmn:incoming>
        //      <bpmn:outgoing>node_3</bpmn:outgoing>
        //    </bpmn:task>

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL" xmlns:bpmndi="http://www.omg.org/spec/BPMN/20100524/DI" xmlns:dc="http://www.omg.org/spec/DD/20100524/DC" xmlns:di="http://www.omg.org/spec/DD/20100524/DI" xmlns:pm="http://processmaker.com/BPMN/2.0/Schema.xsd" xmlns:tns="http://sourceforge.net/bpmn/definitions/_1530553328908" xmlns:xsd="http://www.w3.org/2001/XMLSchema" targetNamespace="http://bpmn.io/schema/bpmn" exporter="ProcessMaker Modeler" exporterVersion="1.0" xsi:schemaLocation="http://www.omg.org/spec/BPMN/20100524/MODEL http://bpmn.sourceforge.net/schemas/BPMN20.xsd">
  <bpmn:process id="ProcessId" name="ProcessName" isExecutable="true">

    <bpmn:startEvent id="node_7" name="Start Event" pm:allowInterstitial="false">
      <bpmn:outgoing>node_12</bpmn:outgoing>
    </bpmn:startEvent>



        <bpmn:serviceTask id="node_8" name="Form Task" pm:implementation="BetTask" pm:screenRef="2" pm:allowInterstitial="false" pm:assignment="requester" pm:assignmentLock="false" pm:allowReassignment="false">
          <bpmn:incoming>node_11</bpmn:incoming>
          <bpmn:outgoing>node_3</bpmn:outgoing>
        </bpmn:serviceTask>

    <bpmn:task id="node_9" name="Form Task" pm:screenRef="2" pm:allowInterstitial="false" pm:assignment="rule_expression" pm:assignedUsers="" pm:assignedGroups="" pm:assignmentLock="false" pm:allowReassignment="false" pm:assignmentRules="[]">
      <bpmn:incoming>node_12</bpmn:incoming>
      <bpmn:outgoing>node_6</bpmn:outgoing>
    </bpmn:task>



    <bpmn:sequenceFlow id="node_12" name="" sourceRef="node_7" targetRef="node_9" />
    <bpmn:endEvent id="node_22" name="End Event">
      <bpmn:incoming>node_5</bpmn:incoming>
    </bpmn:endEvent>
    <bpmn:task id="node_1" name="Form Task" pm:screenRef="2" pm:allowInterstitial="false" pm:assignment="requester" pm:assignmentLock="false" pm:allowReassignment="false">
      <bpmn:incoming>node_3</bpmn:incoming>
      <bpmn:outgoing>node_5</bpmn:outgoing>
    </bpmn:task>
    <bpmn:sequenceFlow id="node_3" name="" sourceRef="node_8" targetRef="node_1" />
    <bpmn:sequenceFlow id="node_5" name="" sourceRef="node_1" targetRef="node_22" />
    <bpmn:exclusiveGateway id="node_16" name="Exclusive Gateway">
      <bpmn:incoming>node_6</bpmn:incoming>
      <bpmn:outgoing>node_11</bpmn:outgoing>
    </bpmn:exclusiveGateway>
    <bpmn:sequenceFlow id="node_6" sourceRef="node_9" targetRef="node_16" />
    <bpmn:sequenceFlow id="node_11" sourceRef="node_16" targetRef="node_8" />
  </bpmn:process>
  <bpmndi:BPMNDiagram id="BPMNDiagramId">
    <bpmndi:BPMNPlane id="BPMNPlaneId" bpmnElement="ProcessId">
      <bpmndi:BPMNShape id="node_7_di" bpmnElement="node_7">
        <dc:Bounds x="510" y="360" width="36" height="36" />
      </bpmndi:BPMNShape>
      <bpmndi:BPMNShape id="node_8_di" bpmnElement="node_8">
        <dc:Bounds x="650" y="730" width="116" height="76" />
      </bpmndi:BPMNShape>
      <bpmndi:BPMNShape id="node_9_di" bpmnElement="node_9">
        <dc:Bounds x="1130" y="520" width="116" height="76" />
      </bpmndi:BPMNShape>
      <bpmndi:BPMNEdge id="node_12_di" bpmnElement="node_12">
        <di:waypoint x="528" y="378" />
        <di:waypoint x="1188" y="558" />
      </bpmndi:BPMNEdge>
      <bpmndi:BPMNShape id="node_22_di" bpmnElement="node_22">
        <dc:Bounds x="920" y="950" width="36" height="36" />
      </bpmndi:BPMNShape>
      <bpmndi:BPMNShape id="node_1_di" bpmnElement="node_1">
        <dc:Bounds x="890" y="760" width="116" height="76" />
      </bpmndi:BPMNShape>
      <bpmndi:BPMNEdge id="node_3_di" bpmnElement="node_3">
        <di:waypoint x="708" y="768" />
        <di:waypoint x="948" y="798" />
      </bpmndi:BPMNEdge>
      <bpmndi:BPMNEdge id="node_5_di" bpmnElement="node_5">
        <di:waypoint x="948" y="798" />
        <di:waypoint x="938" y="968" />
      </bpmndi:BPMNEdge>
      <bpmndi:BPMNShape id="node_16_di" bpmnElement="node_16">
        <dc:Bounds x="790" y="620" width="36" height="36" />
      </bpmndi:BPMNShape>
      <bpmndi:BPMNEdge id="node_6_di" bpmnElement="node_6">
        <di:waypoint x="1188" y="558" />
        <di:waypoint x="808" y="638" />
      </bpmndi:BPMNEdge>
      <bpmndi:BPMNEdge id="node_11_di" bpmnElement="node_11">
        <di:waypoint x="808" y="638" />
        <di:waypoint x="708" y="768" />
      </bpmndi:BPMNEdge>
    </bpmndi:BPMNPlane>
  </bpmndi:BPMNDiagram>
</bpmn:definitions>
XML;

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL" xmlns:pm="http://processmaker.com/BPMN/2.0/Schema.xsd" id="sample-process" targetNamespace="http://bpmn.io/schema/bpmn">
  <bpmn:process id="ProcessId" isExecutable="true">
    <!-- Start Event -->
    <bpmn:startEvent id="startEvent_1">
      <bpmn:outgoing>sequenceFlow_1</bpmn:outgoing>
    </bpmn:startEvent>

    <!-- Sequence Flow from Start Event to Script Task -->
    <bpmn:sequenceFlow id="sequenceFlow_1" sourceRef="startEvent_1" targetRef="scriptTask_1" />

    <!-- Script Task -->
    <bpmn:scriptTask id="scriptTask_1" name="Sample Script Task" scriptFormat="javascript">
      <bpmn:incoming>sequenceFlow_1</bpmn:incoming>
      <bpmn:outgoing>sequenceFlow_2</bpmn:outgoing>
    </bpmn:scriptTask>

    <!-- Sequence Flow from Script Task to Service Task -->
    <bpmn:sequenceFlow id="sequenceFlow_2" sourceRef="scriptTask_1" targetRef="serviceTask_1" />

    <!-- Service Task -->
    <bpmn:betTask id="serviceTask_1" name="Sample Service Task" pm:implementation="BetTask">
      <bpmn:incoming>sequenceFlow_2</bpmn:incoming>
      <bpmn:outgoing>sequenceFlow_3</bpmn:outgoing>
    </bpmn:betTask>

    <!-- Sequence Flow from Service Task to End Event -->
    <bpmn:sequenceFlow id="sequenceFlow_3" sourceRef="serviceTask_1" targetRef="endEvent_1" />

    <!-- End Event -->
    <bpmn:endEvent id="endEvent_1">
      <bpmn:incoming>sequenceFlow_3</bpmn:incoming>
    </bpmn:endEvent>
  </bpmn:process>
</bpmn:definitions>

XML;


        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<bpmn:definitions xmlns:bpmn="http://www.omg.org/spec/BPMN/20100524/MODEL" xmlns:pm="http://processmaker.com/BPMN/2.0/Schema.xsd" id="sample-process" targetNamespace="http://bpmn.io/schema/bpmn">
  <bpmn:process id="ProcessId" isExecutable="true">
    <!-- Start Event -->
    <bpmn:startEvent id="startEvent_1">
      <bpmn:outgoing>sequenceFlow_1</bpmn:outgoing>
    </bpmn:startEvent>

    <!-- Sequence Flow from Start Event to Script Task -->
    <bpmn:sequenceFlow id="sequenceFlow_1" sourceRef="startEvent_1" targetRef="scriptTask_1" />

    <!-- Script Task -->
    <bpmn:scriptTask id="scriptTask_1" name="Sample Script Task" scriptFormat="javascript">
      <bpmn:incoming>sequenceFlow_1</bpmn:incoming>
      <bpmn:outgoing>sequenceFlow_2</bpmn:outgoing>
    </bpmn:scriptTask>

    <!-- Sequence Flow from Script Task to Service Task -->
    <bpmn:sequenceFlow id="sequenceFlow_2" sourceRef="scriptTask_1" targetRef="serviceTask_1" />

    <bpmn:formActivityTask id="serviceTask_1" name="From Task" pm:implementation="run">
          <bpmn:extensionElements>
            <form:formData xmlns:form="http://example.com/form">
              <form:input id="input1" type="text" label="title" value="测试"/>
              <form:select id="select1" label="type">
                <form:option value="option1" check="true">Option 1</form:option>
                <form:option value="option2">Option 2</form:option>
              </form:select>
            </form:formData>
          </bpmn:extensionElements>
      <bpmn:incoming>sequenceFlow_2</bpmn:incoming>
      <bpmn:outgoing>sequenceFlow_3</bpmn:outgoing>
    </bpmn:formActivityTask>

    <!-- Service Task -->
<!--    <bpmn:betTask id="serviceTask_1" name="Sample Service Task" pm:implementation="BetTask">-->
<!--      <bpmn:incoming>sequenceFlow_2</bpmn:incoming>-->
<!--      <bpmn:outgoing>sequenceFlow_3</bpmn:outgoing>-->
<!--    </bpmn:betTask>-->

    <!-- Sequence Flow from Service Task to End Event -->
    <bpmn:sequenceFlow id="sequenceFlow_3" sourceRef="serviceTask_1" targetRef="endEvent_1" />

    <!-- End Event -->
    <bpmn:endEvent id="endEvent_1">
      <bpmn:incoming>sequenceFlow_3</bpmn:incoming>
    </bpmn:endEvent>
  </bpmn:process>
</bpmn:definitions>

XML;


        $this->testService->setXml($xml)->setId('ProcessId')->handle();

    }
}
