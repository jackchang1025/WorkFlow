InclusiveGateway.php 文件定义了一个名为 InclusiveGateway 的类，实现了 InclusiveGatewayInterface 接口。这个类主要负责对应 BPMN 中的 Inclusive Gateway 元素。Inclusive Gateway 是一种网关，用于在多个并发传入分支和多个传出分支之间同步一个特定子集。这个类使用了 InclusiveGatewayTrait 和 ConditionedGatewayTrait 特性来实现这个功能。
InclusiveGateway 类具有以下方法：

createConditionedFlowTo()：创建一个有条件的流向目标节点的连接。
getBpmnEventClasses()：返回 BPMN 元素的自定义事件类的数组映射。
InclusiveGatewayTrait.php 文件定义了一个名为 InclusiveGatewayTrait 的特性。这个特性为 InclusiveGateway 类提供了一些实现方法。它使用了 ConditionedGatewayTrait 特性，该特性提供了处理条件网关的基本行为。
InclusiveGatewayTrait 包含以下方法：

buildTransitions()：构建定义元素的转换。
getInputPlace()：获取元素的输入。
buildConnectionTo()：创建到目标节点的连接。
buildConditionedConnectionTo()：添加一个具有条件的转换到 InclusiveGateway。
ConditionedGatewayTrait.php 文件定义了一个名为 ConditionedGatewayTrait 的特性，它实现了具有条件的网关的基本行为。这个特性与 InclusiveGatewayTrait 和 InclusiveGateway 类一起使用，以便实现条件网关的逻辑。
ConditionedGatewayTrait 包含以下方法：

initConditionedGatewayTrait()：初始化 ConditionedGatewayTrait。
conditionedTransition()：添加输出条件转换。
setDefaultTransition()：设置默认输出转换。
buildFlowTransitions()：构建流程转换以接受网关条件转换。
getConditionedTransitions()：返回网关的条件转换列表。
当您使用 Nayra 包创建一个 InclusiveGateway 实例时，这些特性和类一起协同工作，以实现包含网关的功能。在执行过程中，代码会根据条件和默认转换计算路径，以便在多个传入和传出分支之间正确同步。

<InclusiveGateway id="Gateway_0yjid4i" name="cutOffRule">
      <extensionElements>
        <camunda:executionListener class="" event="start" />
        <camunda:properties>
          <camunda:property />
        </camunda:properties>
        <zeebe:properties>
          <zeebe:property />
          <zeebe:property name="bet_lottery_rule-投注次数规则大于等于10" value="/\b([1-9]\d+)\b/" />
        </zeebe:properties>
      </extensionElements>
      <incoming>Flow_0bb48ok</incoming>
      <outgoing>Flow_0gyacca</outgoing>
      <outgoing>Flow_1t9xm15</outgoing>
    </InclusiveGateway>
    <endEvent id="Event_1h15zt9">
      <incoming>Flow_0gyacca</incoming>
    </endEvent>
    <sequenceFlow id="Flow_0gyacca" name="yes" sourceRef="Gateway_0yjid4i" targetRef="Event_1h15zt9">
      <extensionElements>
        <zeebe:properties>
          <zeebe:property name="total_amount_rule-总金额规则大于等于2000" value="/\b([2-9]\d{3,}|\d{5,})\b/" />
        </zeebe:properties>
      </extensionElements>
    </sequenceFlow>
    <sequenceFlow id="Flow_1t9xm15" sourceRef="Gateway_0yjid4i" targetRef="Activity_0hqeqff">
      <extensionElements>
        <zeebe:properties>
          <zeebe:property name="AAAAA" value="/AAAA/" />
        </zeebe:properties>
      </extensionElements>
    </sequenceFlow>
    <sequenceFlow id="Flow_0bb48ok" sourceRef="StartEvent_1y45yut" targetRef="Gateway_0yjid4i" />

在上面 BPMN XML 中，我们可以看到一个 InclusiveGateway 元素，
它有两个输出流 id 分别为 Flow_0gyacca，Flow_1t9xm15。 在 Flow_0gyacca 和 Flow_1t9xm15 的 extensionElements 中有 properties，properties 属性中有 name,value，在下面代码中处理 inclusiveGateway 网关时候，现在根据你学习的最新代码，自定义条件逻辑，通过 name 和 value 来判断是否满足条件，满足条件则走对应的流向。

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
                         * @var BetTask $bpmnElementInstance 在这里处理用户任务
                         */
                        $bpmnElementInstance = $element->getBpmnElementInstance();

                        $bpmnElementInstance->run($bpmnElementInstance->getTokens($instance));

                    break;
                case 'endEvent':
                    // 在这里处理结束事件

                    break;

                // 在这里添加其他元素类型的处理

            }
            // 继续执行令牌并运行到下一个状态
            $this->bpmnEngine->runToNextState();
        }


- total_amount_rule-总金额规则大于等于2000 /\b([2-9]\d{3,}|\d{5,})\b/
- bet_lottery_rule-投注次数规则大于等于10 /\b([1-9]\d+)\b/
- lottery_rule-AAA /([\x{4e00}-\x{9fa5}_a-zA-Z0-9])\1{2,}$/u
- win_lose_rule-输 /^0$/
- win_lose_rule-赢 /^1$/
- total_amount_rule-总金额规则小于等于0 /^(-\d+|-?\d*\.\d+|0)$/

