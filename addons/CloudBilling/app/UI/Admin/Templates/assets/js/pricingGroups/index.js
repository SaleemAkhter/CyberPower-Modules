
function mgUpdateGroupId(response, id, event) {
    if (typeof response.rawData !== 'undefined' && typeof response.rawData.gid !== 'undefined') {
        for (var key in mgPageControler.vueLoader.$children)
        {
            if(mgPageControler.vueLoader.$children[key].component_id === 'pricingGroups')
            {
                mgPageControler.vueLoader.$children[key].gid = response.rawData.gid;
                mgPageControler.vueLoader.$children[key].$nextTick(function(){
                    mgPageControler.vueLoader.$children[key].loadCategories(mgPageControler.vueLoader.$children[key].loadCategories);
                });

            }
        }
    }
}
