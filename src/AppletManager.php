<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of AppletManager
 *
 * @author giovannifiore
 */
class AppletManager {

    /**
     * Gets the language files for the applet and puts them into the cache.
     *
     * @throws Exception   If there was an error.
     *
     * @return void
     */
    public static function generateAppletLanguageXmlFiles() {
        // List of the applets [directory => applet_id].
        $applets = array(
            'memberapplet' => 'JSM2_MemberApplet',
        );

        Logging::printLog(Logging::logType("Getting applet language XMLs..","INFO"));

        foreach ($applets as $appletDirectory => $appletLanguageId) {

            Logging::printLog(Logging::logType("Getting > $appletLanguageId ($appletDirectory) language xmls..","INFO"));
            $languages = self::getAppletLanguages($appletLanguageId);
            if (empty($languages)) {
                throw new \Exception('There is no available languages for the ' . $appletLanguageId . ' applet.');
            } else {
                Logging::printLog(Logging::logType(" - Available languages: " . implode(", ", $languages),"INFO"));
            }
            $path = Config::get('system.paths.root') . '/cache/flash';
            foreach ($languages as $language) {
                $xmlContent = self::getAppletLanguageFile($appletLanguageId, $language);
                $xmlFile = $path . '/lang_' . $language . '.xml';
                if (strlen($xmlContent) == file_put_contents($xmlFile, $xmlContent)) {
                    echo " OK saving $xmlFile was successful.\n";
                    
                } else {
                    throw new \Exception('Unable to save applet: (' . $appletLanguageId . ') language: (' . $language
                    . ') xml (' . $xmlFile . ')!');
                }
            }
            echo " < $appletLanguageId ($appletDirectory) language xml cached.\n";
        }

        echo "\nApplet language XMLs generated.\n";
    }

    /**
     * Gets the available languages for the given applet.
     *
     * @param string $applet   The applet identifier.
     *
     * @return array   The list of the available applet languages.
     */
    protected static function getAppletLanguages($applet) {
        $result = ApiCall::call(
                        'system_api', 'language_api', array(
                    'system' => 'LanguageFiles',
                    'action' => 'getAppletLanguages'
                        ), array('applet' => $applet)
        );

        try {
            ApiErrors::checkForApiErrorResult($result);
        } catch (\Exception $e) {
            throw new \Exception('Getting languages for applet (' . $applet . ') was unsuccessful ' . $e->getMessage());
        }

        return $result['data'];
    }

    /**
     * Gets a language xml for an applet.
     *
     * @param string $applet      The identifier of the applet.
     * @param string $language    The language identifier.
     *
     * @return string|false   The content of the language file or false if weren't able to get it.
     */
    protected static function getAppletLanguageFile($applet, $language) {
        $result = ApiCall::call(
                        'system_api', 'language_api', array(
                    'system' => 'LanguageFiles',
                    'action' => 'getAppletLanguageFile'
                        ), array(
                    'applet' => $applet,
                    'language' => $language
                        )
        );

        try {
            ApiErrors::checkForApiErrorResult($result);
        } catch (\Exception $e) {
            throw new \Exception('Getting language xml for applet: (' . $applet . ') on language: (' . $language . ') was unsuccessful: '
            . $e->getMessage());
        }

        return $result['data'];
    }

}
