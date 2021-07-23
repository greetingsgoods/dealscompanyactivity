<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>
<?
/*
const Lead = 1;    // refresh FirstOwnerType and LastOwnerType constants
   const Deal = 2;
   const Contact = 3;
   const Company = 4;
   const Invoice = 5;
   const Activity = 6;
   const Quote = 7;
*/


$arrTypes = array
(
	"1" => "TITLE_LEAD",
	"2" => "TITLE_DEAL",
	"3" => "TITLE_CONTACT",
	"4" => "TITLE_COMPANY",
	"5" => "TITLE_INVOICE",
);
?>
<table width="100%" border="0" cellpadding="2" cellspacing="2">
    <tr>

        <td align="right" width="40%"><?= GetMessage("BPGP_ENTITY_ID") ?>:</td>
        <td width="60%">
            <input type="text" size="50" id="id_entity_id" name="entity_id"
                   value="<?= $arCurrentValues['entity_id'] ?>">
            <input type="button" value="..." onclick="BPAShowSelector('id_entity_id', 'string');">

        </td>

    </tr>
    <tr>

        <td align="right" width="40%"><?= GetMessage("BPGP_ENTITY_TYPE") ?>:</td>
        <td width="60%">
			<? foreach ($arrTypes as $type_id => $name): ?>
                <input type="radio" name="entity_type_id"
                       value="<?= $type_id ?>" <? if ($type_id == $arCurrentValues['entity_type_id']) echo " checked " ?>><?= GetMessage($name) ?>
                <Br>
			<? endforeach; ?>
        </td>

    </tr>

</table>
