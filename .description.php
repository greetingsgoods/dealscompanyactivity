<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arActivityDescription = array(
	"NAME" => GetMessage("GET_ACTIVITY_FROM_ENTITY"),
	"DESCRIPTION" => GetMessage("GET_ACTIVITY_FROM_ENTITY"),
	"TYPE" => "activity",
	"CLASS" => "GetActivityFromEntity",
	"JSCLASS" => "BizProcActivity",
	"CATEGORY" => array(
		"ID" => "other",
	),
	"RETURN" => array(
		"AllActivity" => array(
			"NAME" => GetMessage("ALL_ACTIVITY"),
			"TYPE" => "int",
		),
		"UnCompletedActivity" => array(
			"NAME" => GetMessage("UNCOMPLETED_ACTIVITY"),
			"TYPE" => "int",
		),
	),
);
?>
