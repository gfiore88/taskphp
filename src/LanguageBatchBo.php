<?php

namespace Language;

/**
 * Business logic related to generating language files.
 */
class LanguageBatchBo {
    
    public static function generateLanguageFiles() { 
        return LanguageManager::generateLanguageFiles();
    }

    public static function generateAppletLanguageXmlFiles() {
        return AppletManager::generateAppletLanguageXmlFiles();
    }

}
