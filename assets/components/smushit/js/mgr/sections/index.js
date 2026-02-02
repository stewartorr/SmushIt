Ext.onReady(function () {
    MODx.load({ xtype: "smushit-page-home" });
});
Smushit.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [
            {
                xtype: "smushit-panel-home",
                renderTo: "smushit-panel-home-div"
            }
        ]
    });
    Smushit.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(Smushit.page.Home, MODx.Component);
Ext.reg("smushit-page-home", Smushit.page.Home);