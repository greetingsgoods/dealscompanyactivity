"# Подсчет дел у сущности

Решение на основе выборки из поста

    /*
    
    array (
    'template_id' => '18',
    'sessid' => 'e21eed4d4df81e339da95bad98b70334',
    'site' => 's1',
    'ajax_action' => 'start_workflow',
    'module_id' => 'crm',
    'entity' => 'CCrmDocumentDeal',
    'document_type' => 'DEAL',
    'document_id' => 'DEAL_49',
    )
    
    */

Скрипт

    use \Bitrix\Main\Application;
    use \Bitrix\Main\Loader;
    
    $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
    
    
    class BPGetActivityFromEntity
    extends CBPActivity
    {
    private $taskId = 0;
    
        // private $isInEventActivityMode = false;
    
        public function __construct($name)
        {
            parent::__construct($name);
            $this->arProperties = array(
                "Title" => "",
                "EntitytID" => null,
                "EntityType" => null,
                "AllActivity" => null,
                "UnCompletedActivity" => null
    
            );
            $this->EntityType = ucwords(strtolower($request->getPost('document_type'))); //\CCrmOwnerType::Deal
            $this->EntitytID = filter_var($request->getPost('document_id'), FILTER_SANITIZE_NUMBER_INT);
            $this->totalActivityNotComplete = 0;
            $this->totalActivity = 0;
        }
    
        public function Execute()
        {
            if (
                true
                && Loader::includeModule('crm')
            ) {
    
                $result = \CCrmActivity::getList(
                    [],
                    [
                        "OWNER_ID" => $this->EntitytID,
                        "OWNER_TYPE_ID" => $this->EntityType,
                        'CHECK_PERMISSIONS' => 'N'
                    ]
                );
    
                while ($activity = $result->fetch()) {
                    $this->totalActivity++;
                    if ($activity["COMPLETED"] == "N") {
                        $this->totalActivityNotComplete++;
                    }
                }
                return CBPActivityExecutionStatus::Closed;
            }
        }
    }
    
    $result = new BPGetActivityFromEntity();
    $exec = $result->Execute();
