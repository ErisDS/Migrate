<?php
/*
Script Name: Update WP URLS
Description: Replaces all URLS in a WordPress system so that a site can be migrated across domains
Author: Hannah (ErisDS)
Author Email: erisds@gmail.com
Author URL: http://erisds.co.uk
Script URL: http://erisds.co.uk/resources/migrate
TODO: Add BB Press support back
Version: 0.0.2
*/


/* 
$current_url: Set this to be the current URL of your WordPress install if you want a default (including http://)
*/
$current_url = "";

/* 
$replacement_url: Set this to be the new URL of your WordPress install if you want a default (including http://)
*/
$replacement_url = "";


/*******************************/
/* DON'T TOUCH ANYTHING ELSE ! */
/*******************************/
$server_url = 'http://' . $_SERVER['SERVER_NAME'];



function checkURL($url)
{
  $url_regex = '/^(http\:\/\/[a-zA-Z0-9_\-]+(?:\.[a-zA-Z0-9_\-]+)*\.[a-zA-Z]{2,4}(?:\/[a-zA-Z0-9_]+)*(?:\/[a-zA-Z0-9_]+\.[a-zA-Z]{2,4}(?:\?[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)?)?(?:\&[a-zA-Z0-9_]+\=[a-zA-Z0-9_]+)*)$/';
  if($url == 'http://')
  {
    return false;
  }  
  
  return preg_match($url_regex, $url);
}


?>

<html>
  <head>
    <title>Migrate URLS in WordPress</title>
    <style>
      .wrapper{font-family:Helvetica,Arial,sans-serif;font-size:13px;width:580px}
      .grey{color:#999;}
      form ul, form ul li{list-style:none;margin:0;padding:0;text-indent:0;clear:both}
      #url_form{width:420px}
      #url_form input{float:right;width:250px}
      h2{clear:both;padding:10px 0 0;margin:0}
      p{clear:both;padding:0 0 10px;margin:0}
      .info, .success, .warning, .error, .validation {border: 1px solid;margin: 10px 0;padding:10px;clear:both}
      .info{color:#00529b; background-color:#bde5f8}
      .success{color:#4f8a10; background-color:#dff2bf}
      .success:before{content:'Success: ';font-weight:bold}
      .warning{color:#9f6000; background-color:#feefb3}
      .warning:before{content:'Warning: ';font-weight:bold}
      .error{color: #d8000c;background-color:#ffbaba}
      .error:before{content:'Error: ';font-weight:bold}
      .red{color:#d8000c}
      .green{color:#4f8a10}
      #backup{width:250px;float:right;margin:0}
    </style>
  </head>
  <body>
    <div class="wrapper">
      <div id="backup" class="info">
        <p><strong>ALWAYS</strong> backup your database before making changes!</p>
        <p>Try using the <a href="http://wordpress.org/extend/plugins/wp-db-backup/" title="WP-DB-Backup Plugin">WP-DB-Backup Plugin</a> if you need help.</p>
       </div>
      <h1>Migrate WordPress</h1>
     
      
      <?php
      if(!@include('wp-config.php')){
        die('<div class="error">Cannot find config file. Please make sure this script is installed at the same level as wp-config.php and try again</div>');
      }

      mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die('<div class="error">Could not connect to the database</div>');
      mysql_select_db(DB_NAME) or die('<div class="error">Could not select the database</div>');
      
      
      
      if($_POST['url_pattern'] && $_POST['url_pattern'] !== '')
      {
        $url_pattern = substr($_POST['url_pattern'], 0, 7) == 'http://' ? $_POST['url_pattern'] : 'http://' . $_POST['url_pattern'];
      }
      else
      {
        $url_pattern = substr($current_url, 0, 7) == 'http://' ? $current_url : 'http://' . $current_url;  
      }
      
      if($_POST['url_replace'] && $_POST['url_replace'] !== '')
      {
        $url_replace = substr($_POST['url_replace'], 0, 7) == 'http://' ? $_POST['url_replace'] : 'http://' . $_POST['url_replace'];
      }
      else
      {
        $url_replace = substr($replacement_url, 0, 7) == 'http://' ? $replacement_url : 'http://' . $replacement_url; 
      }
      
         
      // IF NOT SUBMITTED
      if((!$_POST['submit_urls'] || ($_POST['submit_urls'] && (!checkURL($url_pattern) || !checkURL($url_replace)))) && (!$_POST['submit_confirm'] || ($_POST['submit_confirm'] && !$_POST['confirm']))):
      ?>
  
      <h2>Step 1: Setup...</h2>
      
      <?php if($_POST['submit_urls'] && !checkURL($url_pattern)): ?>
      <div class="error">Your current URL (<?php echo $url_pattern ?>) is not a valid URL.</div>
      <?php endif; ?>
      
      <?php if($_POST['submit_urls'] && !checkURL($url_replace)): ?>
      <div class="error">Your current URL (<?php echo $url_replace ?>) is not a valid URL.</div>
      <?php endif; ?>
      
      
      <p>Enter the URL location of your current install, and the URL location of your new install.</p>
    
      <form id="url_form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <ul>
          <li>
            <label for="url_pattern">Your current URL</label>
            <input type="text" id="url_pattern" name="url_pattern" value="<?php echo $url_pattern; ?>" />
          </li>   
          <li>
            <label for="url_replace">Your replacement(new) URL</label>
            <input type="text" id="url_replace" name="url_replace" value="<?php echo $url_replace; ?>" />
          </li>
        </ul>
        <input type="submit" id="submit_urls" name="submit_urls" value="Continue (Step 2)" />
      </form>
      
      <p><strong>Note:</strong> You can define these URLs permanently by editing the script.</p>
      
     <?php elseif($_POST['submit_urls'] && (!$_POST['submit_confirm'] || ($_POST['submit_confirm'] && !$_POST['confirm']))): 
     
     $sql1 = "SELECT option_value FROM `" . $table_prefix . "options` WHERE option_name = 'siteurl'";
          $result1 = mysql_query($sql1);
          $row1 = mysql_fetch_assoc($result1);
        $site = $row1['option_value'];
        $sql2 = "SELECT option_value FROM `" . $table_prefix . "options` WHERE option_name = 'home'";
          $result2 = mysql_query($sql2);
          $row2 = mysql_fetch_assoc($result2);
        $home = $row2['option_value'];
        $sql3 = "SELECT ID, guid FROM `" . $table_prefix . "posts` WHERE guid LIKE '%".$url_pattern."%'";
          $result3 = mysql_query($sql3);
          $guid_count = mysql_num_rows($result3);
        $sql4 = "SELECT ID, post_title, post_content FROM `" . $table_prefix . "posts` WHERE `post_content` LIKE '%" . $url_pattern . "%'";
          $result4 = mysql_query($sql4);
          $content_count = mysql_num_rows($result4);
        $total = $content_count + $guid_count + ($home === $url_pattern ? 1 : 0) + ($site === $url_pattern ? 1 : 0);
     
     ?>
      <h2 class="grey">Step 1: Setup...</h2>
      <ul class="grey">
        <li>Current URL is set to: <strong><?php echo $url_pattern; ?></strong></li>
        <li>Replacement URL is set to: <strong><?php echo $url_replace; ?></strong></li>  
      </ul> 
     
      
      <h2>Step 2: Confirm...</h2>
      <ul>
        <li>Home URL: <strong><?php echo $home; ?></strong> - <?php echo $home != $url_replace ? '<span class="red">Home URL will be replaced</span>' : '<span class="green">Home URL is OK</span>'; ?></li>
        <li>Site URL: <strong><?php echo $site; ?></strong> - <?php echo $site != $url_replace ? '<span class="red">Site URL will be replaced</span>' : '<span class="green">Site URL is OK</span>'; ?></li>
        <li>GUIDS: <strong><?php echo $guid_count > 0 ? $guid_count . ' </strong><span class="red">need updating</span>' : '</strong><span class="green">No updates necessary</span>'; ?> </li>
        <li>Content:<strong> <?php echo $content_count > 0 ? $content_count . '</strong> <span class="red">need updating</span>' : '</strong><span class="green">No updates necessary</span>'; ?> </li>
      </ul>

      <?php if($total > 0): ?>
      
        <div class="info"><strong><?php echo $total; ?></strong> database entries will be migrated</div>
           
        <?php if($url_pattern != $home): ?>
          <div class="warning">Your current URL (<?php echo $url_pattern; ?>) is not the same as the current home URL (<?php echo $home; ?>). This could cause issues. Please confirm you want to proceed below.</div>
        <?php endif; ?>
        
        <?php if($url_pattern != $site): ?>
          <div class="warning">Your current URL (<?php echo $url_pattern; ?>) is not the same as the current site URL (<?php echo $site ?>). This could cause issues. Please confirm you want to proceed below.</div>
        <?php endif; ?>
        
        <?php if($url_replace != $server_url): ?>
          <div class="warning">Your replacement URL (<?php echo $url_replace; ?>) is not the same as the current server URL (<?php echo $server_url; ?>). Please confirm you want to proceed below.</div>
        <?php endif; ?>
        
        <?php if($_POST['submit_confirm'] && !$_POST['confirm']): ?>
        <div class="error">You must confirm the changes to continue!</div>
        <?php endif; ?>
        
          
        <form id="confirm_form" action="<?php $_SERVER['PHP_SELF']; ?>" method="POST">
        <input type="hidden" id="submit_urls" name="submit_urls" value="true" />
        <input type="hidden" id="url_pattern" name="url_pattern" value="<?php echo $url_pattern; ?>" />
        <input type="hidden" id="url_replace" name="url_replace" value="<?php echo $url_replace; ?>" />
        <input type="hidden" id="total" name="total" value="<?php echo $total ?>" />
          <ul>
            <li>
              <label for="confirm">Confirm you want to change <strong><?php echo $url_pattern; ?></strong> to <strong><?php echo $url_replace; ?></strong> in these <?php echo $total; ?> items</label>
              <input type="checkbox" id="confirm" name="confirm" />
            </li>
          </ul>
          <p><input type="submit" id="submit_confirm" name="submit_confirm" value="Lets do this!" /></p>
        </form>
      
      <?php else: ?>
        <div class="success">Nothing to migrate!</div>
      
      <?php endif; ?>
  
      <?php
      else: 
        $log = '';
        $count = 0;
        $error = 0;
        
        if($site !== $url_replace)
        {
          $log .= '<h3>Updating <strong>siteurl</strong></h3>';
          
          $sql = "SELECT option_value FROM `" . $table_prefix . "options` WHERE option_name = 'siteurl'";
          $result = mysql_query($sql);
          $row = mysql_fetch_assoc($result);
          $old_value = $row['option_value'];
          $new_value = str_replace($url_pattern,$url_replace,$old_value);
          $update = "UPDATE `" . $table_prefix . "options` SET option_value='" . $new_value . "' WHERE option_name='siteurl'";
          $result2 = mysql_query($update);
          if($result2)
          {
            $log .= '<div class="green">Updated <strong>siteurl</strong> successfully!</div>';
            $count++;
          }
          else
          {
            $log .= '<div class="red">Something went wrong, <strong>siteurl</strong> wasn\'t updated</div>';
            $error++;
          }
        }
        
        if($home !== $url_replace)
        {
          $log .= '<h3>Updating <strong>home</strong></h3>';
          
          $sql = "SELECT option_value FROM `" . $table_prefix . "options` WHERE option_name = 'home'";
          $result = mysql_query($sql);
          $row = mysql_fetch_assoc($result);
          $old_value = $row['option_value'];
          $new_value = str_replace($url_pattern,$url_replace,$old_value);
          $update = "UPDATE `" . $table_prefix . "options` SET option_value='" . $new_value . "' WHERE option_name='home'";
          $result2 = mysql_query($update);
          if($result2)
          {
            $log .= '<div class="green">Updated <strong>home</strong> successfully!</div>';
            $count++;
          }
          else
          {
            $log .= '<div class="red">Something went wrong, <strong>home</strong> wasn\'t updated</div>';
            $error++;
          }
        }  
        
        $sql = "SELECT ID, guid FROM `" . $table_prefix . "posts`";
        $result = mysql_query($sql);
        
        
        $log .= '<h3>Updating ' . mysql_num_rows($result) . ' post guids</h3>';
        
        while($row = mysql_fetch_assoc($result))
        {
          
          $id = $row['ID'];
          $old_guid = $row['guid'];
          $new_guid = str_replace($url_pattern,$url_replace,$old_guid);
          $log .= '<pre>ID: ' . $id . '<br />OLD: ' . $old_guid . '<br />NEW: ' . $new_guid . '</pre>';
        
           
          $update = "UPDATE `" . $table_prefix . "posts` SET guid = '" . $new_guid . "' WHERE ID = '" .  $id ."'";
          $result2 = mysql_query($update);
          if($result2)
          {
            $log .= '<p class="green">GUID updated successfully!</p>';
            $count++;
          }
          else
          {
            $log .= '<p class="red">Something went wrong</p>';
            $error++;
          }
        } 
        
        $sql = "SELECT ID, post_title, post_content FROM `" . $table_prefix . "posts` WHERE `post_content` LIKE '%" . $url_pattern . "%'";
        $result = mysql_query($sql);
        $log .= '<h3>Updating ' . mysql_num_rows($result) . ' posts contents</h3>';
      
        while($row = mysql_fetch_assoc($result))
        {
          
          $id = $row['ID'];
          $old_content = $row['post_content'];
          $new_content = str_replace($url_pattern,$url_replace,$old_content);
          
          $log .= '<pre>ID: ' . $id . '<br />TITLE: ' . $row['post_title'] . '</pre>'; 
          
          
          $update = "UPDATE `" . $table_prefix . "posts` SET post_content = '" . mysql_real_escape_string($new_content) . "' WHERE ID = '" .  $id ."'";
          $result2 = mysql_query($update);
          if($result2)
          {
            $log .= '<p>Post content updated succesfully!</p>';
            $count++;
          }
          else
          {
            $log .= '<p class="red">Something went wrong</p>';
            $error++;
          }
        }
        ?>
        
        <h2 class="grey">Step 1: Setup...</h2>
        <ul class="grey">
          <li>Current URL is set to: <strong><?php echo $url_pattern; ?></strong></li>
          <li>Replacement URL is set to: <strong><?php echo $url_replace; ?></strong></li>  
        </ul> 
        
        <h2 class="grey">Step 2: Confirm...</h2>
        <ul class="grey">
          <li>Confirmed migration of <?php echo $_POST['total'] ?> database entries</li>
        </ul>
        
        <h2>Step 3: Results...</h2>
        
        <?php if($count != 0): ?>
        <div class="success"><strong><?php echo $count; ?></strong> database entries migrated succesfully</div>
        <?php endif; ?>
        
        <?php if($error != 0): ?>
        <div class="error"><strong><?php echo $error; ?></strong> database entries failed to migrate</div>   
        <?php endif; ?>
        
      
        <h2 class="grey">Details</h2>
      
        <div class="details grey">
          <?php echo $log; ?>
        </div>

      <?php endif; // end if submitted ?>

    </div>
  </body>
</html>