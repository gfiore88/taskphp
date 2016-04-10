<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Language;

/**
 * Description of LanguageFiles
 *
 * @author giovannifiore
 */
class LanguageManager {

    protected static $applications = array();

    /**
     * Starts the language file generation.
     *
     * @return void
     */
    public static function generateLanguageFiles() {
        // The applications where we need to translate.
        self::$applications = Config::get('system.translated_applications');

        Logging::printLog(Logging::logType("Generating language files....", "INFO"));
        foreach (self::$applications as $application => $languages) {

            Logging::printLog(Logging::logType("[APPLICATION ". $application, "INFO"));
            foreach ($languages as $language) {
                Logging::printLog(Logging::logType("\t[LANGUAGE: " . $language . "]","INFO"));
                if (self::getLanguageFile($application, $language)) {
                    Logging::printLog(Logging::logType("OK", "SUCCESS"));
                } else {
                    throw new \Exception('Unable to generate language file!');
                }
            }
        }
    }

    /**
     * Gets the language file for the given language and stores it.
     *
     * @param string $application   The name of the application.
     * @param string $language      The identifier of the language.
     *
     * @throws CurlException   If there was an error during the download of the language file.
     *
     * @return bool   The success of the operation.
     */
    protected static function getLanguageFile($application, $language) {
        $result = false;
        $languageResponse = ApiCall::call(
                        'system_api', 'language_api', array(
                    'system' => 'LanguageFiles',
                    'action' => 'getLanguageFile'
                        ), array('language' => $language)
        );

        try {
            ApiErrors::checkForApiErrorResult($languageResponse);
        } catch (\Exception $e) {
            Logging::printLog(Logging::logType("Error getting language file. See Exception...", "ERROR"));
            throw new \Exception('Error during getting language file: (' . $application . '/' . $language . ')');
        }

        // If we got correct data we store it.
        $destination = self::getLanguageCachePath($application) . $language . '.php';
        // If there is no folder yet, we'll create it.
        var_dump($destination);
        if (!is_dir(dirname($destination))) {
            mkdir(dirname($destination), 0755, true);
        }

        $result = file_put_contents($destination, $languageResponse['data']);

        return (bool) $result;
    }

    /**
     * Gets the directory of the cached language files.
     *
     * @param string $application   The application.
     *
     * @return string   The directory of the cached language files.
     */
    protected static function getLanguageCachePath($application) {
        return Config::get('system.paths.root') . '/cache/' . $application . '/';
    }

}
