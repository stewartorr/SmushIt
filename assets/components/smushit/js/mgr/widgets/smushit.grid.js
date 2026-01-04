Smushit.grid.Smushit = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        id: "smushit-grid-smushit",
        url: Smushit.config.connectorUrl,
        baseParams: { action: "mgr/items/getlist" },
        fields: ["id", "src", "dest", "original","optimised","format","smushdate"],
        paging: true,
        remoteSort: true,
        anchor: "97%",
        autoExpandColumn: "src",
        columns: [
            {
                header: _("id"),
                dataIndex: "id",
                sortable: true,
                width: 25,
            },
            {
                header: _("smushit.management_preview"),
                dataIndex: "dest",
                sortable: true,
                width: 50,
                renderer: function(value, metaData, record) {
                    if (!value) {
                        return '';
                    }
                    metaData.attr = 'ext:qtip="<img src=\'/' + value + '\' style=\'max-width:200px; max-height:200px;\' />"';
                    return '<img src="/' + value + '" style="height:40px; width: 40px; object-fit: cover;" />';
                }
            },
            {
                header: _("smushit.management_src"),
                dataIndex: "src",
                sortable: true,
                width: 250,
                renderer: function(value, metaData, record) {
                    return '<a href="/' + value + '" target="_blank">' + value + '</a>';
                }
            },
            {
                header: _("smushit.management_dest"),
                dataIndex: "dest",
                sortable: true,
                width: 250,
                renderer: function(value) {
                    return '<a href="/' + value + '" target="_blank">' + value + '</a>';
                }
            },
            {
                header: _("smushit.management_original"),
                dataIndex: "original",
                sortable: true,
                width: 80,
                renderer: function(value, metaData, record) {
                    return Ext.util.Format.fileSize(value);
                }
            },
            {
                header: _("smushit.management_optimised"),
                dataIndex: "optimised",
                sortable: true,
                width: 80,
                renderer: function(value, metaData, record) {
                    return Ext.util.Format.fileSize(value);
                }
            },
            {
                header: _("smushit.management_saving"),
                dataIndex: "optimised",
                sortable: true,
                width: 80,
                renderer: function(value, metaData, record) {
                    var a = parseFloat(record.get('original')) || 0;
                    var b = parseFloat(record.get('optimised')) || 0;

                    if (a === 0) {
                        return '—';
                    }

                    var percent = ((b - a) / a) * 100;

                    if (percent < 0) {
                        metaData.css = 'green';
                    } else if (percent > 0) {
                        metaData.css = 'red';
                    }

                    return Ext.util.Format.number(percent, '0.00') + '%';
                }
            },
            {
                header: _("smushit.management_format"),
                dataIndex: "format",
                sortable: true,
                width: 80,
            },
            {
                header: _("smushit.management_smushdate"),
                dataIndex: "smushdate",
                sortable: true,
                width: 120,
            },
        ]
        ,tbar:[{
    xtype: 'textfield'
    ,id: 'smushit-search-filter'
    ,emptyText: _('smushit.search...')
    ,listeners: {
        'change': {fn:this.search,scope:this}
        ,'render': {fn: function(cmp) {
            new Ext.KeyMap(cmp.getEl(), {
                key: Ext.EventObject.ENTER
                ,fn: function() {
                    this.fireEvent('change',this);
                    this.blur();
                    return true;
                }
                ,scope: cmp
            });
        },scope:this}
    }
}]
    });
    Smushit.grid.Smushit.superclass.constructor.call(this, config);
};
Ext.extend(Smushit.grid.Smushit, MODx.grid.Grid, {
    search: function (tf, nv, ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },
});
Ext.reg("smushit-grid-smushit", Smushit.grid.Smushit);