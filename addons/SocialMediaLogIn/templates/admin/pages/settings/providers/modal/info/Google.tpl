<ol>
    <li>Proceed to:: <a href="https://console.developers.google.com/iam-admin/projects">https://console.developers.google.com/iam-admin/projects</a> and log in if necessary.</li>
    <li>Click on <b>Select Project</b>, then press <b>Create Project</b> and select it when created. </li>
    <li>Move to <b>API Manager → Credentials → OAuth</b> consent screen and fill out the form there. </li>
    <li>Next, go to <b>Credentials → Create credentials (OAuth client ID type)</b> and select <b>Web application</b>.</li>
    <li>Set <b>Authorize redirect URIs</b> to  <br /><b>{$mainURL}/SMLHandler.php?module=Google</b></li>
    <li>Once you have registered paste the created application credentials (Client ID for application ID and Client Secret for Application secret) into the form below.</li>
</ol>