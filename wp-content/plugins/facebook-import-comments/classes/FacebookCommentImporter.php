<?php

class com_bul7_wp_plugin_FacebookCommentImporter {
   const IMODE_APPEND = 'A';
   const IMODE_DELETE = 'D';
    private $refreshInterval = 300;
    private $additionalLocales = array();
    private $useBlogLocale = true;
    private $importMode;

    public function __construct() {
        $this->readSettings();
        add_action('wp_head', array($this, 'import'));
        add_action('admin_menu', array(&$this, 'registerAdminMenuOptions'));
    }

    function registerAdminMenuOptions() {
        add_options_page('Facebook Comment to WordPress', 'FB2WP Comments', 'administrator', get_class($this), array(&$this, 'adminSettingsPage'));
    }

    public function import() {
       global $id;
        if (!is_single()) return;
        $helper = new com_bul7_wp_plugin_FacebookCommentImporterHelper((empty($id)) ? $GLOBALS['post']->ID : $id);
        $isNeedRefresh = $helper->lastImport + $this->refreshInterval <= time();
        if (!$isNeedRefresh) return;

        $url = get_permalink();
        $locales = $this->additionalLocales;
        if ($this->useBlogLocale) $locales[] = get_bloginfo('language');
        else if (empty($locales)) $locales[] = '';

        $comments = array();
        foreach ($locales as $loc) {
            if (FALSE === ($locComments = $this->fetchCommentsFor($url, $loc))) {
               // TODO: Log failure
               return;
            }
            if (is_array($locComments) && is_array($locComments['data']))
               $comments = array_merge($comments, $locComments);
        }
        //
        $helper->import($comments['data'], $this->importMode);
    }

    public function adminSettingsPage() {
        $tokenField = get_class($this).'_submit';
        $isSubmitted = isset($_POST[$tokenField]) && $_POST[$tokenField] == 'Y';

        $messages = array();
        $vars = array();
        if ($isSubmitted) {
            if ($_POST['do_delete_facebook_comments']) {
                $this->deleteAllImportedFacebookComments();
                $messages[] = 'All imported Facebook comments removed.';
            } // do_delete_facebook_comments

            else if ($_POST['do_update_settings']) {
                if (isset($_POST['refreshInterval']) && is_numeric($_POST['refreshInterval']) && $_POST['refreshInterval'] >= 0)
                    $this->refreshInterval = intval($_POST['refreshInterval']);
                else
                    $messages[] = 'WARNING: The value you supplied for Refresh Interval was ignored because it is incorrect. Expecting numeric value, greater than or equal to 0. ' . $_POST['refreshInterval'] . ' given';

                $this->useBlogLocale = isset($_POST['useBlogLocale']) && $_POST['useBlogLocale'] == 'Y';

                $isLocalesListValid = TRUE;
                $additionalLocalesList = array_map('trim', isset($_POST['additionalLocales']) && '' != trim($_POST['additionalLocales']) ? explode(',', $_POST['additionalLocales']) : array());
                foreach ($additionalLocalesList as $loc) {
                    $isLocalesListValid = $this->isValidLocale($loc);
                    if (!$isLocalesListValid) break;
                }

                if ($isLocalesListValid)
                    $this->additionalLocales = $additionalLocalesList;
                else
                    $messages[] = 'WARNING: The value you supplied for Additional Locales was ignored because it is incorrect.';

                $this->saveSettings();
                $messages['Settings saved'];
            }
        }
        $vars['refreshInterval'] = $this->refreshInterval;
        $vars['additionalLocales'] = implode(',', $this->additionalLocales);
        $vars['useBlogLocale'] = $this->useBlogLocale;
        $vars['numFacebookComments'] = $this->countFacebookComments();
        $vars['importMode'] = $this->importMode;
        
        $this->showAdminPage($tokenField, $vars, $messages);
    }

    private function isValidLocale($locale) {
        return preg_match('/^[a-z][a-z]_[A-Z][A-Z]$/', $locale);
    }
    
    private function countFacebookComments() {
        global $wpdb;
        /* @var $wpdb wpdb */

        if (FALSE === $wpdb->query("SELECT COUNT(*) cnt FROM {$wpdb->prefix}comments WHERE comment_agent LIKE 'facebook-seo-comments%'"))
            throw new Exception('countFacebookComments: ' . mysql_error());
        return $wpdb->last_result[0]->cnt;
    }

    private function showAdminPage($tokenField, $vars, $messages) {
        extract($vars);
        include dirname(__FILE__).'/../template/settings.php';
    }

    private function readSettings() {
        $options = get_option(get_class($this));
        if (!is_array($options)) $options = array();

        $this->refreshInterval = isset($options['refreshInterval']) ? $options['refreshInterval'] : 300;
        $this->useBlogLocale = isset($options['useBlogLocale']) ? $options['useBlogLocale'] : FALSE;
        $this->additionalLocales = isset($options['additionalLocales']) ? $options['additionalLocales'] : '';
        $this->importMode = isset($options['importMode']) ? $options['importMode'] : self::IMODE_DELETE;
    }

    private function saveSettings() {
        $options = array(
            'refreshInterval' => $this->refreshInterval,
            'useBlogLocale' => $this->useBlogLocale,
            'additionalLocales' => $this->additionalLocales
        );
        update_option(get_class($this), $options);
    }

    private function deleteAllImportedFacebookComments() {
        global $wpdb;
        $wpdb->query("DELETE FROM `".$wpdb->prefix."comments` WHERE `comment_agent` = 'facebook-seo-comments-1.0'");
        $wpdb->query("DELETE FROM `".$wpdb->prefix."postmeta` WHERE `meta_key` LIKE  '%FBSEOCommentsMap%'");
    }

    private function fetchCommentsFor($permalink, $locale) {
        // DEBUG:
        // $permalink = "http://gotvarstvo.georgievi.net/a/cvetnica";
        $request_url = "http://graph.facebook.com/comments/?ids=" .
                urlencode($permalink);
        if ($locale != '') $request_url .= '&locale=' . $locale;

        $requests = @file_get_contents($request_url);
        if ($requests === FALSE) return FALSE;

        return @current(json_decode($requests, TRUE));
    }
}

class com_bul7_wp_plugin_FacebookCommentImporterHelper {
    private $postId;
    private $commentMap;
    public $lastImport;
    private $processedComments;

    public function __construct($postId) {
        $this->postId = $postId;
        $this->readPostMeta();
    }

    public function import($comments, $mode) {
        $this->processedComments = array();
        $this->importComments($comments);
        if ($mode == com_bul7_wp_plugin_FacebookCommentImporter::IMODE_DELETE) {
           $this->deleteMissing($comments);
        }
        $this->savePostMeta();
    }

    private function deleteMissing($comments) {
       $missingCommentIds = array();
       foreach ($this->commentMap as $commentFBId => $commentMeta) {
          if (!isset($this->processedComments[$commentFBId])) {
             $missingCommentIds[] = $commentMeta['id'];
             unset($this->commentMap[$commentFBId]);
          }
       }
       if ($missingCommentIds) {
          $db = $this->getDb();
          $idList = implode(',', $missingCommentIds);
          $db->query("DELETE FROM `".$db->prefix."comments` WHERE comment_ID IN ($idList)");
          echo mysql_error();
       }
    }

    private function importComments($comments, $parentCommentId = NULL) {
        foreach ($comments as $num => $comment) {
            $this->importComment($comment, $parentCommentId);
        }
    }

    private function importComment($comment, $parentFBId) {
       $this->processedComments[$comment['id']] = $comment['id'];
       if (!isset($this->commentMap[$comment['id']])) {
            $db = $this->getDb();

            $createdDateTime = new DateTime($comment['created_time']);

            $timeOffsetFromGmt = -intval($createdDateTime->getOffset());
            $createdDateTime->modify($timeOffsetFromGmt.' seconds');
            $dateGmt = $createdDateTime->format('Y-m-d H:i:s');

            $timeOffsetToLocal = date('Z');
            $createdDateTime->modify($timeOffsetToLocal.' seconds');
            $date = $createdDateTime->format('Y-m-d H:i:s');

            $data = array(
                'comment_post_ID' => $this->postId,
                'comment_author' => $comment['from']['name'],
                'comment_author_email' => '',
                'comment_author_url' => 'http://www.facebook.com/profile.php?id='.$comment['from']['id'],
                'comment_date' => $date,
                'comment_date_gmt' => $dateGmt,
                'comment_content' => $comment['message'],
                'comment_karma' => 0,
                'comment_approved' => 1,
                'comment_agent' => 'facebook-seo-comments-1.0',
                'comment_type' => '',
                'comment_parent' => is_null($parentFBId) ? 0 : $this->commentMap[$parentFBId]['id'],
                'user_id' => 0
            );
            if ($db->insert($db->prefix.'comments', $data)) {
                $this->commentMap[$comment['id']]['id'] = $db->insert_id;
            } else {
                return;
            }
        }
        if (isset($comment['comments']['data']))
            $this->importComments($comment['comments']['data'], $comment[id]);
    }

    private function readPostMeta() {
        $metaEncoded = get_metadata('post', $this->postId, 'FBSEOCommentsMap', true);
        $meta = ($metaEncoded == '') ? array() : json_decode($metaEncoded, TRUE);

        $this->lastImport = isset($meta['lastImport']) ? $meta['lastImport'] : 0;
        $this->commentMap = isset($meta['map']) ? $meta['map'] : array();
    }

    private function savePostMeta() {
        $meta = array(
            'lastImport' => time(),
            'map' => $this->commentMap
        );
        update_metadata('post', $this->postId, 'FBSEOCommentsMap', json_encode($meta));
    }

    /**
     * @global wpdb $wpdb
     * @return wpdb
     */
    private function getDb() {
        global $wpdb;
        return $wpdb;
    }

}


?>
