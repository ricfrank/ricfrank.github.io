<?php

class ContentService
{
    private static $PRODUCT_INFO = array(
        'template_id' => '{%FCMS_TEMPLATE_ID%}',
        'order_id' => '{%ORDER_ID%}',
        'product_id' => '{%FCMS_PRODUCT_ID%}',
    );
    protected static $_productInformation;

    public function __construct()
    {
    }

    protected static function _loadProductInformation()
    {
        $data = null;
        $filePath = MOTO_ADMIN_DIR . '/config/product_information.php';
        if (file_exists($filePath)) {
            $level = error_reporting();
            error_reporting(0);
            try {
                $data = require $filePath;
            } catch (\Exception $e) {
            }
            error_reporting($level);
        }
        if (!is_array($data)) {
            $data = array(
                'product_type' => 'html',
                'product_id' => 'null',
                'template_id' => 1,
                'source' => 'auto',
                'created_at' => time(),
            );
            if (empty(static::$PRODUCT_INFO['product_id']) || static::$PRODUCT_INFO['product_id'] === '{%FCMS_' . 'PRODUCT_ID%}') {
                $data['product_id'] = substr('_' . md5(__FILE__ . time()), 0, 32);
            } else {
                $data['product_id'] = static::$PRODUCT_INFO['product_id'];
                $data['template_id'] = static::$PRODUCT_INFO['template_id'];
                $data['source'] = 'file';
            }
            $content = '<?php return ' . var_export($data, true) . ';';
            file_put_contents($filePath, $content);
            clearstatcache();
            MotoUtil::fixFilePermission($filePath);
        }
        if (is_array($data)) {
            static::$_productInformation = $data;
            return true;
        }
        return false;
    }

    public static function getProductInformation()
    {
        if (!is_array(static::$_productInformation)) {
            static::_loadProductInformation();
        }
        if (is_array(static::$_productInformation)) {
            return static::$_productInformation;
        }
        return static::$PRODUCT_INFO;
    }

    public function getProductInfo()
    {
        return static::getProductInformation();
    }

    public function getContent()
    {
        $_SESSION['MOTO_UPDATE'] = array('newVersion' => null, 'url' => null, 'description' => null);
        $responseVO = new ResponseVO();
        $responseVO->status = new StatusVO();
        try {
            define('IS_SMART_GETCONTENT', true);
            $responseVO->result = new ContentVO(new MotoXML(CONTENT_RESOURSE_PATH));
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (DOMException $e) {
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_XML;
            $responseVO->status->message = $e->getMessage();
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function savePage(PageVO $page, LayoutVO $layout, WebsiteVO $website)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            $dom = new MotoXML(CONTENT_RESOURSE_PATH);
            $page->updateDomElement(MotoXML::findOneByXPath('//pages', $dom));
            $layout->updateDomElement(MotoXML::findOneByXPath('//layouts', $dom));
            $website->updateDomElement(MotoXML::findOneByXPath('//motoContent', $dom));
            MotoXML::putXML($dom, CONTENT_RESOURSE_PATH);
            MotoCache::getInstance()->clean(MotoCache::CLEANING_MODE_MATCHING_TAG, array('page', 'page_' . $page->id, 'layout_' . $layout->id, 'website'), __FUNCTION__);
            MotoUtil::setLoadEncodedDataFalse();
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function removePage($pageId)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            $dom = new MotoXML(CONTENT_RESOURSE_PATH);
            $page = PageVO::findById($pageId, $dom);
            if ($page) {
                $page->remove();
                MotoXML::putXML($dom, CONTENT_RESOURSE_PATH);
                MotoCache::getInstance()->clean(MotoCache::CLEANING_MODE_MATCHING_TAG, array('page_' . $pageId), __FUNCTION__);
                MotoUtil::setLoadEncodedDataFalse();
            }
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function savePopup(PopupVO $popup)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            $dom = new MotoXML(CONTENT_RESOURSE_PATH);
            $popup->updateDomElement(MotoXML::findOneByXPath('//popups', $dom));
            MotoXML::putXML($dom, CONTENT_RESOURSE_PATH);
            MotoCache::getInstance()->clean(MotoCache::CLEANING_MODE_MATCHING_TAG, array('popup', 'popup_' . $popup->id), __FUNCTION__);
            MotoUtil::setLoadEncodedDataFalse();
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function removePopup($popupId)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            $dom = new MotoXML(CONTENT_RESOURSE_PATH);
            $popup = PopupVO::findById($popupId, $dom);
            if ($popup) {
                $popup->remove();
                MotoXML::putXML($dom, CONTENT_RESOURSE_PATH);
                MotoCache::getInstance()->clean(MotoCache::CLEANING_MODE_MATCHING_TAG, array('popup_' . $popupId), __FUNCTION__);
                MotoUtil::setLoadEncodedDataFalse();
            }
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function removePopups($popupIds)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            $dom = new MotoXML(CONTENT_RESOURSE_PATH);
            $result = array();
            $updateXML = false;
            foreach ($popupIds as $popupId) {
                $popup = PopupVO::findById($popupId, $dom);
                if ($popup) {
                    MotoCache::getInstance()->clean(MotoCache::CLEANING_MODE_MATCHING_TAG, array('popup_' . $popupId), __FUNCTION__);
                    $popup->remove();
                    $updateXML = true;
                    array_push($result, $popupId);
                }
            }
            if ($updateXML) {
                MotoXML::putXML($dom, CONTENT_RESOURSE_PATH);
                MotoUtil::setLoadEncodedDataFalse();
            }
            $responseVO->result = $result;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function savePagesInfo($pages)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            $dom = new MotoXML(CONTENT_RESOURSE_PATH);
            $pagesNode = MotoXML::findOneByXPath('//pages', $dom);
            foreach ($pages as $page) {
                $pageNode = MotoXML::findOneByXPath(".//page[@id='{$page->id}']", $pagesNode);
                if (!is_null($pageNode)) {
                    $pageNode->setAttribute('parent', (string) $page->parent);
                    $pageNode->setAttribute('order', (integer) $page->order);
                    if ((string) MotoUtil::boolToString($page->noIndex) == 'true') $pageNode->setAttribute('noIndex', (string) MotoUtil::boolToString($page->noIndex)); else $pageNode->removeAttribute('noIndex');
                    if ((string) MotoUtil::boolToString($page->noFollow) == 'true') $pageNode->setAttribute('noFollow', (string) MotoUtil::boolToString($page->noFollow)); else $pageNode->removeAttribute('noFollow');
                    $pageNodeName = MotoXML::findOneByXPath("./name", $pageNode);
                    $pageNodeName->nodeValue = '';
                    $pageNodeName->appendChild($pageNodeName->ownerDocument->createCDATASection($page->name));
                    $pageNodeTitle = MotoXML::findOneByXPath("./title", $pageNode);
                    $pageNodeTitle->nodeValue = '';
                    $pageNodeTitle->appendChild($pageNodeTitle->ownerDocument->createCDATASection($page->title));
                    $pageNodeUrl = MotoXML::findOneByXPath("./url", $pageNode);
                    $pageNodeUrl->nodeValue = '';
                    $pageNodeUrl->appendChild($pageNodeUrl->ownerDocument->createCDATASection($page->url));
                    if (!is_null($page->properties)) {
                        $pageNodeName = MotoXML::findOneByXPath("./properties", $pageNode);
                        if (!is_null($pageNodeName)) $pageNode->removeChild($pageNodeName);
                        $pageNodeName = new DOMElement('properties');
                        $pageNode->appendChild($pageNodeName);
                        foreach ($page->properties as $property) {
                            $property->saveDomElement($pageNodeName->appendChild(new DOMElement('item')));
                        }
                    }
                }
            }
            MotoXML::putXML($dom, CONTENT_RESOURSE_PATH);
            MotoUtil::setLoadEncodedDataFalse();
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function savePopupsInfo($popups, $folders)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            $dom = new MotoXML(CONTENT_RESOURSE_PATH);
            $popupsNode = MotoXML::findOneByXPath('//popups', $dom);
            foreach ($popups as $popup) {
                $popupNode = MotoXML::findOneByXPath(".//popup[@id='{$popup->id}']", $popupsNode);
                $newPopupNode = new DOMElement("popup");
                if (!is_null($popupNode)) {
                    $popupsNode->replaceChild($newPopupNode, $popupNode);
                } else {
                    $popupsNode->appendChild($newPopupNode);
                }
                $popup->saveDomElement($newPopupNode);
            }
            $newFoldersNode = new DOMElement('folders');
            $oldFoldersNode = MotoXML::findOneByXPath(".//popups/folders", $dom);
            if (!is_null($oldFoldersNode)) {
                $popupsNode->replaceChild($newFoldersNode, $oldFoldersNode);
            } else {
                $popupsNode->appendChild($newFoldersNode);
            }
            foreach ($folders as $folder) {
                $folder->saveDomElement($newFoldersNode->appendChild(new DOMElement('folder')));
            }
            MotoXML::putXML($dom, CONTENT_RESOURSE_PATH);
            MotoUtil::setLoadEncodedDataFalse();
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function addNewPage(PageVO $page)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            $dom = new MotoXML(CONTENT_RESOURSE_PATH);
            $page->updateDomElement(MotoXML::findOneByXPath('//pages', $dom));
            MotoXML::putXML($dom, CONTENT_RESOURSE_PATH);
            MotoUtil::setLoadEncodedDataFalse();
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function addNewPopup(PopupVO $popup)
    {
        return $this->savePopup($popup);
    }

    public function loadExternalRichContentData(ModuleVO $moduleVO)
    {
        $responseVO = new ResponseVO();
        $responseVO->status = new StatusVO();
        try {
            $moduleVO->data = null;
            $responseVO->result = $moduleVO;
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            if (isset($moduleVO->parameters["externalFile"]) && file_exists($filename = MotoUtil::replaceSlashes(MOTO_ROOT_DIR . "/" . $moduleVO->parameters["externalFile"]))) {
                $dom = new MotoXML($filename);
                $objectsHolder = new MotoObjectsHolderVO();
                $moduleVO->data = $objectsHolder->loadDomElement(MotoXML::findOneByXPath('./data', $dom));
                $responseVO->status->status = StatusEnum::SUCCESS;
            }
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function saveExternalRichContentData(ModuleVO $moduleVO, $section = "", $id = 0)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            if ($moduleVO->moduleType->type != SystemModuleTypesEnum::RICH_CONTENT) {
                $responseVO->result = null;
                $responseVO->status->status = StatusEnum::INVALID_NUMBER_OF_ARGUMENTS;
                $responseVO->status->message = "Not found this object in structure.";
                return $responseVO;
            }
            $dom = new MotoXML(CONTENT_RESOURSE_PATH);
            $_dom = new MotoXML(STRUCTURE_RESOURSE_PATH);
            $node = MotoXML::findOneByXPath(".//module[@id=" . $moduleVO->id . " and @moduleType=" . $moduleVO->moduleType->id . "]", $dom);
            if (is_null($node)) {
                $responseVO->result = null;
                $responseVO->status->status = StatusEnum::INVALID_NUMBER_OF_ARGUMENTS;
                $responseVO->status->message = "Not found this object in structure.";
                return $responseVO;
            }
            $nodeModules = $node->parentNode;
            $nodeModules->removeChild($node);
            $node = new DOMElement("module");
            $nodeModules->appendChild($node);
            if (isset($moduleVO->parameters["externalData"]) && $moduleVO->parameters["externalData"] == "true") {
                $filename = (isset($moduleVO->parameters["externalFile"]) ? $moduleVO->parameters["externalFile"] : "");
                if ($filename == "") {
                    $filename = MotoUtil::fileUniqueName("xml/modules/richContent.xml");
                    $moduleVO->parameters["externalFile"] = $filename;
                }
                $extDom = new MotoXML('', '1.0', 'utf-8');
                $richContent = new DOMElement("richContent");
                $extDom->appendChild($richContent);
                $moduleVO->data->saveDomElement($richContent->appendChild(new DOMElement("data")));
                MotoXML::putXML($extDom, MOTO_ROOT_DIR . '/' . $filename);
                if (file_exists(MOTO_ROOT_DIR . '/' . $filename . '.gz')) @unlink(MOTO_ROOT_DIR . '/' . $filename . '.gz');
            } else {
                $responseVO->result = null;
                $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
                return $responseVO;
            }
            $moduleVO->saveDomElement($node);
            MotoXML::putXML($dom, CONTENT_RESOURSE_PATH);
            MotoUtil::setLoadEncodedDataFalse();
            MotoCache::getInstance()->clean(MotoCache::CLEANING_MODE_MATCHING_TAG, array('modules', 'richContent', 'richContent_' . (isset($moduleVO->id) ? $moduleVO->id : '')), __FUNCTION__);
            $responseVO->result = $moduleVO;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }

    public function removeExternalRichContentData(ModuleVO $moduleVO)
    {
        $responseVO = new ResponseVO();
        if (DEMO_MODE === 'true') {
            $responseVO->status->status = StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            return $responseVO;
        }
        $responseVO->status = new StatusVO();
        try {
            if (isset($moduleVO->parameters["externalFile"]) && file_exists($filename = MotoUtil::replaceSlashes(MOTO_ROOT_DIR . "/" . $moduleVO->parameters["externalFile"]))) {
                @unlink($filename);
                MotoCache::getInstance()->clean(MotoCache::CLEANING_MODE_MATCHING_TAG, array('modules', 'richContent', 'richContent_' . (isset($moduleVO->id) ? $moduleVO->id : '')), __FUNCTION__);
                if (file_exists($filename . '.gz')) @unlink($filename . '.gz');
            }
            $moduleVO->parameters["externalFile"] = "";
            $moduleVO->parameters["externalData"] = "false";
            $responseVO->result = null;
            $responseVO->status->status = StatusEnum::SUCCESS;
        } catch (Exception $e) {
            $responseVO->result = null;
            $responseVO->status->status = $e->getCode() == StatusEnum::ERROR_ACCESS_DENIED ? StatusEnum::ERROR_ACCESS_DENIED : StatusEnum::ERROR_WHILE_WORKING_WITH_FILE;
            $responseVO->status->message = $e->getMessage();
        }
        return $responseVO;
    }
}