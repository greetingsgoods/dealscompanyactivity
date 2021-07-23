<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


use Bitrix\Main\Application;
use Bitrix\Main\Loader;

$request = Application::getInstance()->getContext()->getRequest();


class CBPGetActivityFromEntity
	extends CBPActivity

{
	private $taskId = 0;
	private $isInEventActivityMode = false;

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

	public static function ValidateProperties($arTestProperties = array(), CBPWorkflowTemplateUser $user = null)
	{
		$arErrors = array();


		return $arErrors;
	}

	public static function GetPropertiesDialog($documentType, $activityName, $arWorkflowTemplate, $arWorkflowParameters, $arWorkflowVariables, $arCurrentValues = null, $formName = "", $popupWindow = null)
	{

		$runtime = CBPRuntime::GetRuntime();
		$documentService = $runtime->GetService("DocumentService");

		$arMap = array(
			"EntitytID" => "entity_id",
			"EntityType" => "entity_type_id"
		);

		if (!is_array($arWorkflowParameters))
			$arWorkflowParameters = array();
		if (!is_array($arWorkflowVariables))
			$arWorkflowVariables = array();

		if (!is_array($arCurrentValues)) {
			$arCurrentValues = array();
			$arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
			if (is_array($arCurrentActivity["Properties"])) {
				foreach ($arMap as $k => $v) {
					if (array_key_exists($k, $arCurrentActivity["Properties"])) {

						$arCurrentValues[$arMap[$k]] = $arCurrentActivity["Properties"][$k];
					} else {
						$arCurrentValues[$arMap[$k]] = "";
					}
				}

			} else {
				foreach ($arMap as $k => $v)
					$arCurrentValues[$arMap[$k]] = "";
			}
		}

		$arFieldTypes = $documentService->GetDocumentFieldTypes($documentType);
		$arDocumentFields = $documentService->GetDocumentFields($documentType);


		return $runtime->ExecuteResourceFile(
			__FILE__,
			"properties_dialog.php",
			array(
				"arCurrentValues" => $arCurrentValues,
				"arDocumentFields" => $arDocumentFields,
				"arFieldTypes" => $arFieldTypes,
				"javascriptFunctions" => $javascriptFunctions,
				"formName" => $formName,
				"popupWindow" => &$popupWindow,
			)
		);

	}

	public static function GetPropertiesDialogValues($documentType, $activityName, &$arWorkflowTemplate, &$arWorkflowParameters, &$arWorkflowVariables, $arCurrentValues, &$arErrors)
	{
		$arErrors = array();

		$runtime = CBPRuntime::GetRuntime();

		$arMap = array(
			"entity_id" => "EntitytID",
			"entity_type_id" => "EntityType"
		);

		$arProperties = array();
		foreach ($arMap as $key => $value) {

			$arProperties[$value] = $arCurrentValues[$key];
		}

		$arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
		$arCurrentActivity["Properties"] = $arProperties;


		return true;
	}

	public function Execute()
	{
		if (
			true
			&& Loader::includeModule('crm')
		) {

			$result = CCrmActivity::getList(
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

?>
