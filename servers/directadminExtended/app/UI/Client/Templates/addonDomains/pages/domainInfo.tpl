
<table class="lu-table lu-table--mob-collapsible lu-dataTable no-footer dtr-column">
    <tbody class="first-table-row">
        <tr>
            <td>{$MGLANG->translate('domain')}</td>
            <td>{$rawObject->getDomain()}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('isdefault')}</td>
            <td>{ucfirst($rawObject->getDefaultdomain('translate'))}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('suspended')}</td>
            <td>{$rawObject->getSuspended('translate')}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('active')}</td>
            <td>{ucfirst($rawObject->getActive('translate'))}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('bandwidth')}</td>
            <td>{$rawObject->getBandwidth()} / {ucfirst($rawObject->getBandwidthLimit())}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('quota')}</td>
            <td>{$rawObject->getQuota()} / {ucfirst($rawObject->getQuotaLimit())}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('subdomain')}</td>
            <td>{$rawObject->getSubdomain()}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('cgi')}</td>
            <td>{$rawObject->getCgi('label')}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('php')}</td>
            <td>{$rawObject->getPhp('label')}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('safemode')}</td>
            <td>{$rawObject->getSafemode('label')}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('ssl')}</td>
            <td>{$rawObject->getSsl('label')}</td>
        </tr>
        <tr>
            <td>{$MGLANG->translate('localMail')}</td>
            <td>{$rawObject->getLocalMail('label')}</td>
        </tr>

    </tbody>

</table>
