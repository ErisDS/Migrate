<img class="" title="migrate" src="http://res.cloudinary.com/hannahwf/image/upload/v1392491823/migrate1_z1xgil.jpg" alt="Migrate" />

<p>Migrate helps to move WordPress installations between URLs, for example between a development and production URL, or between domain names.</p>

<p>At this time, the Migrate is only a script, rather than a plugin so that it can be run to correct database problems after a site has been moved.</p>

<h2>Download</h2>

Download a zip file from <a href="https://github.com/ErisDS/Migrate" title="GitHub">GitHub</a>. Direct link: <a href="https://github.com/ErisDS/Migrate/zipball/master" title="Download from GitHub">https://github.com/ErisDS/Migrate/zipball/master</a>


<h2>Usage</h2>

<ol>
  <li>After moving your WordPress install (files and database) to a new url</li>
  <li>Place the script <code>migrate.php</code> in the root folder of your WordPress installation (at it's new location) at the same level as wp-config.php.</li>
  <li>Navigate to yournewurl.com/migrate.php</li>
  <li>Fill out the "Your current URL" field with the orignal URL at which you installed WordPress</li>
  <li>Fill out the "Your replacement URL" field with the new URL that you want WordPress to sit under</li>
  <li>Press "Continue (Step 2)"</li>
  <li>The information you entered will be repeated back to you, along with some information about your WordPress install and what is going to be changed.</li>
  <li>If you are happy the information is correct, check the confirmation checkbox and press "Lets do this!" </li>
  <li>The script will output details of what has been changed, and your WordPress install should now work correctly</li>
</ol>

<h3>I got a warning message?</h3>

<p>During Step 2 of the process, the script checks to see if the new URL matches the server URL where the migrate.php script is sitting. If they don't match the script produces a warning. This is because in most cases they should be the same, and this warning will hopefully ward off unwanted spelling errors.</p>

<h3>What if I changed the URL incorrectly?</h3>

<p>You can run the script as many times as you like and it will not cause any unwanted problems, to get it to work you only need to make sure that the "from" or "current" URL is correct. If it isn't correct, nothing bad will happen!
The script does exactly the same thing each time it is run, therefore if you want to test it by changing your WordPress install to a dummy URL and back again, you can :)</p>

<h2>More Information</h2>

<p>For more details please see the blog post at: <a href="http://hannah.wf/migrate-announcement">hannah.wf/migrate-announcement</a></p>

<h2>Contribute</h2>
Feel free to raise bugs and open PRs. Try to keep to the existing code style. If you're interested in becoming a committer drop me a message.

<h2>Changelog</h2>

<dl>
  <dt>Version 0.0.4</dt>
  <dd>Bugfix / support for <kbd>https://</kbd> thanks to Bteryk</dd>
</dl>
