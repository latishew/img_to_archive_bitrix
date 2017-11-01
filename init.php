<?
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("ArcivesCreator", "OnAfterIBlockElementAddHandler"));

class ArcivesCreator
{  
    function OnAfterIBlockElementAddHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == 13){
            if ($arFields["RESULT"]) {
                if(!CModule::IncludeModule("iblock"))
                return;

                $arFilter = Array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "ID"=>$arFields["RESULT"]);
                $res = CIBlockElement::GetList(Array(), $arFilter);
                if ($ob = $res->GetNextElement()){                  
                    $arProps = $ob->GetProperties();                  
                   }
                
                foreach ($arProps["PHOTOS"]["VALUE"] as $key => $arFiles) {                  

                    $rsFile = CFile::GetByID($arFiles);
                    $filesPath[$key] = $rsFile->Fetch();
                    $filesPath[$key]["SRC"] = CFile::GetPath($arFiles);
                }
                $archpath = '../../my_archive'.$arFields["RESULT"].'.zip';
                $zip = new ZipArchive;
                
                if ($zip->open($archpath, ZipArchive::CREATE) === TRUE){                 
                   foreach ($filesPath as $keyPath => $valuePath) {
                                $zip->addFile("../..".$valuePath["SRC"], $valuePath["FILE_NAME"]);
                            }                 
                   $zip->close();               

                   $arFile = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/my_archive'.$arFields["RESULT"].'.zip');
                   CIBlockElement::SetPropertyValueCode($arFields["RESULT"], "ARCHIVE", $arFile);               
                }
                    unlink($_SERVER["DOCUMENT_ROOT"].'/my_archive'.$arFields["RESULT"].'.zip');
            }           

        }       
        
    }
}

AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("ArcivesUpdater", "OnAfterIBlockElementUpdateHandler"));
class ArcivesUpdater
{  
    function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == 13){
            if ($arFields["ID"]) {
                if(!CModule::IncludeModule("iblock"))
                return;

                $arFilter = Array("IBLOCK_ID"=>$arFields["IBLOCK_ID"], "ID"=>$arFields["ID"]);
                $res = CIBlockElement::GetList(Array(), $arFilter);
                if ($ob = $res->GetNextElement()){                  
                    $arProps = $ob->GetProperties();                  
                   }
                
                foreach ($arProps["PHOTOS"]["VALUE"] as $key => $arFiles) {                  

                    $rsFile = CFile::GetByID($arFiles);
                    $filesPath[$key] = $rsFile->Fetch();
                    $filesPath[$key]["SRC"] = CFile::GetPath($arFiles);
                }
                $archpath = '../../my_archive'.$arFields["ID"].'.zip';
                $zip = new ZipArchive;
                
                if ($zip->open($archpath, ZipArchive::CREATE) === TRUE){                 
                   foreach ($filesPath as $keyPath => $valuePath) {
                                $zip->addFile("../..".$valuePath["SRC"], $valuePath["FILE_NAME"]);
                            }                 
                   $zip->close();               

                   $arFile = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].'/my_archive'.$arFields["ID"].'.zip');
                   CIBlockElement::SetPropertyValueCode($arFields["ID"], "ARCHIVE", $arFile);               
                }
                    unlink($_SERVER["DOCUMENT_ROOT"].'/my_archive'.$arFields["ID"].'.zip');              
                
            }
        }        
    }
}

// // при включенном композитном режиме, сохраняем в кеш, контент нужный для отдачи аяксом
// \Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible('main', 'OnEndBufferContent', function(&$content){
//     if (version_compare(SM_VERSION, '14.5.0') >= 0 && CHTMLPagesCache::IsCompositeEnabled()) {
//         if (isset($_SERVER['HTTP_X_REQUESTED_WITH'], $_GET['AJAX_PAGE']) 
//             && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
 
//             list(, $content_html) = explode('<!--RestartBuffer-->', $content);
 
//             if(is_string($content_html) && strlen($content_html)){
//                 $content = $content_html;
//             }
//         }
//     }
// });
?>