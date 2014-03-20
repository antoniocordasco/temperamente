<?php
$blogLocale = get_bloginfo('language');
?>

<div class="wrap">
    <div id="icon-options-general" class="icon32"><br /></div>
    <h2>Facebook Comments to WordPress Plugin Settings</h2>

    <?php if ($messages) { ?>
        <div id="setting-error-settings_updated" class="updated settings-error">
            <?php foreach ($messages as $m) { echo "<p><strong>$m</strong></p>"; } ?>
        </div>
    <?php } // messages  ?>


    <form method="POST">
        <input type="hidden" name="<?php echo $tokenField; ?>" value="Y" />

        <h3>Performance Settings</h3>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">Refresh Interval</th>
                <td><input type="text" name="refreshInterval" size="10" value="<?php echo $refreshInterval; ?>" /> seconds <br />
                    <em>When a visitor opens a post, the plugin connects to Facebook to retrieve comments for that post. To reduce the resulting network traffic, the Refresh Interval setting specifies the minimum amount of time in seconds between two connections.<br />
                        Setting this option to a low value results in degraded performance. Using a high causes latency - comments from Facebook appear in your blog with a delay.</em>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Import Mode</th>
                <td><em>By default when synchronizing your blog comments with Facebook comments, removed comments are removed from the blog database. You can select different behavior.</em><br />
                   <input type="radio" name="importMode" id="importModeAppend" value="<?php echo com_bul7_wp_plugin_FacebookCommentImporter::IMODE_APPEND; ?>"<?php if ($importMode == com_bul7_wp_plugin_FacebookCommentImporter::IMODE_APPEND) echo ' checked="checked"'; ?>/> <label for="importModeAppend">Append new</label><br />
                   <em>In append mode only new comments are imported. No modifications to already imported comments are done. Best performance, but may require manual comments moderation.</em><br />
                   <input type="radio" name="importMode" id="importModeDelete" value="<?php echo com_bul7_wp_plugin_FacebookCommentImporter::IMODE_DELETE; ?>"<?php if ($importMode == com_bul7_wp_plugin_FacebookCommentImporter::IMODE_DELETE) echo ' checked="checked"'; ?> /> <label for="importModeDelete">Remove missing</label><br />
                   <em>In remove mode Facebook comments in blog's database that are not found anymore on Facebook are removed.</em>
                </td>
            </tr>
        </table>

        <h3>Language Settings</h3>

        <p>When connecting to Facebook the plugin needs to supply information about comments locale. Locale is made of two parts: language and location. <br />
            For example en_US locale means English language and United States as a location.</p>
        
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Locales</th>
                <td><p><input type="checkbox" name="useBlogLocale" id="useBlogLocale" value="Y"<?php if ($useBlogLocale) echo ' checked="checked"'; ?> /> <label for="useBlogLocale">Retrieve blog locale</label><br />
                    <em>Select this option if you want to retrieve comments that match the current blog locale (<?php echo $blogLocale; ?>).</em></p>
                    <p><label for="additionalLocales">Retrieve additional locales</label> <input type="text" name="additionalLocales" id="additionalLocales" size="60" value="<?php echo htmlspecialchars($additionalLocales); ?>" /><br />
                    <em>Specify a comma separated list of additional locales that you want to be retrieved. This option is independent from the blog locale.</em></p>
                </td>
            </tr>
        </table>

        <p><input type="submit" name="do_update_settings" value="Save Settings" /></p>

        <h3>Maintenance</h3>
        <p>Number of imported comments: <?php echo $numFacebookComments; ?> <input type="submit" name="do_refresh" value="Refresh" /></p>
        <p><input type="submit" name="do_delete_facebook_comments" value="Delete All Imported Facebook Comments" /></p>
    </form>
</div>
