<ol>
    <li>Proceed to:: <a href="https://portal.azure.com/">https://portal.azure.com</a> and log in if necessary.</li>
    <li>Search for and select <b>Azure Active Directory</b>.</li>
    <li>Under <b>Manage</b>, select the <b>App registrations</b> option.</li>
    <li>Select <b>New registration</b>, enter a <b>Name</b> for the application and choose the <b>Supported account types</b> for the application and then registry the application.</li>
    <li>Under <b>Manage</b>, select <b>Authentication</b>, and then select <b>Add a platform</b>.</li>
    <li>Select the <b>Internet Application</b> tile and enter the <b>{$mainURL}/SMLHandler.php</b> as a <b>Redirect URL</b>.</li>

    <li>In the <b>Implicit grant</b> section, select the <b>Access Token</b> and save changes.</li>
    <li>Under <b>Manage</b>, select <b>Api Permissions</b>.</li>
    <li>In the last step add the following permissions <b>Directory.Read.All</b>, <b>User.Read.All</b> .</li>
</ol>