<?php

namespace App\Services;

use App\Services\Engine\BpmnEngine;
use App\Services\Engine\EventEngine;
use App\Services\Engine\RepositoryEngine;
use App\Services\Events\ServiceTaskActivatedEvent;
use App\Services\Models\StartEventService;
use App\Services\Tasks\BetTask;
use App\Services\Tasks\CreateBetAmountTask;
use App\Services\Tasks\CreateBetCodeTask;
use App\Services\Tasks\GetLotteryDataTask;
use App\Services\Tasks\FormActivityTask;
use DOMDocument;
use Illuminate\Support\Facades\Cache;
use ProcessMaker\Nayra\Bpmn\Events\ActivityActivatedEvent;
use ProcessMaker\Nayra\Bpmn\Models\Flow;
use ProcessMaker\Nayra\Bpmn\Models\InclusiveGateway;
use ProcessMaker\Nayra\Bpmn\Models\Process;
use ProcessMaker\Nayra\Bpmn\Models\ScriptTask;
use ProcessMaker\Nayra\Bpmn\Models\ServiceTask;
use ProcessMaker\Nayra\Contracts\Bpmn\ActivityInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\EventDefinitionInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\EventInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ExclusiveGatewayInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\FlowInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\FlowNodeInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\LoopCharacteristicsInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ServiceInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\ServiceTaskInterface;
use ProcessMaker\Nayra\Contracts\Bpmn\TokenInterface;
use ProcessMaker\Nayra\Storage\BpmnDocument;
use ProcessMaker\Nayra\Storage\BpmnElement;

class TestService
{
    const NAMESPACE = 'http://www.omg.org/spec/BPMN/20100524/MODEL';

    protected array $tasks = [
        [
            'namespace' => self::NAMESPACE,
            'tagName'   => GetLotteryDataTask::TAG_NAME,
            'mapping'   => [
                GetLotteryDataTask::class,
                [
                    FlowNodeInterface::BPMN_PROPERTY_INCOMING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_INCOMING]],
                    FlowNodeInterface::BPMN_PROPERTY_OUTGOING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_OUTGOING]],
                    ActivityInterface::BPMN_PROPERTY_LOOP_CHARACTERISTICS => ['1', LoopCharacteristicsInterface::class],
                    ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION     => ['1', [BpmnDocument::BPMN_MODEL, ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION]],
                ],
            ]
        ],
        [
            'namespace' => self::NAMESPACE,
            'tagName'   => CreateBetAmountTask::TAG_NAME,
            'mapping'   => [
                CreateBetAmountTask::class,
                [
                    FlowNodeInterface::BPMN_PROPERTY_INCOMING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_INCOMING]],
                    FlowNodeInterface::BPMN_PROPERTY_OUTGOING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_OUTGOING]],
                    ActivityInterface::BPMN_PROPERTY_LOOP_CHARACTERISTICS => ['1', LoopCharacteristicsInterface::class],
                    ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION     => ['1', [BpmnDocument::BPMN_MODEL, ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION]],
                ],
            ]
        ],
        [
            'namespace' => self::NAMESPACE,
            'tagName'   => CreateBetCodeTask::TAG_NAME,
            'mapping'   => [
                CreateBetCodeTask::class,
                [
                    FlowNodeInterface::BPMN_PROPERTY_INCOMING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_INCOMING]],
                    FlowNodeInterface::BPMN_PROPERTY_OUTGOING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_OUTGOING]],
                    ActivityInterface::BPMN_PROPERTY_LOOP_CHARACTERISTICS => ['1', LoopCharacteristicsInterface::class],
                    ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION     => ['1', [BpmnDocument::BPMN_MODEL, ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION]],
                ],
            ]
        ],
        [
            'namespace' => self::NAMESPACE,
            'tagName'   => BetTask::TAG_NAME,
            'mapping'   => [
                BetTask::class,
                [
                    FlowNodeInterface::BPMN_PROPERTY_INCOMING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_INCOMING]],
                    FlowNodeInterface::BPMN_PROPERTY_OUTGOING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_OUTGOING]],
                    ActivityInterface::BPMN_PROPERTY_LOOP_CHARACTERISTICS => ['1', LoopCharacteristicsInterface::class],
                    ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION     => ['1', [BpmnDocument::BPMN_MODEL, ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION]],
                ],
            ]
        ],
        [
            'namespace' => self::NAMESPACE,
            'tagName'   => FormActivityTask::TAG_NAME,
            'mapping'   => [
                FormActivityTask::class,
                [
                    FlowNodeInterface::BPMN_PROPERTY_INCOMING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_INCOMING]],
                    FlowNodeInterface::BPMN_PROPERTY_OUTGOING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_OUTGOING]],
                    ActivityInterface::BPMN_PROPERTY_LOOP_CHARACTERISTICS => ['1', LoopCharacteristicsInterface::class],
                    ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION     => ['1', [BpmnDocument::BPMN_MODEL, ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION]],
                ],
            ]
        ],
        [
            'namespace' => self::NAMESPACE,
            'tagName'   => StartEventService::TAG_NAME,
            'mapping'   => [
                StartEventService::class,
                [
                    FlowNodeInterface::BPMN_PROPERTY_INCOMING       => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_INCOMING]],
                    FlowNodeInterface::BPMN_PROPERTY_OUTGOING       => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_OUTGOING]],
                    EventInterface::BPMN_PROPERTY_EVENT_DEFINITIONS => ['n', EventDefinitionInterface::class],
                ],
            ]
        ],
    ];

    protected readonly BpmnEngine $bpmnEngine;

    public function __construct(
        protected readonly RepositoryEngine $repositoryEngine,
        protected readonly EventEngine      $eventEngine,
        protected readonly BpmnDocument     $bpmnDocument,
        protected string                    $xml = '',
        protected string                    $id = '',
        protected array                     $data = [],
    )
    {

        // 实例化引擎
        $this->bpmnEngine = new BpmnEngine($this->repositoryEngine, $this->eventEngine);

        // 注册自定义活动处理器
        $this->registerBpmnElementMappings();

        $this->bpmnDocument->setEngine($this->bpmnEngine);
        $this->bpmnDocument->setFactory($this->repositoryEngine);

    }


    public function setXml(string $xml): static
    {
        $this->xml = $xml;
        return $this;
    }

    public function setId(string $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function setData(array $data): static
    {
        $this->data = $data;
        return $this;
    }


    public function handle()
    {


        $this->bpmnDocument->loadXML($this->xml);


        //获取对流程的引用
        $process = $this->bpmnDocument->getProcess($this->id);

        //创建数据存储
        $dataStore = $this->repositoryEngine->createDataStore();
        $dataStore->setData($this->data);


        //创建流程实例
        $instance = $this->bpmnEngine->createExecutionInstance($process, $dataStore);

        //触发启动事件
        $start = $this->bpmnDocument->getStartEvent('startEvent_1');
        $start->start($instance);


        //执行令牌并运行到下一个状态
        $this->bpmnEngine->runToNextState();

        //一个令牌到达第一个任务
        $firstTask = $this->bpmnDocument->getScriptTask('scriptTask_1');
        $token     = $firstTask->getTokens($instance)->item(0);

//        完成第一个任务
        $firstTask->runScript($token);


        //执行令牌并运行到下一个状态
        $this->bpmnEngine->runToNextState();

        //一个令牌到达第一个任务
        $firstTask = $this->bpmnDocument->getServiceTask('serviceTask_1');
        $token     = $firstTask->getTokens($instance)->item(0);

        //        完成第一个任务
        $firstTask->run($token);

        dd('ok');

        // 运行流程
        while ($process->getActivities()) {
            $this->bpmnEngine->runToNextState();
        }


//        $this->repositoryEngine->addDocument($this->bpmnDocument);
//
//        $process = $this->bpmnDocument->getElementsByTagNameNS(BpmnDocument::BPMN_MODEL, $id)->item(0);
//
//        $document = new BpmnDocument();
//        $document->load('path/to/your/bpmn-file.bpmn');
//
//// 创建引擎和存储库
//        $repository = new BpmnDocumentRepository();
//        $engine = new Engine($repository);
//
//// 注册流程定义
//        $repository->addDocument($document);
//        $process = $document->getElementsByTagNameNS(BpmnDocument::BPMN_MODEL, 'process')->item(0);
//        $processId = $process->getAttribute('id');
//        $processDefinition = $repository->getProcess($processId);
//
//// 创建流程实例并运行
//        $instance = $engine->createExecutionInstance($processDefinition);
//        $engine->runToNextState();
//
//// 运行流程
//        while ($engine->hasPendingActivities()) {
//            $engine->runToNextState();
//        }

    }


    public function handle2()
    {


//        Cache::set('xml',$this->xml);

        // 加载 BPMN 文档
        $this->bpmnDocument->loadXML($this->xml);

        // 替换 BPMN 文档中的元素
        $this->replaceBpmElement();

        // 从 BPMN 文档中获取流程定义
        /**
         * @var Process $process
         */
        $process = $this->bpmnDocument->getProcess('Process_1');

        $executionInstanceInterface = $process->call();


        $process->getDispatcher()->listen('ServiceTaskActivated',function (string $event, ServiceTaskActivatedEvent $serviceTaskActivatedEvent){

            $serviceTaskActivatedEvent->activatedEvent(); //BeforeTransit
        });

        $process->getDispatcher()->listen('GatewayActivated',function (string $event, $payload){

            dump($event,$payload[0] ? $payload[0]->getProperty('id') : '');
        });

        $process->getDispatcher()->listen('BeforeTransit',function (string $event, $payload){
            dump(
                $event,
                $payload
            );

        });//\ProcessMaker\Nayra\Bpmn\ConditionedExclusiveTransition  ConditionedTransition

        $process->getDispatcher()->listen('ConditionedTransition',function (string $event, $payload){

            dump($event,$payload[1] ? $payload[1]->getProperty('id') : '');
        });


        $this->bpmnEngine->runToNextState();

        dd('end');

        $getActivities = $process->getActivities();

        foreach ($getActivities as $getActivity) {

            $this->bpmnEngine->runToNextState();

            /**
             * @var ActivityInterface $getActivity
             */
            $token = $getActivity->getTokens($executionInstanceInterface);

            if ($token->count()){
                switch ($getActivity) {
                    case $getActivity instanceof ServiceTaskInterface:
                        $getActivity->run($token->item(0));
                        break;
                    default:
                        $getActivity->complete($token->item(0));
                }
            }
        }

        $this->bpmnEngine->runToNextState();


//        $executionInstanceInterface->getProcess()->getEngine()->runToNextState();


        /**
         * @var Process $process
         */
//        $process = $this->bpmnDocument->getElementsByTagName('process')->item(0)->getBpmnElementInstance();


        dd('end');


        // 创建数据存储
        $dataStore = $this->repositoryEngine->createDataStore();

        // 启动流程
        $instance = $this->bpmnEngine->createExecutionInstance($process, $dataStore);


        $startEvent = $this->bpmnDocument->getElementsByTagName('startEvent')->item(0)->getBpmnElementInstance();
        $startEvent->start($instance);

        $this->bpmnEngine->runToNextState();

        //运行下一个状态


        /**
         * 获取流程中的所有元素
         * @var BpmnElement $element
         */
        $elements = $this->bpmnDocument->getElementsByTagName('*');


        foreach ($elements as $element) {
            // 获取元素类型
            $elementType = $element->localName;

            // 根据元素类型执行相应的操作
            switch ($elementType) {
                case 'exclusiveGateway':
                    // 在这里处理独占网关

                    break;
                case 'inclusiveGateway':
                    // 在这里处理包容网关

                    /**
                     * @var InclusiveGateway $gatewayInstance
                     */
                    $gatewayInstance = $element->getBpmnElementInstance();

                    $this->bpmnEngine->loadProcess($gatewayInstance->getProcess());

                    // 获取从网关出去的顺序流
                    $outgoingFlows = $gatewayInstance->getProperty('outgoing');

                    // 遍历所有从网关出去的顺序流
                    foreach ($outgoingFlows as $outgoingFlow) {
                        /**
                         * 获取顺序流的 extensionElements
                         * @var Flow $outgoingFlow
                         */
                        $extensionElements = $outgoingFlow->getBpmnElement()->getElementsByTagNameNS('*', 'extensionElements');

                        // 遍历所有 extensionElements
                        foreach ($extensionElements as $extensionElement) {
                            // 获取 properties
                            $properties = $extensionElement->getElementsByTagNameNS('*', 'property');

                            // 遍历所有 properties
                            foreach ($properties as $property) {
                                // 获取 name 和 value 属性
                                $name  = $property->getAttribute('name');
                                $value = $property->getAttribute('value');

                                // 在这里创建自定义条件
                                $condition = function ($token) use ($name, $value, $instance) {

                                    // 在这里实现自定义条件逻辑
                                    $ruleName  = explode(',', $name)[0];
                                    $dataStore = $instance->getDataStore();

                                    $model = $dataStore->getData('temporary_table');

                                    $ruleData = $model->getAttribute($ruleName);

                                    $response = preg_match($value, $ruleData);

                                    return $response;
                                };

                                $outgoingFlow->setProperties([
                                    FlowInterface::BPMN_PROPERTY_CONDITION_EXPRESSION => $condition,
                                    FlowInterface::BPMN_PROPERTY_IS_DEFAULT           => $outgoingFlow->isDefault()
                                ]);
                            }
                        }
                    }

                    break;
                case 'sequenceFlow':
                    // 在这里处理顺序流

                    break;

                case 'scriptTask':
                    // 在这里处理任务

                    /**
                     * @var ScriptTask $script
                     */
                    $script = $element->getBpmnElementInstance();

                    $script->runScript($script->getTokens($instance));

                    break;

                case 'serviceTask':

                    /**
                     * @var ServiceTask $bpmnElementInstance 在这里处理用户任务
                     */
                    $bpmnElementInstance = $element->getBpmnElementInstance();
                    $bpmnElementInstance->run($bpmnElementInstance->getTokens($instance));

                    break;
                case 'betTask':

                    /**
                     * @var GetLotteryDataTask $bpmnElementInstance 在这里处理用户任务
                     */
                    $bpmnElementInstance = $element->getBpmnElementInstance();

                    $bpmnElementInstance->run($bpmnElementInstance->getTokens($instance)->item(0));

                    break;
                case 'endEvent':
                    // 在这里处理结束事件

                    break;

                // 在这里添加其他元素类型的处理

            }
            // 继续执行令牌并运行到下一个状态
            $this->bpmnEngine->runToNextState();
        }


        $exclusiveGateway = $this->bpmnDocument->getExclusiveGateway('Gateway_0yjid4i');
        $exclusiveGateway->getTokens($instance)->item(0);


        $token = $instance->getTokens()->item(0);

        $variables = 0;

        while ($token !== null) {
            // 获取令牌对应的元素（任务、事件等）
            $element = $token->getOwnerElement();

            // 根据元素类型执行相应的操作
            switch (get_class($element)) {
                case 'ProcessMaker\Nayra\Bpmn\Task': // 任务
                    // 在这里执行任务相关操作（如调用其他服务、发送邮件等）
                    break;
                case ExclusiveGatewayInterface::class: // 排他网关
                    // 在这里执行排他网关相关操作（如根据条件选择分支）

                    $element->createConditionedFlowTo($variables); // 将运行时变量传递给网关

                    break;
                // ...
            }

            $this->bpmnEngine->runToNextState();

            // 获取下一个待执行的令牌（如果有）
            $token = $instance->getTokens()->item(0);
        }

    }

    private function runWorkflow($repository, $instance)
    {
        // 获取第一个待执行的令牌
        $token = $instance->getTokens()->item(0);

        // 循环遍历并执行工作流中的所有元素
        while ($token !== null) {
            // 获取令牌对应的元素（任务、事件等）
            $element = $token->getOwnerElement();

            // 根据元素类型执行相应的操作
            switch (get_class($element)) {
                case 'ProcessMaker\Nayra\Bpmn\Task': // 任务
                    // 在这里执行任务相关操作（如调用其他服务、发送邮件等）
                    break;
                case 'ProcessMaker\Nayra\Bpmn\ExclusiveGateway': // 排他网关
                    // 在这里执行排他网关相关操作（如根据条件选择分支）
                    break;
                // ...
            }

            // 执行元素的行为
            $element->run($instance);

            // 获取下一个待执行的令牌（如果有）
            $token = $instance->getTokens()->item(0);
        }
    }


    /**
     * @return void
     */
    public function registerBpmnElementMappings(): void
    {
        foreach ($this->tasks as $mapping) {
            $this->bpmnDocument->setBpmnElementMapping($mapping['namespace'], $mapping['tagName'], $mapping['mapping']);
        }
    }

    public function registerBpmnElementMapping(): void
    {

        $bpmnDocument = new BpmnDocument(new RepositoryEngine(), new BpmnEngine());

        $bpmnDocument->setBpmnElementMapping('http://www.omg.org/spec/BPMN/20100524/MODEL', FormActivityTask::TAG_NAME, [
            FormActivityTask::class,
            [
                FlowNodeInterface::BPMN_PROPERTY_INCOMING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_INCOMING]],
                FlowNodeInterface::BPMN_PROPERTY_OUTGOING             => ['n', [BpmnDocument::BPMN_MODEL, FlowNodeInterface::BPMN_PROPERTY_OUTGOING]],
                ActivityInterface::BPMN_PROPERTY_LOOP_CHARACTERISTICS => ['1', LoopCharacteristicsInterface::class],
                ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION     => ['1', [BpmnDocument::BPMN_MODEL, ActivityInterface::BPMN_PROPERTY_IO_SPECIFICATION]],
            ],
        ]);
    }


    public function replaceBpmElement(): void
    {
        // 获取BPMN XML中所有的serviceTask元素
        $serviceTasks = $this->bpmnDocument->getElementsByTagName('serviceTask');

        // 创建一个数组来存储所有的serviceTask元素
        $serviceTasksArray = [];
        foreach ($serviceTasks as $serviceTask) {
            $serviceTasksArray[] = $serviceTask;
        }

        // 遍历并处理每个serviceTask元素
        foreach ($serviceTasksArray as $serviceTask) {
            // 获取serviceTask元素的name属性的值
            $name = $serviceTask->getAttribute('name');
            // 获取serviceTask元素的 namespaceURI 属性的值
            $namespaceURI = $serviceTask->namespaceURI;

            // 创建一个新的DOM元素，使用name属性的值作为标签名
            $newElement = $this->bpmnDocument->createElementNS($namespaceURI, $name);

            // 为新元素设置name,namespaceURI 属性
            $newElement->setAttribute('name', $name);

            // 将serviceTask元素的所有属性复制到新元素
            foreach ($serviceTask->attributes as $attribute) {
                $newElement->setAttribute($attribute->name, $attribute->value);
            }

            // 将serviceTask元素的所有子元素移动到新元素
            while ($serviceTask->firstChild) {
                $newElement->appendChild($serviceTask->firstChild);
            }

            // 使用新元素替换原始的serviceTask元素
            $serviceTask->parentNode->replaceChild($newElement, $serviceTask);
        }
    }

}
