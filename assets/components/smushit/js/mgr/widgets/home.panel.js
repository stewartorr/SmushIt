Smushit.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        border: false,
        cls: "container home-panel",
        defaults: {
            collapsible: false,
            autoHeight: true
        },
        items: [
            {
                html: "<h2>" + _("smushit") + "</h2>",
                border: false,
                cls: "modx-page-header",
            },
            {
                xtype: "modx-tabs",
                defaults: { border: false, autoHeight: true },
                border: true,
                items: [
                    {
                        title: _("smushit"),
                        defaults: { autoHeight: true },
                        items: [
                            {
                                html:
                                    "<p>" +
                                    _("smushit.management_desc") +
                                    "</p>",
                                border: false,
                                bodyCssClass: "panel-desc",
                            },
                            {
                                xtype: 'smushit-grid-smushit'
                                ,cls: 'main-wrapper'
                                ,preventRender: true
                            }
                        ],
                    },
                ],
                // only to redo the grid layout after the content is rendered
                // to fix overflow components' panels, especially when scroll bar is shown up
                listeners: {
                    afterrender: function (tabPanel) {
                        tabPanel.doLayout();
                    },
                },
            },
        ],
    });
    Smushit.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(Smushit.panel.Home, MODx.Panel);
Ext.reg("smushit-panel-home", Smushit.panel.Home);