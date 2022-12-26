<ol>
    <li>Proceed to: <a href="https://dashboard.stripe.com/test/applications/overview" target="_blank">https://dashboard.stripe.com/test/applications/overview</a> and log in if necessary.</li>
    <li>Create a new application and register your platform.</li>
    <li>Fill out all required fields and set <b>Redirect URL</b> (for tests to the <b>Development</b> section or production to the <b>Production</b> section) to <br /><b>{$mainURL}/SMLHandler.php?module=Stripe</b> <br/><b>NOTE: Only domains with SSL are supported.</b></li> 
    <li>Select appropriate <b>client_id</b> and copy to <b>Application ID</b> field.</li>
    <li>Proceed to: <a href="https://dashboard.stripe.com/account/apikeys" target="_blank">https://dashboard.stripe.com/account/apikeys</a> and reveal secret key.</li>
    <li>Copy <b>Secret</b> key to <b>Application Secret</b> field (for development mode, select <b>View test data</b> in the menu on the left and copy the new test key again).</li>
</ol>
