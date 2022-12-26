function showCsr(data, targetId, event)
{
    mgPageControler.vueLoader.loadModal(event, 'CSR', 'ModulesGarden_Servers_DirectAdminExtended_App_UI_Client_Ssl_Buttons_ViewCSR', data.htmlData.csr, {}, true);
}
