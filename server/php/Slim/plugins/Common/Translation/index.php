<?php
/**
 * API Endpoints
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    OFOS
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
/**
 * GET translations
 * Summary: Filter  translations
 * Notes: Filter translations.
 * Output-Formats: [application/json]
 */
$app->GET('/api/v1/translations', function ($request, $response, $args) use ($app)
{
    $result = array();
    try {
        $queryParams = $request->getQueryParams();
        if (!empty($queryParams['lang_code'])) {
            $lang_name = $queryParams['lang_code'];
        } else {
            $lang_name = 'en';
        }
        if (!empty($queryParams['file_list']) && $queryParams['file_list'] == 'filelist') {
            $dir = SCRIPT_PATH . '/l10n/*.json';
            
            $data = array();                        
            foreach(glob($dir) as $file) 
            {
                 $file = basename($file);
                 $filename = basename($file, ".json");
                 $data[$filename]=$file;
            }
            $result['data'] = array($data);
            return renderWithJson($result);
        }         
        $lang_file_path = SCRIPT_PATH . '/l10n/' . $lang_name . '.json';
        if (file_exists($lang_file_path)) {
            $content = file_get_contents(SCRIPT_PATH . '/l10n/' . $lang_name . '.json');
            $app = json_decode($content, true);
            $data = array();
            foreach($app as $Key => $value){
                $data[] = array('label' => $Key, 'lang_text' => $value);
            }
            $result['data'] = $data;
            return renderWithJson($result);
        } else {
            return renderWithJson($result, 'No translation found. Please create new translation', '', 1);
        }
    }
    catch(Exception $e) {
        return renderWithJson($result, $message = 'No record found', $fields = '', $isError = 1);
    }
})->add(new Acl\ACL('canListTranslations'));
/**
 * GET translations
 * Summary: Filter  translations
 * Notes: Filter translations.
 * Output-Formats: [application/json]
 */
$app->POST('/api/v1/translations', function ($request, $response, $args) use ($app)
{
    $result = array();
    $args = $request->getParsedBody();
    if (!empty($args['lang_code'])) {
        $lang_file_path = SCRIPT_PATH . '/l10n/en.json';
        $content = file_get_contents($lang_file_path);
        $app = json_decode($content, true);
        $mediadir = SCRIPT_PATH . '/l10n/';
        $file_path = $mediadir . $args['lang_code'] . '.json';
        $fh = fopen($file_path, 'w');
        fwrite($fh, json_encode($app, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        fclose($fh);
        $result['data'] = $app;
        return renderWithJson($app);
    } else {
        return renderWithJson($result, $message = 'Please provide language code', $fields = '', $isError = 1);
    }
})->add(new Acl\ACL('canCreateTranslation'));
/**
 * GET translations
 * Summary: Filter  translations
 * Notes: Filter translations.
 * Output-Formats: [application/json]
 */
$app->PUT('/api/v1/translations/{langCode}', function ($request, $response, $args) use ($app)
{
    $result = array();
    $args = $request->getParsedBody();
    $langCode = $request->getAttribute('langCode');
    if (!empty($langCode)) {
        $lang_file_path = SCRIPT_PATH . '/l10n/' . $langCode . '.json';
        if (file_exists($lang_file_path)) {
            $content = file_get_contents($lang_file_path);
            $app = json_decode($content, true);
            if (!empty($args)) {
                foreach ($args as $arg) {
                   // foreach ($arg as $key => $value) {
                        $app[$arg['label']] = $arg['lang_text'];
                   // }
                }
            }
            $fh = fopen($lang_file_path, 'w');
            fwrite($fh, json_encode($app, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            fclose($fh);
            $result['data'] = $app;
            return renderWithJson($app);
        } else {
            $lang_file_path = SCRIPT_PATH . '/l10n/en.json';
            $content = file_get_contents($lang_file_path);
            $app = json_decode($content, true);
            if (!empty($args['keyword'])) {
                foreach ($args['keyword'] as $arg) {
                    foreach ($arg as $key => $value) {
                        $app[$key] = $value;
                    }
                }
            }
            $mediadir = SCRIPT_PATH . '/l10n/';
            $file_path = $mediadir . $langCode . '.json';
            $fh = fopen($file_path, 'w');
            fwrite($fh, json_encode($app, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            fclose($fh);
            $result['data'] = $app;
            return renderWithJson($app);
        }
    } else {
        return renderWithJson($result, $message = 'No translaton found', $fields = '', $isError = 1);
    }
})->add(new Acl\ACL('canUpdateTranslation'));
